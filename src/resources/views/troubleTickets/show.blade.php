@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tt_troubleTicket_show') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            @component('components.box',['box_class'=>'box-info'])
                @include('TroubleTicket::troubleTickets.partials.tt_details',['isPublicView' => false])
            @endcomponent

            @can('partialUpdate',$troubleTicket)
                @component('components.box',['box_title'=>trans('Corals::labels.update_title', ['title' => $troubleTicket->code]),'box_class'=>'box-primary'])
                    <div class="row">
                        <div class="col-md-12">
                            @include('TroubleTicket::troubleTickets.partials.partial_edit')
                        </div>
                    </div>
                @endcomponent
            @endcan
        </div>
        <div class="col-md-8">
            @component('components.box',['box_class'=>'box-success'])
                @include('TroubleTicket::troubleTickets.partials.tt_description_section')
            @endcomponent
        </div>
    </div>
@endsection
