<?php

namespace Corals\Modules\TroubleTicket\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Assignment extends BaseModel
{
    use PresentableTrait, LogsActivity, ModelPropertiesTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'trouble_ticket.models.assignment';

    protected $table = 'tt_assignments';

    protected $casts = [
        'properties' => 'json',
    ];

    public $guarded = ['id'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignee(): MorphTo
    {
        return $this->morphTo();
    }
}
