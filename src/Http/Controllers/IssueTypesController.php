<?php

namespace Corals\Modules\TroubleTicket\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\TroubleTicket\DataTables\IssueTypesDataTable;
use Corals\Modules\TroubleTicket\Http\Requests\IssueTypeRequest;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Services\IssueTypeService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class IssueTypesController extends BaseController
{
    protected $issueTypeService;

    public function __construct(IssueTypeService $issueTypeService)
    {
        $this->issueTypeService = $issueTypeService;

        $this->resource_url = config('trouble_ticket.models.issue_type.resource_url');

        $this->resource_model = new IssueType();

        $this->title = 'TroubleTicket::module.issue_type.title';
        $this->title_singular = 'TroubleTicket::module.issue_type.title_singular';

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['getByCategory']);
        parent::__construct();
    }

    /**
     * @param IssueTypeRequest $request
     * @param IssueTypesDataTable $dataTable
     * @return mixed
     */
    public function index(IssueTypeRequest $request, IssueTypesDataTable $dataTable)
    {
        return $dataTable->render('TroubleTicket::issue_types.index');
    }

    /**
     * @param IssueTypeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(IssueTypeRequest $request)
    {
        $issueType = new IssueType();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('TroubleTicket::issue_types.create_edit')->with(compact('issueType'));
    }

    /**
     * @param IssueTypeRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     * @throws ValidationException
     */
    public function store(IssueTypeRequest $request)
    {
        try {
            $this->issueTypeService->store($request, IssueType::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            if ($exception instanceof ValidationException) {
                throw $exception;
            }
            log_exception($exception, IssueType::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param IssueTypeRequest $request
     * @param IssueType $issueType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(IssueTypeRequest $request, IssueType $issueType)
    {
        $this->setViewSharedData([
            'title_singular' => $this->title_singular . " [$issueType->title]",
            'showModel' => $issueType,
        ]);

        return view('TroubleTicket::issue_types.show')->with(compact('issueType'));
    }

    /**
     * @param IssueTypeRequest $request
     * @param IssueType $issueType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(IssueTypeRequest $request, IssueType $issueType)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $issueType->title])
        ]);

        return view('TroubleTicket::issue_types.create_edit')->with(compact('issueType'));
    }

    /**
     * @param IssueTypeRequest $request
     * @param IssueType $issueType
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(IssueTypeRequest $request, IssueType $issueType)
    {
        try {
            $this->issueTypeService->update($request, $issueType);

            flash(trans('Corals::messages.success.updated', ['item' => $issueType->title]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, IssueType::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    public function destroy(IssueTypeRequest $request, IssueType $issueType)
    {
        try {
            $this->issueTypeService->destroy($request, $issueType);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, IssueTypesController::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param $index
     * @param IssueType $issueType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSolutionForm(Request $request, IssueType $issueType)
    {
        abort_if(!$request->ajax(), 404);

        $index = $request->get('index', 0);

        return view('TroubleTicket::issue_types.partials.solution_form')->with(compact('index', 'issueType'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        return IssueType::query()
            ->join('utility_model_has_category', function ($joinCategories) use ($categoryId) {
                $joinCategories->on('tt_ticket_issue_types.id', 'utility_model_has_category.model_id')
                    ->where('utility_model_has_category.model_type', getModelMorphMap(IssueType::class))
                    ->where('utility_model_has_category.category_id', $categoryId);
            })->select('tt_ticket_issue_types.*')
            ->pluck('title', 'id')
            ->toArray();
    }
}
