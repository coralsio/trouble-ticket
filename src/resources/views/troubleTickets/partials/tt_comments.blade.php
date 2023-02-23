<div class="table-responsive">
    <table class="table table-striped">
        @forelse($troubleTicket->comments as $comment)
            @continue($comment->is_private && !(user() && user()->can('seePrivateComments', $comment)))
            @include('TroubleTicket::troubleTickets.partials.single_comment',compact('comment'))
        @empty
            <tr>
                <td class="text-center">@lang('TroubleTicket::labels.comment.no_comments')</td>
            </tr>
        @endforelse
    </table>
</div>
