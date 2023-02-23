<style>

    #tt-activity-items .tt-activity-item:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

</style>
<div class="pt-2 p-t-20" id="tt-activity-items">
    @foreach($troubleTicket->ttActivities as $activity)
        <div class="tt-activity-item p-1 p-10">
            <ul class="list-inline">
                <li class="list-inline-item">
                    <i class="fa fa-user-o fa-fw"></i> {!!  $activity->present('causer_id') !!}
                </li>
                <li class="list-inline-item">
                    <i class="fa fa-clock-o fa-fw"></i> {!!  $activity->present('created_at') !!}
                </li>
            </ul>
            <p>{!!  $activity->description !!}</p>
            <ul class="list-unstyled">
                @foreach($troubleTicket->handleActivityRecord($activity) as $key => $item)
                    <li>
                        <b>{{ $item['label'] }}</b>:
                        @if(!empty($item['old_value']))
                            <small class="text-muted">
                                @lang('TroubleTicket::labels.history.old'):
                            </small> {!! $item['old_value'] !!}
                            <small class="text-muted">@lang('TroubleTicket::labels.history.changed_to'):
                            </small>
                        @endif
                        {!! $item['value'] !!}
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
