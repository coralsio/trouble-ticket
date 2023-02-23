<?php


namespace Corals\Modules\TroubleTicket\Http\Controllers;

use Corals\Foundation\Http\Controllers\PublicBaseController;
use Corals\Modules\CMS\Traits\SEOTools;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\TroubleTicket\Services\TroubleTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class PublicTroubleTicketsController extends PublicBaseController
{
    use SEOTools;

    protected $troubleTicketService;

    public function __construct(TroubleTicketService $troubleTicketService)
    {
        $this->troubleTicketService = $troubleTicketService;

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, TroubleTicket $troubleTicket)
    {
        $isPublicView = true;

        $seoItem = [
            'title' => $troubleTicket->code,
            'meta_description' => '',
            'url' => $request->fullUrl(),
            'type' => 'TroubleTicket',
        ];

        $this->setSEO((object)$seoItem);

        return view('tt_show')->with(compact('troubleTicket', 'isPublicView'));
    }

    public function create(Request $request)
    {
        if (user()) {
            return redirect(config('trouble_ticket.models.troubleTicket.resource_url') . '/create');
        }
        
        $troubleTicket = new TroubleTicket();

        view()->share([
            'resource_url' => $request->url(),
            'title_singular' => trans('Corals::labels.create_title',
                ['title' => trans('TroubleTicket::module.troubleTicket.title_singular')]),
        ]);

        $seoItem = [
            'title' => trans('TroubleTicket::module.troubleTicket.title'),
            'meta_description' => '',
            'url' => $request->fullUrl(),
            'type' => 'TroubleTicket',
        ];

        $this->setSEO((object)$seoItem);

        return view('tt_create')->with(compact('troubleTicket'));
    }

    public function doCreate(Request $request)
    {
        try {
            $troubleTicket = $this->troubleTicketService->store($request, TroubleTicket::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();

            $ttSignedURL = URL::signedRoute('publicTroubleTicket', ['trouble_ticket' => $troubleTicket->hashed_id]);

            return redirectTo($ttSignedURL);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicket::class, 'doCreate');
            return response()->json(['level' => 'error', 'message' => $exception->getMessage(), 400]);
        }
    }
}
