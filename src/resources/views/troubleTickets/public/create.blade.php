<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>{!! view()->shared('title_singular') !!}</h3>
            <hr/>
        </div>
    </div>
    {!! CoralsForm::openForm($troubleTicket) !!}
    <div class="row">
        <div class="col-md-4">
            {!! CoralsForm::text('title','TroubleTicket::attributes.troubleTicket.title', true) !!}
            {!! CoralsForm::email('public_owner[email]', 'TroubleTicket::attributes.troubleTicket.public_owner.email_public', true) !!}
            {!! CoralsForm::text('public_owner[name]','TroubleTicket::attributes.troubleTicket.public_owner.name_public', true) !!}
            {!! CoralsForm::select('priority','TroubleTicket::attributes.troubleTicket.priority', \ListOfValues::get('tt_priority'), true,null,[], 'select2') !!}
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
        <div class="col-md-8">
            {!! CoralsForm::textarea('description','TroubleTicket::attributes.troubleTicket.description', true, $troubleTicket->description,['class'=>'ckeditor-simple']) !!}

            @include('Media::attachments_section',[
                                     'hasForm'=> false,
                                      'object'=>$troubleTicket,
                                      'url'=> url($troubleTicket->getShowURL(). '/add-attachments')
                                 ])
            @guest
                <div class="form-group">
                    {!! NoCaptcha::display() !!}
                </div>
            @endguest
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! CoralsForm::formButtons('', [], ['show_cancel' => false]) !!}
        </div>
    </div>
    {!! CoralsForm::closeForm($troubleTicket) !!}
</div>
@include('TroubleTicket::troubleTickets.partials.create_edit_js')
