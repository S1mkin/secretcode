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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/secretcode/get', 'SecretcodesController@get');
Route::post('/secretcode/add', 'SecretcodesController@add');
Route::post('/secretcode/delete', 'SecretcodesController@delete');
Route::post('/secretcode/filter', 'SecretcodesController@filter');