<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group([ 'middleware' => 'auth:api'], function() {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //route des applications 
    Route::get('/application', 'ApplicationController@index')->name('app_list');
    Route::put('/application/{id}', 'ApplicationController@update')->name('app_update');
    Route::get('/application/{id}', 'ApplicationController@show')->name('app_show');
    Route::get('/application/unique/{unique}', 'ApplicationController@showByUnique')->name('showByUnique');
    Route::post('/application', 'ApplicationController@create')->name('app_create');
    Route::get('/application/check/ifs/{id}', 'ApplicationController@checkIfs')->name('app_check_ifs');
    Route::get('/application/check/trello/{id}', 'ApplicationController@checkTrello')->name('app_check_trello');

    Route::post('/note', 'NoteController@create')->name('note_create');
    Route::get('/note', 'NoteController@index')->name('note_list');

    Route::post('/form/{note}', 'FormController@create')->name('form_create');
    Route::get('/form/{note}', 'FormController@index')->name('form_list');


    Route::get('/team/{app}', 'TeamController@index')->name('team_index');

    Route::get('/option/{name}', 'OptionController@find')->name('option_find');
    Route::post('/option', 'OptionController@create')->name('option_create');

    Route::post('/mobile', 'MobileController@create')->name('create');
    Route::get('/mobile', 'MobileController@index')->name('mobile_list');
    Route::get('/mobile/assigned/{app}', 'MobileController@assigned')->name('mobile_assigned');
    Route::post('/mobile/assigned/{app}', 'MobileController@assigne')->name('mobile_assigne');
    Route::post('/mobile/priorty/{app}', 'MobileController@priorty')->name('mobile_priorty');
    Route::get('/mobile/priorty/{app}', 'MobileController@priortyFind')->name('mobile_priorty_find');
    
    Route::get('/trello/boards/{app}', 'TrelloController@boards')->name('trello_boards');
    Route::get('/trello/lists/{app}', 'TrelloController@lists')->name('trello_lists');
    Route::get('/trello/membres/{app}', 'TrelloController@membres')->name('trello_membres');
    Route::get('/trello/labels/{app}', 'TrelloController@labels')->name('trello_labels');

    Route::get('/infusionsoft/membres/{app}', 'InfusionsoftController@membres')->name('infusionsoft_membres');
    Route::get('/infusionsoft/contacts/{app}', 'InfusionsoftController@contacts')->name('infusionsoft_contacts');
    Route::get('/infusionsoft/fetchContact/{app}', 'InfusionsoftController@fetchContact')->name('infusionsoft_fetchcontact');

    
});
