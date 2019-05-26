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

Route::get('/version', function() {
    return [
        'version'     => config('epcc.versionNumber'),
        'versionName' => config('epcc.versionName'),
        'releaseDate' => config('epcc.releaseDate')->format('F Y')
//            Use this once Laravel allows Carbon 2: 'releaseDate' => config('epcc.releaseDate')->isoFormat('MMMM G')
    ];
});

Route::prefix('creator')->group(function () {
    Route::get('/', 'HighLevelCreatorController@index');
    Route::get('/validate', 'HighLevelCreatorController@validateCharacter');
});
