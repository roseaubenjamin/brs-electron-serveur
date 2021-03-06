<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//URL DE REDIRECTION AUTHENTIFICATION
Route::get('/app/trello/auth/{id}', 'ApplicationController@trelloauth')->name('app_trello_auth');
Route::get('/app/ifs/auth/{id}', 'ApplicationController@ifsauth')->name('app_ifs_auth');


Route::get('/', 'ViewController@home')->name('home');
Route::get('/public', 'PublicController@index')->name('public');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/mobile/{id}/{action?}', 'ViewController@mobile')->name('mobile');
Route::get('/read/{unique}', 'ViewController@read')->name('read');
Route::get('/team/{unique}', 'TeamController@create')->name('teamCreate');
Route::get('/audio/{unique}', 'NoteController@audio')->name('audio');
Route::get('/login/url/{email}/{password}', 'Auth\LoginController@authenticatedByUrl')->name('auth_by_url');

//route des la page authentification
Auth::routes();

Route::get('/home', function ($any) {
	return redirect('/') ; 
});


//@todo : ici on doit encore faire le filtre de quelle URL retourne a la 404
//et quelle url devrait redireger vers home 
Route::get('/{any}', function ($any) {
	if ( Auth::check() ) {
		return View('home');
	}else{
		return View('404');
	}
})->where('any', '.*');


Auth::routes();

