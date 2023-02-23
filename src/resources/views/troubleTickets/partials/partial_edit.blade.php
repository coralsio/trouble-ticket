{!! CoralsForm::openForm($troubleTicket,['url'=>$troubleTicket->getShowURL().'/partial-update']) !!}

{!! CoralsForm::select('status','TroubleTicket::attributes.troubleTicket.status', \ListOfValues::get('tt_status'), true,$troubleTicket->exists?$troubleTicket->status:'new',[], 'select2') !!}

{!! CoralsForm::select('priority','TroubleTicket::attributes.troubleTicket.priority', \ListOfValues::get('tt_priority'), true,null,[], 'select2') !!}

{!! CoralsForm::select('category_id','TroubleTicket::attributes.troubleTicket.category', \Category::getCategoriesByParent('tt-categories'), true, null,[
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

{!! CoralsForm::select('assignee_id','TroubleTicket::attributes.troubleTicket.assignee_id', [], true, null,[
                           'data'=>[
                               'selected_value'=>\TroubleTickets::getFirstTTAssignee($troubleTicket)->id
                            ]
                   ],'select2') !!}

{!! CoralsForm::formButtons('Corals::labels.update',[],['show_cancel' => false]) !!}

{!! CoralsForm::closeForm($troubleTicket) !!}

@include('TroubleTicket::troubleTickets.partials.create_edit_js')
