<?php


namespace Corals\Modules\TroubleTicket\Http\Controllers;


use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\Utility\Comment\Http\Controllers\CommentBaseController;
use Corals\Modules\Utility\Comment\Services\CommentService;
use Illuminate\Http\Request;

class CommentsController extends CommentBaseController
{
    public function __construct(CommentService $commentService)
    {
        $this->corals_middleware_except = ['createTTComment', 'ttComments'];
        parent::__construct($commentService);
    }

    /**
     *
     */
    protected function setCommonVariables()
    {
        $this->commentableClass = TroubleTicket::class;
    }

    /**
     * @param Request $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function ttComments(Request $request, TroubleTicket $troubleTicket)
    {
        abort_if(!$request->ajax(), 404);

        return view('TroubleTicket::troubleTickets.partials.tt_comments')
            ->with(compact('troubleTicket'));
    }

    public function createTTComment(Request $request, $commentable_hashed_id)
    {
        $this->validate($request, [
            'body' => 'required|max:191',
        ]);

        $commentable = $this->commentableClass::findByHash($commentable_hashed_id);

        if (!$commentable) {
            abort(404, 'Not Found!!');
        }

        if (!user()) {
            abort_if(in_array($commentable->status, TroubleTicket::LOCKED_STATUSES), 403, 'Forbidden');

            $this->author = $commentable->owner;
        } else {
            $this->author = user();
        }

        $response = $this->doCreate($request, $commentable_hashed_id);

        $commentable->logActivity(trans('TroubleTicket::activities.tt_commented',
            ['author' => $this->author->getIdentifier()]));

        return $response;
    }
}
