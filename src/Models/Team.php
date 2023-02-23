<?php

namespace Corals\Modules\TroubleTicket\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\User\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends BaseModel
{
    use PresentableTrait;
    use LogsActivity;
    use ModelPropertiesTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'trouble_ticket.models.team';

    protected $table = 'tt_teams';

    protected $casts = [
        'properties' => 'json',
    ];

    public $guarded = ['id'];

    public function users()
    {
        return $this->morphMany(Assignment::class, 'model')
            ->where('assignee_type', getMorphAlias(User::class));
    }
}
