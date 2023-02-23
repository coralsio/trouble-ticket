<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>{{ $troubleTicket->code }}</h3>
            <ul class="list-inline">
                <li class="list-inline-item">
                    {{ trans('Corals::attributes.created_at') }}:
                    <i class="fa fa-clock-o fa-fw"></i> {{ $troubleTicket->present('created_at') }}
                </li>
            </ul>
            <hr/>
        </div>
        <div class="col-md-6 text-right">
            @if(!in_array($troubleTicket->status, ['resolved']))
                {!! CoralsForm::link(url($troubleTicket->getShowURL().'/resolve'), "TroubleTicket::labels.trouble_ticket.resolve",
                    ['class'=>'btn btn-success btn-sm', 'data-action'=>'post','data-page_action'=>'site_reload']) !!}
            @endif
            @if(in_array($troubleTicket->status, \Corals\Modules\TroubleTicket\Models\TroubleTicket::LOCKED_STATUSES))
                {!! CoralsForm::link(url($troubleTicket->getShowURL().'/reopen'), "TroubleTicket::labels.trouble_ticket.re_open",
                    ['class'=>'btn btn-danger btn-sm', 'data-action'=>'post','data-page_action'=>'site_reload']) !!}
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            @include('TroubleTicket::troubleTickets.partials.tt_details')
        </div>
        <div class="col-md-8">
            @include('TroubleTicket::troubleTickets.partials.tt_description_section')
        </div>
    </div>
</div>
