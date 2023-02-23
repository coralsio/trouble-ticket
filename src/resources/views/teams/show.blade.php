@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tt_team_show') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            @component('components.box',['box_class'=>'box-success'])
                <div class="row">
                    <div class="col-md-4">
                        <label>@lang('TroubleTicket::attributes.team.name')</label>
                        <p>{!! $team->present('name') !!} </p>
                        <label>@lang('TroubleTicket::attributes.team.email')</label>
                        <p>{!! $team->present('email') !!}</p>
                        <label>@lang('TroubleTicket::attributes.team.slack')</label>
                        <p style="word-wrap: break-word">{!! $team->present('slack') !!} </p>
                    </div>
                    <div class="col-md-8">
                        <label>@lang('TroubleTicket::attributes.team.users')</label>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('User::attributes.user.full_name')</th>
                                    <th>@lang('User::attributes.user.email')</th>
                                    <th>@lang('User::attributes.user.roles')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($team->users as $assignment)
                                    <tr>
                                        <td>{!! $assignment->assignee->present('full_name') !!}</td>
                                        <td>{!! $assignment->assignee->present('email') !!}</td>
                                        <td>{!! $assignment->assignee->present('roles') !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
@endsection
