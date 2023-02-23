<div class="table-responsive">
    <table class="table table-striped">
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.code')</th>
            <td>{!! $isPublicView?$troubleTicket->presentStripTags('code'):$troubleTicket->present('code') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.title')</th>
            <td>{!! $troubleTicket->present('title') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.status')</th>
            <td>{!! $troubleTicket->present('status') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.priority')</th>
            <td>{!! $troubleTicket->present('priority') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.model')</th>
            <td>{!! $isPublicView?$troubleTicket->presentStripTags('model'):$troubleTicket->present('model') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.category')</th>
            <td>{!! $troubleTicket->present('category') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.issue_type')</th>
            <td>{!! $isPublicView?$troubleTicket->presentStripTags('issue_type'):$troubleTicket->present('issue_type') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.team')</th>
            <td>{!! $isPublicView?$troubleTicket->presentStripTags('team'):$troubleTicket->present('team') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.owner')</th>
            <td>{!! $isPublicView?$troubleTicket->presentStripTags('owner'):$troubleTicket->present('owner') !!}</td>
        </tr>
        <tr>
            <th>@lang('TroubleTicket::attributes.troubleTicket.closed_at')</th>
            <td>{!! $troubleTicket->present('closed_at') !!}</td>
        </tr>
        @can('TroubleTicket::troubleTicket.update', $troubleTicket)
            <tr>
                <th>@lang('TroubleTicket::attributes.troubleTicket.due_date')</th>
                <td>{!! $troubleTicket->present('due_date') !!}</td>
            </tr>
            <tr>
                <th>@lang('TroubleTicket::attributes.troubleTicket.assignee_id')</th>
                <td>{!! $isPublicView?$troubleTicket->presentStripTags('assignee'):$troubleTicket->present('assignee') !!}</td>
            </tr>
            <tr>
                <th>@lang('TroubleTicket::attributes.troubleTicket.estimated_hours')</th>
                <td>{!! $troubleTicket->present('estimated_hours') !!}</td>
            </tr>
            <tr>
                <th>@lang('TroubleTicket::attributes.troubleTicket.is_public')</th>
                <td>{!! $troubleTicket->present('is_public') !!}</td>
            </tr>
            <tr>
                <th>@lang('TroubleTicket::attributes.troubleTicket.archived')</th>
                <td>{!! $troubleTicket->present('archived') !!}</td>
            </tr>
        @endcan
    </table>

    @if(!$isPublicView)
        {!! generateCopyToClipBoard('tt_show', $ttSignedURL, trans('TroubleTicket::labels.trouble_ticket.public_url')) !!}
    @endif
</div>
