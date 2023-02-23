<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'trouble-ticket'], function () {
    Route::post('public/create', 'PublicTroubleTicketsController@doCreate');
    Route::get('public/create', 'PublicTroubleTicketsController@create');

    Route::get('tt/{trouble_ticket}', 'PublicTroubleTicketsController@show')
        ->name('publicTroubleTicket')
        ->middleware(\Illuminate\Routing\Middleware\ValidateSignature::class);

    Route::group(['prefix' => 'trouble-tickets'], function () {
        Route::post('{trouble_ticket}/create-comment', 'CommentsController@createTTComment');
        Route::get('{trouble_ticket}/comments', 'CommentsController@ttComments');
        Route::get('update-status-modal/{trouble_ticket?}', 'TroubleTicketsController@updateStatusModal');
        Route::post('{trouble_ticket}/do-update-status', 'TroubleTicketsController@doUpdateStatus');
        Route::post('{trouble_ticket}/add-attachments', 'TroubleTicketsController@addAttachments');
        Route::post('{trouble_ticket}/{status}', 'TroubleTicketsController@markAs');
        Route::post('bulk-action', 'TroubleTicketsController@bulkAction');
    });


    Route::group(['prefix' => 'issue-types'], function () {
        Route::get('get-solution-form', 'IssueTypesController@getSolutionForm');
        Route::get('get-by-category', 'IssueTypesController@getByCategory');
    });


    Route::get('teams/get-by-issue-type', 'TeamsController@getByIssueType');
    Route::get('teams/get-assignees-list', 'TeamsController@getAssigneesList');

    Route::put('trouble-tickets/{trouble_ticket}/partial-update', 'TroubleTicketsController@partialUpdate');

    Route::resource('trouble-tickets', 'TroubleTicketsController');
    Route::resource('teams', 'TeamsController');
    Route::resource('issue-types', 'IssueTypesController');
});
