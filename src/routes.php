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

// Enter to application
Route::get(config('pulsar.appName'), function () { return Redirect::to(config('pulsar.appName') . '/pulsar/dashboard'); });

Route::group(['middleware' => ['auth.pulsar','permission.pulsar']], function() {

    // exit of application
    Route::get(config('pulsar.appName') . '/pulsar/logout', ['resource' => 'admin-pass-actions', 'action' => 'access', function () {
        Auth::logout();
        Session::flush();
        return Redirect::to(config('pulsar.appName'));
    }]);

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get(config('pulsar.appName') . '/pulsar/dashboard', ['as' => 'dashboard', 'uses' => 'Pulsar\Pulsar\Controllers\Dashboard@index', 'resource' => 'admin', 'action' => 'access']);

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
    Route::any(config('pulsar.appName') . '/pulsar/actions/{page?}',                            ['as'=>'actions',               'uses'=>'Pulsar\Pulsar\Controllers\Actions@index',                      'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/actions/json/data',                          ['as'=>'jsonDataActions',       'uses'=>'Pulsar\Pulsar\Controllers\Actions@jsonData',                   'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/create/{page}',                      ['as'=>'createAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@createRecord',               'resource' => 'admin-pass-actions',     'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/store/{page}',                      ['as'=>'storeAction',           'uses'=>'Pulsar\Pulsar\Controllers\Actions@store',                      'resource' => 'admin-pass-actions',     'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/{id}/edit/{page}',                   ['as'=>'editAction',            'uses'=>'Pulsar\Pulsar\Controllers\Actions@editRecord',                 'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/update/{page}',                     ['as'=>'updateAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@update',                     'resource' => 'admin-pass-actions',     'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/destroy/{id}',                       ['as'=>'destroyAction',         'uses'=>'Pulsar\Pulsar\Controllers\Actions@destroyRecord',              'resource' => 'admin-pass-actions',     'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/destroy/select/elements',           ['as'=>'destroySelectAction',   'uses'=>'Pulsar\Pulsar\Controllers\Actions@destroyRecordsSelect',       'resource' => 'admin-pass-actions',     'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | RESOURCES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/resources/{page?}',                          ['as'=>'resources',             'uses'=>'Pulsar\Pulsar\Controllers\Resources@index',                    'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/resources/json/data',                        ['as'=>'jsonDataResources',     'uses'=>'Pulsar\Pulsar\Controllers\Resources@jsonData',                 'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/create/{page}',                    ['as'=>'createResource',        'uses'=>'Pulsar\Pulsar\Controllers\Resources@create',                   'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/resources/store/{page}',                    ['as'=>'storeResource',         'uses'=>'Pulsar\Pulsar\Controllers\Resources@store',                    'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/{id}/edit/{page}',                 ['as'=>'editResource',          'uses'=>'Pulsar\Pulsar\Controllers\Resources@edit',                     'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/resources/update/{page}',                   ['as'=>'updateResource',        'uses'=>'Pulsar\Pulsar\Controllers\Resources@update',                   'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/destroy/{id}',                     ['as'=>'destroyResource',       'uses'=>'Pulsar\Pulsar\Controllers\Resources@destroy',                  'resource' => 'admin-pass-actions',     'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/resources/destroy/select/elements',         ['as'=>'destroySelectResource', 'uses'=>'Pulsar\Pulsar\Controllers\Resources@destroySelect',            'resource' => 'admin-pass-actions',     'action' => 'access']);


});

// LOGIN
Route::post(config('pulsar.appName') . '/pulsar/login',                                     ['as'=>'login',                     'uses'=>'Pulsar\Pulsar\Controllers\Login@login']);
Route::get(config('pulsar.appName') . '/pulsar/login',                                      ['as'=>'loginView',                 'uses'=>'Pulsar\Pulsar\Controllers\Login@loginView']);

// PASSWORD REMINDER
Route::post(config('pulsar.appName') . '/pulsar/password/remind',                           ['as'=>'postRemindPassword',        'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postRemind']);
Route::get(config('pulsar.appName') . '/pulsar/password/reset/{token}',                     ['as'=>'getResetPassword',          'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@getReset']);
Route::post(config('pulsar.appName') . '/pulsar/password/reset/{token}',                    ['as'=>'postResetPassword',         'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postReset']);