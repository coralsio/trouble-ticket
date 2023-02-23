<?php

namespace Corals\Modules\TroubleTicket\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\TroubleTicket\DataTables\TeamsDataTable;
use Corals\Modules\TroubleTicket\Http\Requests\TeamRequest;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Models\Team;
use Corals\Modules\TroubleTicket\Services\TeamService;
use Corals\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamsController extends BaseController
{
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;

        $this->resource_url = config('trouble_ticket.models.team.resource_url');

        $this->resource_model = new Team();

        $this->title = 'TroubleTicket::module.team.title';
        $this->title_singular = 'TroubleTicket::module.team.title_singular';

        parent::__construct();
    }

    /**
     * @param TeamRequest $request
     * @param TeamsDataTable $dataTable
     * @return mixed
     */
    public function index(TeamRequest $request, TeamsDataTable $dataTable)
    {
        return $dataTable->render('TroubleTicket::teams.index');
    }

    /**
     * @param TeamRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(TeamRequest $request)
    {
        $team = new Team();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('TroubleTicket::teams.create_edit')->with(compact('team'));
    }

    /**
     * @param TeamRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     * @throws ValidationException
     */
    public function store(TeamRequest $request)
    {
        try {
            $this->teamService->store($request, Team::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            if ($exception instanceof ValidationException) {
                throw $exception;
            }
            log_exception($exception, Team::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param TeamRequest $request
     * @param Team $team
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TeamRequest $request, Team $team)
    {
        $this->setViewSharedData([
            'title_singular' => $this->title_singular . " [$team->name]",
            'showModel' => $team,
        ]);

        return view('TroubleTicket::teams.show')->with(compact('team'));
    }

    /**
     * @param TeamRequest $request
     * @param Team $team
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TeamRequest $request, Team $team)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $team->name])
        ]);

        return view('TroubleTicket::teams.create_edit')->with(compact('team'));
    }

    /**
     * @param TeamRequest $request
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(TeamRequest $request, Team $team)
    {
        try {
            $this->teamService->update($request, $team);

            flash(trans('Corals::messages.success.updated', ['item' => $team->name]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Team::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    public function destroy(TeamRequest $request, Team $team)
    {
        try {
            $this->teamService->destroy($request, $team);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, TeamsController::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getByIssueType(Request $request)
    {
        $issueTypeId = $request->get('issue_type_id');

        return Team::query()->join('tt_ticket_issue_types', function ($joinAssignments) {
            $joinAssignments->on('tt_ticket_issue_types.team_id', 'tt_teams.id');
        })->where('tt_ticket_issue_types.id', $issueTypeId)
            ->select('tt_teams.*')->pluck('name', 'id');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAssigneesList(Request $request)
    {
        $issueTypeId = $request->get('issue_type_id');

        if (!$issueTypeId) {
            return [];
        }

        $issueType = IssueType::query()->findOrFail($issueTypeId);

        $teamId = $issueType->team_id;

        $users = User::query()
            ->join('tt_assignments', function ($joinTTAssignments) use ($teamId) {
                $joinTTAssignments->on('users.id', 'tt_assignments.assignee_id')
                    ->where('tt_assignments.assignee_type', getModelMorphMap(User::class))
                    ->where('tt_assignments.model_type', getModelMorphMap(Team::class))
                    ->where('tt_assignments.model_id', $teamId);
            })->select('users.*')
            ->get();
        $result = [];

        foreach ($users as $user) {
            $result[$user->id] = $user->getIdentifier();
        }

        return $result;
    }
}
