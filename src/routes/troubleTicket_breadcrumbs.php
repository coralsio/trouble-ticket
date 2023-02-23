<?php

use Corals\Foundation\Facades\Breadcrumb\Breadcrumbs;

Breadcrumbs::register('tt_troubleTickets', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('TroubleTicket::module.troubleTicket.title'),
        url(config('trouble_ticket.models.troubleTicket.resource_url')));
});

Breadcrumbs::register('tt_troubleTicket_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('tt_troubleTickets');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('tt_troubleTicket_show', function ($breadcrumbs) {
    $breadcrumbs->parent('tt_troubleTickets');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//teams

Breadcrumbs::register('tt_teams', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('TroubleTicket::module.team.title'),
        url(config('trouble_ticket.models.team.resource_url')));
});

Breadcrumbs::register('tt_team_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('tt_teams');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('tt_team_show', function ($breadcrumbs) {
    $breadcrumbs->parent('tt_teams');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//issue-types

Breadcrumbs::register('tt_issue_types', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('TroubleTicket::module.team.title'),
        url(config('trouble_ticket.models.team.resource_url')));
});

Breadcrumbs::register('tt_issue_type_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('tt_issue_types');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('tt_issue_type_show', function ($breadcrumbs) {
    $breadcrumbs->parent('tt_issue_types');
    $breadcrumbs->push(view()->shared('title_singular'));
});
