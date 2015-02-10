<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can any all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::group(['middleware' => 'auth.pulsar'], function() {
    // enter to application
    Route::get(config('pulsar.appName'), function () {
        return Redirect::to(config('pulsar.appName') . '/pulsar/dashboard');
    });

    // exit of application
    Route::get(config('pulsar.appName') . '/pulsar/logout', function () {
        Auth::logout();
        Session::flush();
        return Redirect::to(config('pulsar.appName'));
    });

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get(config('pulsar.appName') . '/pulsar/dashboard', ['as' => 'dashboard', 'uses' => 'Pulsar\Pulsar\Controllers\Dashboard@index']);

    /*
    |--------------------------------------------------------------------------
    | LANGUAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/languages/{page?}',                          ['as' => 'langs',               'uses' => 'Pulsar\Pulsar\Controllers\Langs@index']);
    Route::any(config('pulsar.appName') . '/pulsar/languages/json/data',                        ['as' => 'jsonDataLangs',       'uses' => 'Pulsar\Pulsar\Controllers\Langs@jsonData']);
    Route::get(config('pulsar.appName') . '/pulsar/languages/create/{page}',                    ['as' => 'createLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@create']);
    Route::post(config('pulsar.appName') . '/pulsar/languages/store/{page}',                    ['as' => 'storeLang',           'uses' => 'Pulsar\Pulsar\Controllers\Langs@store']);
    Route::get(config('pulsar.appName') . '/pulsar/languages/{id}/edit/{page}',                 ['as' => 'editLang',            'uses' => 'Pulsar\Pulsar\Controllers\Langs@edit']);
    Route::post(config('pulsar.appName') . '/pulsar/languages/update/{page}',                   ['as' => 'updateLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@update']);
    Route::get(config('pulsar.appName') . '/pulsar/languages/destroy/{id}',                     ['as' => 'destroyLang',         'uses' => 'Pulsar\Pulsar\Controllers\Langs@destroy']);
    Route::post(config('pulsar.appName') . '/pulsar/languages/destroy/select/elements',         ['as' => 'destroySelectLang',   'uses' => 'Pulsar\Pulsar\Controllers\Langs@destroySelect']);
    Route::post(config('pulsar.appName') . '/pulsar/languages/delete/image/language/{id}',      ['as' => 'deleteImageLang',     'uses' => 'Pulsar\Pulsar\Controllers\Langs@deleteImage']);

    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/actions/{page?}',                            ['as'=>'actions',               'uses'=>'Pulsar\Pulsar\Controllers\Actions@index']);
    Route::any(config('pulsar.appName') . '/pulsar/actions/json/data',                          ['as'=>'jsonDataActions',       'uses'=>'Pulsar\Pulsar\Controllers\Actions@jsonData']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/create/{page}',                      ['as'=>'createAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@create']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/store/{page}',                      ['as'=>'storeAction',           'uses'=>'Pulsar\Pulsar\Controllers\Actions@store']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/{id}/edit/{page}',                   ['as'=>'editAction',            'uses'=>'Pulsar\Pulsar\Controllers\Actions@edit']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/update/{page}',                     ['as'=>'updateAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@update']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/destroy/{id}',                       ['as'=>'destroyAction',         'uses'=>'Pulsar\Pulsar\Controllers\Actions@destroyRecord']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/destroy/select/elements',           ['as'=>'destroySelectAction',   'uses'=>'Pulsar\Pulsar\Controllers\Actions@destroyRecordsSelect']);

});

// LOGIN
Route::post(config('pulsar.appName') . '/pulsar/login',                                     ['as'=>'login',                     'uses'=>'Pulsar\Pulsar\Controllers\Login@login']);
Route::get(config('pulsar.appName') . '/pulsar/login',                                      ['as'=>'loginView',                 'uses'=>'Pulsar\Pulsar\Controllers\Login@loginView']);

// PASSWORD REMINDER
Route::post(config('pulsar.appName') . '/pulsar/password/remind',                           ['as'=>'postRemindPassword',        'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postRemind']);
Route::get(config('pulsar.appName') . '/pulsar/password/reset/{token}',                     ['as'=>'getResetPassword',          'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@getReset']);
Route::post(config('pulsar.appName') . '/pulsar/password/reset/{token}',                    ['as'=>'postResetPassword',         'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postReset']);