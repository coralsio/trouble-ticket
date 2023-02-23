@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tt_team_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    @component('components.box')
        {!! CoralsForm::openForm($team) !!}
        <div class="row">
            <div class="col-md-3">
                {!! CoralsForm::text('name','TroubleTicket::attributes.team.name', true) !!}
            </div>
            <div class="col-md-9">
                {!! CoralsForm::select("users[]",  'TroubleTicket::attributes.team.users' , [], true, $team->exists ? $team->users()->pluck('assignee_id')->toArray() : [],
                           ['multiple',
                           'class'=>'select2-ajax',
                           'data'=>[
                                  'model'=>\Corals\User\Models\User::class,
                                  'columns'=> json_encode(['name', 'email']),
                                  'selected'=>json_encode($team->exists ? $team->users()->pluck('assignee_id')->toArray() : []),
                                  'where'=>json_encode([])]
                                  ], 'select2') !!}

            </div>
        </div>

        <div class="row">
            <div class="col-md-3">

                <fieldset>
                    <legend>@lang('TroubleTicket::labels.team.notifications_channels')</legend>
                    {!! CoralsForm::text('properties[notifications_channels][email]','TroubleTicket::attributes.team.email') !!}
                    {!! CoralsForm::text('properties[notifications_channels][slack]','TroubleTicket::attributes.team.slack') !!}

                </fieldset>
            </div>

        </div>

        <div class="row">
            <div class="col-md-3">
                {!! CoralsForm::formButtons() !!}
            </div>
        </div>
        {!! CoralsForm::closeForm($team) !!}
    @endcomponent
@endsection