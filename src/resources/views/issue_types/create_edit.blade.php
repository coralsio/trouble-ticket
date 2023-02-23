@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tt_issue_type_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    @component('components.box')
        {!! CoralsForm::openForm($issueType) !!}
        <div class="row">
            <div class="col-md-3">
                {!! CoralsForm::text('title','TroubleTicket::attributes.issue_type.title', true) !!}
            </div>

            <div class="col-md-3">
                {!! CoralsForm::select('team_id','TroubleTicket::attributes.issue_type.team', \TroubleTickets::getTeamsList(), true, null, [], 'select2') !!}
            </div>

            <div class="col-md-3">
                {!! CoralsForm::select('categories[]','TroubleTicket::attributes.issue_type.categories', \Category::getCategoriesByParent('tt-categories'), true, null,['multiple'=>true], 'select2') !!}

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                {!! CoralsForm::textarea('description','TroubleTicket::attributes.issue_type.description', true, $issueType->description,['class'=>'ckeditor-simple']) !!}
            </div>
        </div>

        <div class="row" style="margin-bottom: 5px">
            <div class="col-md-5">
                @include('TroubleTicket::issue_types.partials.solutions')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                {!! CoralsForm::formButtons() !!}
            </div>
        </div>
        {!! CoralsForm::closeForm($issueType) !!}
    @endcomponent
@endsection
