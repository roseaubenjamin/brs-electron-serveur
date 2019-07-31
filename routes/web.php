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

Route::get('/', 'ViewController@home')->name('home');
Route::get('/public', 'ViewController@publicPage')->name('public');

//route des la page authentification
Auth::routes();

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

Route::get('/home', 'HomeController@index')->name('home');
