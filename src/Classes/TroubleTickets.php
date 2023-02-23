<?php


namespace Corals\Modules\TroubleTicket\Classes;


use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Models\Team;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TroubleTickets
{
    /**
     * @param TroubleTicket $troubleTicket
     * @param null $assigneeId
     */
    public function handleAssignment(TroubleTicket $troubleTicket, $assigneeId = null)
    {
        if (is_null($assigneeId)) {
            $this->autoAssignment($troubleTicket);
        } else {
            $user = User::find($assigneeId);
            $this->doAssign($troubleTicket, $user);
        }
    }

    /**
     * @param TroubleTicket $troubleTicket
     * @param $assignee
     */
    protected function doAssign(TroubleTicket $troubleTicket, $assignee)
    {
        if (!$assignee) {
            return;
        }

        $troubleTicket->assignees()->create([
            'assignee_id' => $assignee->id,
            'assignee_type' => getMorphAlias($assignee),
        ]);

        $troubleTicket->logActivity(trans('TroubleTicket::activities.tt_assigned',
            ['assignee' => $assignee->getIdentifier(), 'code' => $troubleTicket->code]));

        $this->ttAssignmentNotification($troubleTicket);
    }

    /**
     * @param TroubleTicket $troubleTicket
     */
    public function autoAssignment(TroubleTicket $troubleTicket)
    {
        $assignee = $this->getBestAssignee($troubleTicket);

        $this->doAssign($troubleTicket, $assignee);
    }

    /**
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getBestAssignee(TroubleTicket $troubleTicket)
    {
        $team = $troubleTicket->team;

        $teamUsersIds = $team->users()
            ->selectRaw('tt_assignments.assignee_id')
            ->pluck('assignee_id')->toArray();

        return User::query()
            ->whereIn('users.id', $teamUsersIds)
            ->leftJoin('tt_assignments', function ($join) {
                $join->on('users.id', 'assignee_id')
                    ->where('tt_assignments.assignee_type', DB::raw(sprintf("'%s'", getMorphAlias(User::class))));
            })->groupBy('users.id')
            ->selectRaw("count(tt_assignments.id) as min_tt_count, users.*")
            ->orderBy('min_tt_count')
            ->first();
    }


    /**
     * @param TroubleTicket $troubleTicket
     * @return mixed
     */
    public function getFirstTTAssignee(TroubleTicket $troubleTicket)
    {
        if (!$troubleTicket->exists) {
            return optional();
        }

        $assignment = $troubleTicket->assignees()->first();

        return optional(optional($assignment)->assignee);
    }


    /**
     * @param TroubleTicket $troubleTicket
     */
    public function ttAssignmentNotification(TroubleTicket $troubleTicket): void
    {
        event('notifications.trouble_tickets.assignment', [
            'assignee' => $this->getFirstTTAssignee($troubleTicket),
            'trouble_ticket' => $troubleTicket
        ]);
    }

    /**
     * @param IssueType $issueType
     * @return mixed
     */
    public function getSortedIssueTypeSolutions(IssueType $issueType)
    {
        return collect($issueType->solutions)->sortBy->order->toArray();
    }

    public function getTeamsList()
    {
        return Team::pluck('name', 'id');
    }

    public function getIssuesList()
    {
        return IssueType::pluck('title', 'id');
    }
}
