<div class="col-md-12 solution-form">
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-sm btn-danger pull-right remove-solution-form">
                @lang('TroubleTicket::labels.issue_type.remove')
            </a>
        </div>
    </div>
    {!! CoralsForm::text("solutions[$index][title]",'TroubleTicket::attributes.issue_type.solutions.title', true) !!}
    {!! CoralsForm::number("solutions[$index][order]",'TroubleTicket::attributes.issue_type.solutions.order', true, $issueType->solutions[$index]['order']??($index+1)) !!}
    {!! CoralsForm::textarea("solutions[$index][details]",'TroubleTicket::attributes.issue_type.solutions.details', true) !!}
    <hr>
</div>
