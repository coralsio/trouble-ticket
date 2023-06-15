<div>
    @if(!in_array($troubleTicket->status, \Corals\Modules\TroubleTicket\Models\TroubleTicket::LOCKED_STATUSES))
        <div class="">
            <h4>@lang('TroubleTicket::labels.comment.add_new_comment')</h4>
            {!! CoralsForm::openForm(null,['url'=>"trouble-ticket/trouble-tickets/$troubleTicket->hashed_id/create-comment",'data-page_action'=>'loadComments']) !!}

            {!! CoralsForm::textarea('body','utility-comment::attributes.comments.body', true, null, []) !!}

            @if(user() && user()->can('seePrivateComments', \Corals\Utility\Comment\Models\Comment::class))
                {!! CoralsForm::checkbox('is_private','utility-comment::attributes.comments.is_private',false,1) !!}
            @endif

            {!! CoralsForm::formButtons('Corals::labels.submit', [], ['show_cancel'=>false]) !!}

            {!! CoralsForm::closeForm() !!}
        </div>
    @endif
    <div>
        <h4>@lang('TroubleTicket::labels.comment.comments_list')</h4>
        <hr/>
        <div id="comments-list">
            @include('TroubleTicket::troubleTickets.partials.tt_comments', ['troubleTicket'=>$troubleTicket])
        </div>
    </div>
</div>

@push('partial_js')
    <script>
        function loadComments() {
            $.get(`{{ url($troubleTicket->getShowURL().'/comments') }}`,
                comments => {
                    $('#comments-list').html(comments);
                });
        }
    </script>
@endpush
