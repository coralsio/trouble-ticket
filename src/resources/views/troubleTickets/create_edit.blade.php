@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tt_troubleTicket_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    @component('components.box')
        {!! CoralsForm::openForm($troubleTicket,['files' => true]) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('title','TroubleTicket::attributes.troubleTicket.title', true) !!}
                        @if(user()->can('fullCreate', $troubleTicket))
                            {!! CoralsForm::select('status','TroubleTicket::attributes.troubleTicket.status', \ListOfValues::get('tt_status'), true,$troubleTicket->exists?$troubleTicket->status:'new',[], 'select2') !!}
                        @endif
                        {!! CoralsForm::select('priority','TroubleTicket::attributes.troubleTicket.priority', \ListOfValues::get('tt_priority'), true,null,[], 'select2') !!}
                        @if(user()->can('fullCreate', $troubleTicket))
                            {!! CoralsForm::checkbox('is_public_owner','TroubleTicket::labels.trouble_ticket.public_owner', $troubleTicket->is_public, 1, ['inline-form'=>true]) !!}
                            <div class="toggle-wrapper">
                                {!! CoralsForm::select('owner_id','TroubleTicket::attributes.troubleTicket.owner', [],  true, null, [
                                        'class'=>'select2-ajax',
                                        'multiple'=> false,
                                        'data'=>[
                                            'model'=>  \Corals\User\Models\User::class,
                                            'columns'=> json_encode(['email','name','last_name']),
                                            'selected'=> json_encode([$troubleTicket->owner_id]),
                                             'where'=>''
                                            ],
                                        ], 'select2') !!}
                            </div>
                            <div class="toggle-wrapper">
                                {!! CoralsForm::email('public_owner[email]', 'TroubleTicket::attributes.troubleTicket.public_owner.email', true) !!}
                            </div>
                            <div class="toggle-wrapper">
                                {!! CoralsForm::text('public_owner[name]','TroubleTicket::attributes.troubleTicket.public_owner.name', true) !!}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        {!! CoralsForm::select('model_type', 'TroubleTicket::attributes.troubleTicket.model_type', \Arr::pluck(get_models('trouble_ticket.models.troubleTicket.model_field_models'), 'label', 'model_morph'), false, null,[],'select2') !!}
                        {!! CoralsForm::select('model_id','TroubleTicket::attributes.troubleTicket.model', [],  false, null, [
                                 'class'=>'select2-ajax',
                                 'multiple'=> false,
                                 'data'=> get_model_details('trouble_ticket.models.troubleTicket.model_field_models',$troubleTicket),
                            ], 'select2') !!}
                        {!! CoralsForm::select('category_id','TroubleTicket::attributes.troubleTicket.category', \Category::getCategoriesByParent('tt-categories'), true,null,[
                                'class'=>'dependent-select',
                                'data'=>[
                                    'dependency-field'=>'issue_type_id',
                                    'dependency-ajax-url'=> url('trouble-ticket/issue-types/get-by-category'),
                                    'selected_value'=> $troubleTicket->exists ? $troubleTicket->category_id : null
                                ]
                                ], 'select2') !!}
                        {!! CoralsForm::select('issue_type_id','TroubleTicket::attributes.troubleTicket.issue_type', [], true,null,[
                                         'class'=>'dependent-select',
                                        'data'=>[
                                            'dependency-field'=>'assignee_id',
                                            'dependency-ajax-url'=> url('trouble-ticket/teams/get-assignees-list'),
                                            'selected_value'=> $troubleTicket->exists ? $troubleTicket->issue_type_id : null
                                ],
                                ], 'select2') !!}
                    </div>
                </div>
                @include('Media::attachments_section',[
                         'hasForm'=> false,
                          'object'=>$troubleTicket,
                          'url'=> url($troubleTicket->getShowURL(). '/add-attachments')
                     ])
            </div>
            <div class="col-md-6">
                {!! CoralsForm::textarea('description','TroubleTicket::attributes.troubleTicket.description', true, $troubleTicket->description,['class'=>'ckeditor-simple']) !!}
                @if(user()->can('fullCreate', $troubleTicket))
                    <div class="row">
                        <div class="col-md-6">
                            {!! CoralsForm::number('estimated_hours','TroubleTicket::attributes.troubleTicket.estimated_hours',false,null,['step'=>'0.1','min'=>0]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! CoralsForm::date('due_date','TroubleTicket::attributes.troubleTicket.due_date') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! CoralsForm::date('closed_at','TroubleTicket::attributes.troubleTicket.closed_at') !!}
                        </div>
                        <div class="col-md-6">
                            {!! CoralsForm::checkbox('archived','TroubleTicket::attributes.troubleTicket.archived', false,1,['inline-form'=>true]) !!}
                        </div>
                    </div>
                @endif
                @if(user()->can('fullCreate', $troubleTicket))
                    <div class="row">
                        @if(!$troubleTicket->exists)
                            <div class="col-md-6 toggle-wrapper">
                                {!! CoralsForm::checkbox('auto_assignment','TroubleTicket::attributes.troubleTicket.auto_assignment', true, 1, ['inline-form'=>true]) !!}
                            </div>
                        @endif
                        <div class="col-md-6 toggle-wrapper">
                            {!! CoralsForm::select('assignee_id','TroubleTicket::attributes.troubleTicket.assignee_id', [], true, null,[
                                        'data'=>[
                                            'selected_value'=>\TroubleTickets::getFirstTTAssignee($troubleTicket)->id
                                         ]
                                ],'select2') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {!! CoralsForm::customFields($troubleTicket) !!}

        <div class="row">
            <div class="col-md-12">
                {!! CoralsForm::formButtons() !!}
            </div>
        </div>
        {!! CoralsForm::closeForm($troubleTicket) !!}
    @endcomponent
@endsection

@include('TroubleTicket::troubleTickets.partials.create_edit_js')
