<?php

use Illuminate\Http\Request;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('login', 'PassportController@login');

Route::post('import', 'MyController@import')->name('import');

Route::group(['middleware'=>'auth:api'],function(){
    Route::get('user', 'PassportController@details');
    Route::get('user', 'UserController@index');
    Route::get('user/{user}', 'UserController@show');
    Route::post('user', 'UserController@store'); //same like register
    Route::put('user/{user}', 'UserController@update');
    Route::delete('user/{user}', 'UserController@destroy');
    Route::post('user/register', 'PassportController@register');
//    Route::get('user/{user}', 'UserController@exportData');

});

