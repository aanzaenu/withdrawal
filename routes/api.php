<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/inbox', 'InboxController@inbox')->name('inbox.post');
Route::get('/inbox/lastinbox/{identifier}', 'InboxController@lastinbox')->name('inbox.lastinbox');
Route::post('/inbox/saldo', 'InboxController@saldo')->name('inbox.saldo');
Route::get('/inbox/ceklist/{identifier}', 'InboxController@ceklist')->name('inbox.ceklist');
Route::get('/inbox/forcedelete/{id}', 'InboxController@forcedelete')->name('inbox.forcedelete');
