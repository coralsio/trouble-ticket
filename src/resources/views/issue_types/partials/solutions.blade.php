<h4>@lang('TroubleTicket::labels.issue_type.solutions')</h4>
<hr>
<div id="solutions">
    @foreach(\TroubleTickets::getSortedIssueTypeSolutions($issueType) ?? [] as $index => $solution)
        @include('TroubleTicket::issue_types.partials.solution_form',['index'=>$index])
    @endforeach
</div>

<a class="btn btn-success btn-sm" id="add-new-solution-form">
    @lang('TroubleTicket::labels.issue_type.add_new_solution')
</a>

@push('partial_js')
    <script>
        $('#add-new-solution-form').on('click', function () {
            let index = $('.solution-form').length;

            let url = '{{url('trouble-ticket/issue-types/get-solution-form?index=')}}' + index;

            $.get(url, solutionForm => {
                $('#solutions').append(solutionForm);
            });
        });

        $(document).on('click', '.remove-solution-form', function () {
            let solutionForm = $(this).closest('.solution-form');
            solutionForm.remove();
        })
    </script>
@endpush
