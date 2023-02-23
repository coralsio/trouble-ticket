@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tt_issue_type_show') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @component('components.box',['box_class'=>'box-success'])
        <div class="row">
            <div class="col-md-4">
                <label>@lang('TroubleTicket::attributes.issue_type.title')</label>
                <p>{!! $issueType->presentStripTags('title') !!} </p>
            </div>
            <div class="col-md-4">
                <label>@lang('TroubleTicket::attributes.issue_type.team')</label>
                <p>{!! $issueType->present('team') !!} </p>
            </div>
            <div class="col-md-4">
                <label>@lang('TroubleTicket::attributes.issue_type.categories')</label>
                <p>{!! $issueType->present('categories') !!} </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr/>
                <label>@lang('TroubleTicket::attributes.issue_type.description')</label>
                {!! $issueType->description !!}
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>@lang('TroubleTicket::labels.issue_type.solutions')</label>
                {!! $issueType->present('solutions') !!}
            </div>
        </div>
    @endcomponent
@endsection
