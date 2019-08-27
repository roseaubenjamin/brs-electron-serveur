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
    Route::get('/application/cards/trello/{id}', 'ApplicationController@cardBoardTrelloUrl')->name('app_check_trello');
    Route::delete('/application/{app}', 'ApplicationController@deleteApplication')->name('app_delete_application');

    Route::post('/note', 'NoteController@create')->name('note_create');
    Route::post('/note/convert', 'NoteController@createConvert')->name('note_create_convert');
    Route::post('/note/{note}', 'NoteController@update')->name('note_update');
    Route::get('/note', 'NoteController@find')->name('note_find');
    Route::get('/notes/{app}', 'NoteController@index')->name('note_list');
    Route::get('/note/attache/{unique}/{nativeId}/{attache}', 'NoteController@attache')->name('note_attache');

    Route::post('/form/{note}', 'FormController@create')->name('form_create');
    Route::get('/form/{note}', 'FormController@index')->name('form_list');

    
    Route::get('/team/{app}', 'TeamController@index')->name('team_index');
    Route::get('/team/info/{app}', 'TeamController@info')->name('team_info');

    Route::get('/option/{name}', 'OptionController@find')->name('option_find');
    Route::post('/option', 'OptionController@create')->name('option_create');
    
    Route::post('/mobile', 'MobileController@create')->name('create');
    Route::get('/mobile', 'MobileController@index')->name('mobile_list');
    Route::get('/mobile/unique/{unique}', 'MobileController@mobileUnique')->name('mobile_unique');
    Route::get('/mobile/{app}', 'MobileController@find')->name('mobile_find');
    Route::delete('/mobile/{app}', 'MobileController@deleteMobile')->name('mobile_deassigned');
    Route::get('/mobile/assigned/{app}', 'MobileController@assigned')->name('mobile_assigned');
    Route::post('/mobile/assigned/{app}', 'MobileController@assigne')->name('mobile_assigne');
    Route::delete('/mobile/assigned/{app}', 'MobileController@deassigned')->name('mobile_deassigned');
    Route::post('/mobile/priorty/{app}', 'MobileController@priorty')->name('mobile_priorty');
    Route::get('/mobile/priorty/{app}', 'MobileController@priortyFind')->name('mobile_priorty_find');
    Route::delete('/mobile/priorty/{app}', 'MobileController@priortyDelete')->name('mobile_priorty_delete');
    Route::delete('/mobile/option/{app}', 'MobileController@deleteOption')->name('note_deloption');
    Route::post('/mobile/vocal/{app}', 'MobileController@createVocal')->name('mobile_create_vocal');
    
    Route::get('/trello/boards/{app}', 'TrelloController@boards')->name('trello_boards');
    Route::get('/trello/lists/{app}', 'TrelloController@lists')->name('trello_lists');
    Route::get('/trello/membres/{app}', 'TrelloController@membres')->name('trello_membres');
    Route::get('/trello/labels/{app}', 'TrelloController@labels')->name('trello_labels');
    Route::get('/trello/card/{app}/{card}', 'TrelloController@card')->name('trello_card');
    Route::delete('/trello/card/{app}/{card}', 'TrelloController@deletecard')->name('trello_card_delete');

    Route::get('/infusionsoft/check/membres', 'InfusionsoftController@checkMembre')->name('infusionsoft_checkMembre');
    Route::get('/infusionsoft/membres/{app}', 'InfusionsoftController@membres')->name('infusionsoft_membres');
    Route::get('/infusionsoft/contacts/{app}', 'InfusionsoftController@contacts')->name('infusionsoft_contacts');
    Route::get('/infusionsoft/fetchContact/{app}', 'InfusionsoftController@fetchContact')->name('infusionsoft_fetchcontact');
    Route::get('/infusionsoft/notes/{app}/{id}', 'InfusionsoftController@notes')->name('infusionsoft_notes');
    Route::get('/infusionsoft/tasks/{app}/{id}', 'InfusionsoftController@tasks')->name('infusionsoft_tasks');

    Route::get('/infusionsoft/note/{app}/{id}', 'InfusionsoftController@note')->name('infusionsoft_note');
    Route::get('/infusionsoft/task/{app}/{id}', 'InfusionsoftController@task')->name('infusionsoft_task');

    
});
