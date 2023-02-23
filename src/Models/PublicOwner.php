<?php

namespace Corals\Modules\TroubleTicket\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class PublicOwner extends BaseModel
{
    use PresentableTrait;
    use LogsActivity;
    use ModelPropertiesTrait;
    use Notifiable;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'trouble_ticket.models.public_owner';

    protected $table = 'tt_public_owners';

    protected $casts = [
        'properties' => 'json',
    ];

    public $guarded = ['id'];

    public function getIdentifier($key = null)
    {
        return sprintf("%s (%s)", $this->name, $this->email);
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getPictureAttribute()
    {
        $id = $this->attributes['id'] ?? 1;
        $avatar = 'avatar_' . ($id % 10) . '.png';

        return asset(config('user.models.user.default_picture') . $avatar);
    }
}
