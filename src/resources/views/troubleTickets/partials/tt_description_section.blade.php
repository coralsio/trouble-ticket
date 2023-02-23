<h4>@lang('TroubleTicket::attributes.troubleTicket.description')</h4>
<div class="well card card-body bg-light">
    {!! $troubleTicket->present('description') !!}
</div>
@can('TroubleTicket::troubleTicket.update', $troubleTicket)
    @if($solutions = $troubleTicket->present('solutions'))
        <hr/>
        <div>
            <h4>@lang('TroubleTicket::labels.issue_type.solutions')</h4>
            {!! $solutions !!}
        </div>
    @endif
@endcan
<div class="row">
    <div class="col-md-12">
        <div class="col-md-12">
            <hr/>
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a data-toggle="tab" href="#tt_comments" class="nav-link active">
                        @lang('TroubleTicket::labels.comment.comments_list')
                    </a>
                </li>
                <li class="nav-item">
                    <a data-toggle="tab" href="#tt_activities" class="nav-link">
                        @lang('TroubleTicket::labels.history.activities')
                    </a>
                </li>
                <li class="nav-item">
                    <a data-toggle="tab" href="#tt_attachments" class="nav-link">
                        @lang('TroubleTicket::labels.trouble_ticket.attachments')
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="tt_comments" class="tab-pane active">
                    @include('TroubleTicket::troubleTickets.partials.comments')
                </div>
                <div id="tt_activities" class="tab-pane">
                    @include('TroubleTicket::troubleTickets.partials.activities')
                </div>
                <div id="tt_attachments" class="tab-pane">
                    @include('Media::attachments_section',[
                            'showAttachmentsForm'=> !in_array($troubleTicket->status, \Corals\Modules\TroubleTicket\Models\TroubleTicket::LOCKED_STATUSES),
                             'object'=>$troubleTicket,
                             'url'=> url($troubleTicket->getShowURL(). '/add-attachments')
                        ])
                </div>
            </div>
        </div>
    </div>
</div>
