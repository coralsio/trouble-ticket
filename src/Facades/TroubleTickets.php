<?php

namespace Corals\Modules\TroubleTicket\Facades;

use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Illuminate\Support\Facades\Facade;

/**
 * Class TroubleTickets
 * @package Corals\Modules\Utility\Facades\TroubleTicket
 * @method static autoAssignment(TroubleTicket $troubleTicket)
 * @method static handleAssignment(TroubleTicket $troubleTicket, $assigneeId = null)
 * @method static getBestAssignee(TroubleTicket $troubleTicket)
 * @method static getTTModels()
 * @method static getTTModelDetails($troubleTicket)
 * @method static ttAssignmentNotification(TroubleTicket $troubleTicket)
 * @method static getFirstTTAssignee(TroubleTicket $troubleTicket)
 * @method static getTeamsList()
 * @method static getIssuesList()
 */
class TroubleTickets extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\TroubleTicket\Classes\TroubleTickets::class;
    }
}
