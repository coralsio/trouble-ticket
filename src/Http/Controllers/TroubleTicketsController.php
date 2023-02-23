<?php

namespace Corals\Modules\TroubleTicket\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\TroubleTicket\DataTables\TroubleTicketsDataTable;
use Corals\Modules\TroubleTicket\Http\Requests\TroubleTicketRequest;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\TroubleTicket\Services\TroubleTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class TroubleTicketsController extends BaseController
{
    protected $troubleTicketService;

    public function __construct(TroubleTicketService $troubleTicketService)
    {
        $this->troubleTicketService = $troubleTicketService;

        $this->resource_url = config('trouble_ticket.models.troubleTicket.resource_url');

        $this->resource_model = new TroubleTicket();

        $this->title = 'TroubleTicket::module.troubleTicket.title';
        $this->title_singular = 'TroubleTicket::module.troubleTicket.title_singular';

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['markAs', 'addAttachments']);

        parent::__construct();
    }

    /**
     * @param TroubleTicketRequest $request
     * @param TroubleTicketsDataTable $dataTable
     * @return mixed
     */
    public function index(TroubleTicketRequest $request, TroubleTicketsDataTable $dataTable)
    {
        return $dataTable->render('TroubleTicket::troubleTickets.index');
    }

    /**
     * @param TroubleTicketRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(TroubleTicketRequest $request)
    {
        $troubleTicket = new TroubleTicket();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('TroubleTicket::troubleTickets.create_edit')->with(compact('troubleTicket'));
    }

    /**
     * @param TroubleTicketRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     * @throws ValidationException
     */
    public function store(TroubleTicketRequest $request)
    {
        try {
            $troubleTicket = $this->troubleTicketService->store($request, TroubleTicket::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();

            return redirectTo($troubleTicket->getShowURL());
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicket::class, 'store');

            return redirectTo($this->resource_url);
        }
    }

    /**
     * @param TroubleTicketRequest $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TroubleTicketRequest $request, TroubleTicket $troubleTicket)
    {
        $this->setViewSharedData([
            'title_singular' => $this->title_singular . " [$troubleTicket->code]",
            'showModel' => $troubleTicket
        ]);

        $ttSignedURL = URL::signedRoute('publicTroubleTicket', ['trouble_ticket' => $troubleTicket->hashed_id]);

        return view('TroubleTicket::troubleTickets.show')->with(compact('troubleTicket', 'ttSignedURL'));
    }

    /**
     * @param TroubleTicketRequest $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TroubleTicketRequest $request, TroubleTicket $troubleTicket)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $troubleTicket->code])
        ]);

        return view('TroubleTicket::troubleTickets.create_edit')->with(compact('troubleTicket'));
    }

    /**
     * @param TroubleTicketRequest $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     * @throws ValidationException
     */
    public function update(TroubleTicketRequest $request, TroubleTicket $troubleTicket)
    {
        try {
            $this->troubleTicketService->update($request, $troubleTicket);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicket::class, 'update');
        }

        return redirectTo($troubleTicket->getShowURL());
    }

    /**
     * @param TroubleTicketRequest $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Http\JsonResponse
     */

    public function bulkAction(BulkRequest $request)
    {
        try {
            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);

            $ids = array_map(function ($hashedId) {
                return hashids_decode($hashedId);
            }, $selection);


            $troubleTickets = TroubleTicket::whereIn('id', $ids);

            switch ($action) {
                case 'update_status':
                    $troubleTickets->update(['status' => $request->input('status')]);
                    break;
                case 'delete':
                    $troubleTickets->delete();
                    $message = [
                        'level' => 'success',
                        'message' => trans('Corals::messages.success.deleted', ['item' => $this->title])
                    ];
                    break;
                case 'archive':
                    $troubleTickets->update(['archived' => true]);
                    break;
                case 'unarchive':
                    $troubleTickets->update(['archived' => false]);
                    break;
            }

            if (!isset($message)) {
                $message = [
                    'level' => 'success',
                    'message' => trans('Corals::messages.success.updated', ['item' => $this->title])
                ];
            }

        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicketsController::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function destroy(TroubleTicketRequest $request, TroubleTicket $troubleTicket)
    {
        try {
            $this->troubleTicketService->destroy($request, $troubleTicket);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicketsController::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param TroubleTicket|null $troubleTicket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateStatusModal(Request $request, TroubleTicket $troubleTicket = null)
    {
        abort_if(!$request->ajax(), 404);

        $this->authorize('update', TroubleTicket::class);


        $view = view('TroubleTicket::troubleTickets.partials.update_status_modal');

        if ($troubleTicket) {
            $doUpdateStatusURL = $troubleTicket->getShowURL() . '/do-update-status';
            $view->with(['model' => $troubleTicket]);
        } else {
            $doUpdateStatusURL = url(config('trouble_ticket.models.troubleTicket.resource_url') . '/bulk-action');
        }

        return $view->with(compact('doUpdateStatusURL'));
    }

    /**
     * @param Request $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     * @throws ValidationException
     */
    public function partialUpdate(Request $request, TroubleTicket $troubleTicket)
    {
        try {
            $request->request->add(Arr::except($troubleTicket->attributesToArray(),
                ['status', 'priority', 'category_id', 'issue_type_id', 'assignee_id']));

            $this->troubleTicketService->update($request, $troubleTicket);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicket::class, 'update');
        }

        return redirectTo($troubleTicket->getShowURL());
    }

    /**
     * @param Request $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function addAttachments(Request $request, TroubleTicket $troubleTicket)
    {
        return $this->troubleTicketService->addAttachments($request, $troubleTicket);
    }

    public function doUpdateStatus(Request $request, TroubleTicket $troubleTicket)
    {
        $this->authorize('update', $troubleTicket);
        try {
            $oldStatus = $troubleTicket->present('status');
            $newStatus = $request->get('status');

            $troubleTicket->update(['status' => $newStatus]);

            if ($oldStatus != $newStatus) {
                $troubleTicket->refresh();
                $troubleTicket->resetPresenterData();

                $troubleTicket->logActivity(trans('TroubleTicket::activities.tt_update_status', [
                    'code' => $troubleTicket->code,
                    'old' => $oldStatus,
                    'new' => $troubleTicket->present('status')
                ]));
            }

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicket::class, 'update');

            $message = [
                'level' => 'success',
                'message' => $exception->getMessage()
            ];

            $code = 200;
        }

        return response()->json($message, $code ?? 200);
    }

    /**
     * @param Request $request
     * @param TroubleTicket $troubleTicket
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAs(Request $request, TroubleTicket $troubleTicket, $status)
    {
        try {
            $causer = user() ?: $troubleTicket->owner;

            switch ($status) {
                case 'reopen':
                    $troubleTicket->update(['status' => 'open', 'closed_at' => null]);

                    $troubleTicket->logActivity(trans('TroubleTicket::activities.tt_reopen', [
                        'code' => $troubleTicket->code,
                        'causer' => $causer->getIdentifier(),
                    ]));
                    break;
                case 'resolve':
                    $troubleTicket->update(['status' => 'resolved', 'closed_at' => now()]);
                    $troubleTicket->logActivity(trans('TroubleTicket::activities.tt_resolve', [
                        'code' => $troubleTicket->code,
                        'causer' => $causer->getIdentifier(),
                    ]));
                    break;
                default:
                    throw new \Exception(trans('TroubleTicket::exceptions.trouble_ticket.invalid_status',
                        ['status' => $status]));
            }

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular]),
            ];
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicket::class, 'update');
            $message = ['level' => 'success', 'message' => $exception->getMessage()];
            $code = 400;
        }
        return response()->json($message, $code ?? 200);

    }
}
