<?php

namespace Corals\Modules\TroubleTicket\Models;

use Corals\Activity\Models\Activity;
use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Traits\ModelUniqueCode;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\User\Models\User;
use Corals\Utility\Comment\Traits\ModelHasComments;
use Corals\Utility\Category\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TroubleTicket extends BaseModel implements HasMedia
{
    use PresentableTrait;
    use LogsActivity;
    use ModelPropertiesTrait;
    use ModelUniqueCode;
    use ModelHasComments;
    use InteractsWithMedia;

    public const LOCKED_STATUSES = ['closed', 'resolved'];
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'trouble_ticket.models.troubleTicket';

    protected $table = 'tt_trouble_tickets';

    protected $casts = [
        'properties' => 'json',
    ];

    public $guarded = ['id'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignees()
    {
        return $this->morphMany(Assignment::class, 'model')
            ->where('assignee_type', getMorphAlias(User::class));
    }

    public function issueType()
    {
        return $this->belongsTo(IssueType::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getIsPublicAttribute()
    {
        return $this->owner instanceof PublicOwner;
    }

    public function getPublicOwnerAttribute()
    {
        return [
            'email' => optional($this->owner)->email,
            'name' => optional($this->owner)->name,
        ];
    }

    public function logActivity($description, $properties = [])
    {
        $activity = activity('TroubleTicket')->on($this);

        if (user()) {
            $activity->causedBy(user());
        } else {
            // may be change it to be by owner owner
            $activity->causedByAnonymous();
        }

        $activity->withProperties($properties);

        $activity->log($description);
    }

    public function ttActivities()
    {
        return $this->morphMany(Activity::class, 'subject')->latest('id')->inLog('TroubleTicket');
    }

    public function handleActivityRecord($activity)
    {
        $changes = $activity->changes();

        $skippedAttr = ['owner_type', 'owner_id',];

        $result = [];

        foreach ($changes as $changeKey => $attributes) {
            $tt = new TroubleTicket();

            $tt->fill($attributes);

            $formattedAttributes = [];

            foreach ($attributes as $key => $value) {
                if (in_array($key, $skippedAttr)) {
                    continue;
                }

                $key = str_replace('_id', '', $key);

                $formattedAttributes[$key] = [
                    'label' => trans('TroubleTicket::attributes.troubleTicket.' . $key),
                    'value' => $tt->present($key),
                ];
            }

            $result[$changeKey] = $formattedAttributes;
        }

        $final = [];

        foreach ($result['attributes'] ?? [] as $key => $item) {
            $oldValue = Arr::get($result, "old.$key.value");

            if (! empty($oldValue)) {
                $item['old_value'] = $oldValue;
            }

            if (empty(trim($oldValue, '-')) && empty(trim($item['value'], '-'))) {
                continue;
            }

            $final[$key] = $item;
        }

        return $final;
    }
}
