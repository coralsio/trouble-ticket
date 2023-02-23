<div class="row">
    <div class="col-md-12">
        {!! Form::open(['url'=>$doUpdateStatusURL,'class'=>'ajax-form','data-page_action'=>isset($model) ? 'closeModal': 'updateStatus' ,'data-table'=>'#TroubleTicketsDataTable']) !!}

        <div class="refund-options">
            {!! CoralsForm::radio('status','Status', true, \ListOfValues::get('tt_status'), isset($model) ? $model->status :null ) !!}
        </div>

        @if(!isset($model))
            {!! Form::hidden('action','update_status') !!}
            {!! Form::hidden('selection',null,['id'=>'tt_statuses_ids']) !!}
        @endif

        {!! CoralsForm::formButtons("<i class='fa fa-send'></i> Submit",[],['show_cancel'=>false]) !!}
        {!! Form::close() !!}
    </div>
</div>

@if(!isset($model))
    <script>

        checked_ids = $('#TroubleTicketsDataTable tbody input:checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        $('#tt_statuses_ids').val(JSON.stringify(checked_ids));

        function updateStatus(r, f) {
            closeModal(r, f);
            $('#TroubleTicketsDataTable').DataTable().ajax.reload();
        }
    </script>

@endif