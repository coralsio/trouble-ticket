<?php

namespace Corals\Modules\TroubleTicket\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Utility\Category\Traits\ModelHasCategory;
use Spatie\Activitylog\Traits\LogsActivity;

class IssueType extends BaseModel
{
    use PresentableTrait;
    use LogsActivity;
    use ModelPropertiesTrait;
    use ModelHasCategory;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'trouble_ticket.models.issue_type';

    protected $table = 'tt_ticket_issue_types';

    protected $casts = [
        'properties' => 'json',
        'solutions' => 'json',
    ];

    public $guarded = ['id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
