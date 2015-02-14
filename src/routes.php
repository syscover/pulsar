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

// enter to application
Route::get(config('pulsar.appName'), function () { return Redirect::to(route('dashboard')); });

// LOGIN
Route::post(config('pulsar.appName') . '/pulsar/login',                                         ['as'=>'login',                 'uses'=>'Pulsar\Pulsar\Controllers\Login@login']);
Route::get(config('pulsar.appName') . '/pulsar/login',                                          ['as'=>'loginView',             'uses'=>'Pulsar\Pulsar\Controllers\Login@loginView']);

// LOGOUT
Route::get(config('pulsar.appName') . '/pulsar/logout',                                         ['as' => 'logout',              'uses' => 'Pulsar\Pulsar\Controllers\Login@logout']);

// PASSWORD REMINDER
Route::post(config('pulsar.appName') . '/pulsar/password/remind',                               ['as'=>'postRemindPassword',    'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postRemind']);
Route::get(config('pulsar.appName') . '/pulsar/password/reset/{token}',                         ['as'=>'getResetPassword',      'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@getReset']);
Route::post(config('pulsar.appName') . '/pulsar/password/reset/{token}',                        ['as'=>'postResetPassword',     'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postReset']);



Route::group(['middleware' => ['auth.pulsar','permission.pulsar']], function() {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get(config('pulsar.appName') . '/pulsar/dashboard',                                  ['as' => 'dashboard',           'uses' => 'Pulsar\Pulsar\Controllers\Dashboard@index',                  'resource' => 'admin',                  'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/actions/{page?}',                            ['as'=>'Action',                'uses'=>'Pulsar\Pulsar\Controllers\Actions@index',                      'resource' => 'admin-perm-actions',     'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/actions/json/data',                          ['as'=>'jsonDataAction',        'uses'=>'Pulsar\Pulsar\Controllers\Actions@jsonData',                   'resource' => 'admin-perm-actions',     'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/create/{page}',                      ['as'=>'createAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@createRecord',               'resource' => 'admin-perm-actions',     'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/store/{page}',                      ['as'=>'storeAction',           'uses'=>'Pulsar\Pulsar\Controllers\Actions@storeRecord',                'resource' => 'admin-perm-actions',     'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/{id}/edit/{page}',                   ['as'=>'editAction',            'uses'=>'Pulsar\Pulsar\Controllers\Actions@editRecord',                 'resource' => 'admin-perm-actions',     'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/actions/update/{page}',                      ['as'=>'updateAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@updateRecord',               'resource' => 'admin-perm-actions',     'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/destroy/{id?}',                      ['as'=>'destroyAction',         'uses'=>'Pulsar\Pulsar\Controllers\Actions@destroyRecord',              'resource' => 'admin-perm-actions',     'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/actions/destroy/select/elements',         ['as'=>'destroySelectAction',   'uses'=>'Pulsar\Pulsar\Controllers\Actions@destroyRecordsSelect',       'resource' => 'admin-perm-actions',     'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | RESOURCES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/resources/{page?}',                          ['as'=>'Resource',              'uses'=>'Pulsar\Pulsar\Controllers\Resources@index',                    'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/resources/json/data',                        ['as'=>'jsonDataResource',      'uses'=>'Pulsar\Pulsar\Controllers\Resources@jsonData',                 'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/create/{page}',                    ['as'=>'createResource',        'uses'=>'Pulsar\Pulsar\Controllers\Resources@createRecord',             'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/resources/store/{page}',                    ['as'=>'storeResource',         'uses'=>'Pulsar\Pulsar\Controllers\Resources@storeRecord',              'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/{id}/edit/{page}',                 ['as'=>'editResource',          'uses'=>'Pulsar\Pulsar\Controllers\Resources@editRecord',               'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/resources/update/{page}',                    ['as'=>'updateResource',        'uses'=>'Pulsar\Pulsar\Controllers\Resources@updateRecord',             'resource' => 'admin-perm-resource',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/destroy/{id?}',                    ['as'=>'destroyResource',       'uses'=>'Pulsar\Pulsar\Controllers\Resources@destroyRecord',            'resource' => 'admin-perm-resource',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/resources/destroy/select/elements',       ['as'=>'destroySelectResource', 'uses'=>'Pulsar\Pulsar\Controllers\Resources@destroyRecordsSelect',     'resource' => 'admin-perm-resource',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PROFILES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/profiles/{page?}',                           ['as'=>'Profile',               'uses'=>'Pulsar\Pulsar\Controllers\Profiles@index',                     'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/profiles/json/data',                         ['as'=>'jsonDataProfile',       'uses'=>'Pulsar\Pulsar\Controllers\Profiles@jsonData',                  'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/create/{page}',                     ['as'=>'createProfile',         'uses'=>'Pulsar\Pulsar\Controllers\Profiles@createRecord',              'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/profiles/store/{page}',                     ['as'=>'storeProfile',          'uses'=>'Pulsar\Pulsar\Controllers\Profiles@storeRecord',               'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/{id}/edit/{page}',                  ['as'=>'editProfile',           'uses'=>'Pulsar\Pulsar\Controllers\Profiles@editRecord',                'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/profiles/update/{page}',                     ['as'=>'updateProfile',         'uses'=>'Pulsar\Pulsar\Controllers\Profiles@updateRecord',              'resource' => 'admin-perm-profile',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/destroy/{id?}',                     ['as'=>'destroyProfile',        'uses'=>'Pulsar\Pulsar\Controllers\Profiles@destroyRecord',             'resource' => 'admin-perm-profile',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/profiles/destroy/select/elements',        ['as'=>'destroySelectProfile',  'uses'=>'Pulsar\Pulsar\Controllers\Profiles@destroyRecordsSelect',      'resource' => 'admin-perm-profile',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PACKAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/packages/{page?}',                           ['as'=>'Package',               'uses'=>'Pulsar\Pulsar\Controllers\Packages@index',                     'resource' => 'admin-packages',         'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/packages/json/data',                         ['as'=>'jsonDataPackage',       'uses'=>'Pulsar\Pulsar\Controllers\Packages@jsonData',                  'resource' => 'admin-packages',         'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/create/{page}',                     ['as'=>'createPackage',         'uses'=>'Pulsar\Pulsar\Controllers\Packages@createRecord',              'resource' => 'admin-packages',         'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/packages/store/{page}',                     ['as'=>'storePackage',          'uses'=>'Pulsar\Pulsar\Controllers\Packages@storeRecord',               'resource' => 'admin-packages',         'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/{id}/edit/{page}',                  ['as'=>'editPackage',           'uses'=>'Pulsar\Pulsar\Controllers\Packages@editRecord',                'resource' => 'admin-packages',         'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/packages/update/{page}',                     ['as'=>'updatePackage',         'uses'=>'Pulsar\Pulsar\Controllers\Packages@updateRecord',              'resource' => 'admin-packages',         'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/destroy/{id?}',                     ['as'=>'destroyPackage',        'uses'=>'Pulsar\Pulsar\Controllers\Packages@destroyRecord',             'resource' => 'admin-packages',         'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/packages/destroy/select/elements',        ['as'=>'destroySelectPackage',  'uses'=>'Pulsar\Pulsar\Controllers\Packages@destroyRecordsSelect',      'resource' => 'admin-packages',         'action' => 'delete']);


    /*
    |--------------------------------------------------------------------------
    | TAREAS CRON
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/{page?}',                           ['as'=>'CronJob',               'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@index',                     'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/run/{page}',                   ['as'=>'runCronJob',            'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@run',                       'resource' => 'admin-cron',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/json/data',                         ['as'=>'jsonDataCronJob',       'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@jsonData',                  'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/create/{page}',                     ['as'=>'createCronJob',         'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@create',                    'resource' => 'admin-cron',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/cronjobs/store/{page}',                     ['as'=>'storeCronJob',          'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@store',                     'resource' => 'admin-cron',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/edit/{page}',                  ['as'=>'editCronJob',           'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@edit',                      'resource' => 'admin-cron',             'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/cronjobs/update/{page}',                     ['as'=>'updateCronJob',         'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@update',                    'resource' => 'admin-cron',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/destroy/{id}',                      ['as'=>'destroyCronJob',        'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@destroy',                   'resource' => 'admin-cron',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/cronjobs/destroy/select/elements',          ['as'=>'destroySelectCronJob',  'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@destroySelect',             'resource' => 'admin-cron',             'action' => 'delete']);






    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/permissions/{profile}/{offsetProfile?}/{offset?}',       ['as'=>'Permission',               'uses'=>'Pulsar\Pulsar\Controllers\Permissions@index',          'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/permissions/json/data/profile/{profile}',                ['as'=>'jsonDataPermission',       'uses'=>'Pulsar\Pulsar\Controllers\Permissions@jsonData',       'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/create/{num}/{num1}/{any}',            ['as'=>'jsonCreatePermission',      'uses'=>'Pulsar\Pulsar\Controllers\Permissions@jsonCreate',     'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/destroy/{num}/{num1}/{any}',           ['as'=>'jsonDestroyPermission',     'uses'=>'Pulsar\Pulsar\Controllers\Permissions@jsonDestroy',    'resource' => 'admin-perm-profile',    'action' => 'access']);


    /*
    |--------------------------------------------------------------------------
    | LANGUAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/langs/{page?}',                              ['as' => 'Lang',                'uses' => 'Pulsar\Pulsar\Controllers\Langs@index',                      'resource' => 'admin-lang',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/langs/json/data',                            ['as' => 'jsonDataLang',        'uses' => 'Pulsar\Pulsar\Controllers\Langs@jsonData',                   'resource' => 'admin-lang',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/create/{page}',                        ['as' => 'createLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@createRecord',               'resource' => 'admin-lang',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/store/{page}',                        ['as' => 'storeLang',           'uses' => 'Pulsar\Pulsar\Controllers\Langs@storeRecord',                'resource' => 'admin-lang',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/{id}/edit/{page}',                     ['as' => 'editLang',            'uses' => 'Pulsar\Pulsar\Controllers\Langs@editRecord',                 'resource' => 'admin-lang',             'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/langs/update/{page}',                        ['as' => 'updateLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@updateRecord',               'resource' => 'admin-lang',             'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/destroy/{id?}',                        ['as' => 'destroyLang',         'uses' => 'Pulsar\Pulsar\Controllers\Langs@destroyRecord',              'resource' => 'admin-lang',             'action' => 'edit']);
    Route::delete(config('pulsar.appName') . '/pulsar/langs/destroy/select/elements',           ['as' => 'destroySelectLang',   'uses' => 'Pulsar\Pulsar\Controllers\Langs@destroyRecordsSelect',       'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/delete/image/lang/{id}',              ['as' => 'deleteImageLang',     'uses' => 'Pulsar\Pulsar\Controllers\Langs@ajaxDeleteImage',            'resource' => 'admin-lang',             'action' => 'delete']);


    /*
    |--------------------------------------------------------------------------
    | COUNTRIES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/countries/{page?}',                           ['as'=>'Country',             'uses'=>'Pulsar\Pulsar\Controllers\Countries@index']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/json/data',                        ['as'=>'jsonDataCountry',     'uses'=>'Pulsar\Pulsar\Controllers\Countries@jsonData']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/create/{page}/{lang}/{country?}',   ['as'=>'createCountry',         'uses'=>'Pulsar\Pulsar\Controllers\Countries@create']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/store/{page}',                      array('as'=>'storeCountry',          'uses'=>'Pulsar\Pulsar\Controllers\Countries@store'));
    Route::get(config('pulsar.appName') . '/pulsar/countries/{id}/edit/{lang}/{page}',            array('as'=>'editCountry',           'uses'=>'Pulsar\Pulsar\Controllers\Countries@edit'));
    Route::post(config('pulsar.appName') . '/pulsar/countries/update/{page}',                     array('as'=>'updateCountry',         'uses'=>'Pulsar\Pulsar\Controllers\Countries@update'));
    Route::get(config('pulsar.appName') . '/pulsar/countries/destroy/{id}',                       array('as'=>'destroyCountry',        'uses'=>'Pulsar\Pulsar\Controllers\Countries@destroy'));
    Route::get(config('pulsar.appName') . '/pulsar/countries/destroy/lang/{id}/{lang}/{page}',    array('as'=>'destroyCountryLang',    'uses'=>'Pulsar\Pulsar\Controllers\Countries@destroyLang'));
    Route::post(config('pulsar.appName') . '/pulsar/countries/destroy/select/elements',           array('as'=>'destroySelectCountry',  'uses'=>'Pulsar\Pulsar\Controllers\Countries@destroySelect'));
    Route::post(config('pulsar.appName') . '/pulsar/countries/json/get_pais/{country?}',          array('as'=>'jsonGetCountry',        'uses'=>'Pulsar\Pulsar\Controllers\Countries@jsonGetCountry'));

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 1
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/areasterritoriales1/{country}/{page?}', array('as'=>'areasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@index'));
    Route::any(config('pulsar.appName').'/pulsar/areasterritoriales1/json/data/pais/{country}', array('as'=>'jsonDataAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@jsonData'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales1/create/{country}/{page}', array('as'=>'createAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@create'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales1/store/{country}/{page}', array('as'=>'storeAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@store'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales1/{id}/edit/{page}', array('as'=>'editAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@edit'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales1/update/{country}/{page}', array('as'=>'updateAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@update'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales1/destroy/{country}/{id}', array('as'=>'destroyAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@destroy'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales1/destroy/select/elements/{country}', array('as'=>'destroySelectAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@destroySelect'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales1/json/get_areas_territoriales_1_from_pais/{country?}', array('as'=>'jsonGetAreasTerritoriales1FromPais', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@jsonGetAreasTerritoriales1FromPais'));

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 2
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/areasterritoriales2/{country}/{page?}', array('as'=>'areasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@index'));
    Route::any(config('pulsar.appName').'/pulsar/areasterritoriales2/json/data/pais/{country}', array('as'=>'jsonDataAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@jsonData'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales2/create/{country}/{page}', array('as'=>'createAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@create'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales2/store/{country}/{page}', array('as'=>'storeAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@store'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales2/{id}/edit/{page}', array('as'=>'editAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@edit'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales2/update/{country}/{page}', array('as'=>'updateAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@update'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales2/destroy/{country}/{id}', array('as'=>'destroyAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@destroy'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales2/destroy/select/elements/{country}', array('as'=>'destroySelectAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@destroySelect'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales2/json/get_areas_territoriales_2_from_area_territorial_1/{id}', array('as'=>'jsonAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@jsonGetAreasTerritoriales2FromAreaTerritorial1'));

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 3
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/areasterritoriales3/{country}/{page?}', array('as'=>'areasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@index'));
    Route::any(config('pulsar.appName').'/pulsar/areasterritoriales3/json/data/pais/{country}', array('as'=>'jsonDataAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@jsonData'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales3/create/{country}/{page}', array('as'=>'createAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@create'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales3/store/{country}/{page}', array('as'=>'storeAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@store'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales3/{id}/edit/{page}', array('as'=>'editAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@edit'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales3/update/{country}/{page}', array('as'=>'updateAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@update'));
    Route::get(config('pulsar.appName').'/pulsar/areasterritoriales3/destroy/{country}/{id}', array('as'=>'destroyAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@destroy'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales3/destroy/select/elements/{country}', array('as'=>'destroySelectAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@destroySelect'));
    Route::post(config('pulsar.appName').'/pulsar/areasterritoriales3/json/get_areas_territoriales_3_from_area_territorial_2/{id}', array('as'=>'jsonAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@jsonGetAreasTerritoriales3FromAreaTerritorial2'));

    



    /*
    |--------------------------------------------------------------------------
    | USUARIO
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/users/{page?}',                                  array('as'=>'User', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@index'));
    Route::any(config('pulsar.appName') . '/pulsar/users/json/data',                  array('as'=>'jsonDataUsuarios', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@jsonData'));
    Route::get(config('pulsar.appName') . '/pulsar/users/create/{page}',              array('as'=>'createUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@create'));
    Route::post(config('pulsar.appName') . '/pulsar/users/store/{page}',              array('as'=>'storeUser', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@store'));
    Route::get(config('pulsar.appName') . '/pulsar/users/{id}/edit/{page}',           array('as'=>'editUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@edit'));
    Route::post(config('pulsar.appName') . '/pulsar/users/update/{page}',             array('as'=>'updateUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@update'));
    Route::get(config('pulsar.appName') . '/pulsar/users/destroy/{id}',               array('as'=>'destroyUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@destroy'));
    Route::post(config('pulsar.appName') . '/pulsar/users/destroy/select/elements',   array('as'=>'destroySelectUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@destroySelect'));




    /*
    |--------------------------------------------------------------------------
    | DIRECCIONES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/direcciones/json/data/{resource}/{object}',         array('as'=>'jsonDataDirecciones',  'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@jsonData'));
    Route::get(config('pulsar.appName') . '/pulsar/direcciones/create/{resource}/{object}',            array('as'=>'createDireccion',      'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@create'));
    Route::post(config('pulsar.appName') . '/pulsar/direcciones/store',                                array('as'=>'storeDireccion',       'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@store'));
    Route::get(config('pulsar.appName') . '/pulsar/direcciones/{id}/edit/{page}',                      array('as'=>'editDireccion',        'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@edit'));
    Route::post(config('pulsar.appName') . '/pulsar/direcciones/update/{page}',                        array('as'=>'updateDireccion',      'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@update'));

    /*
    |--------------------------------------------------------------------------
    | GOOGLE SERVICES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/google/services',                                     array('as'=>'googleServices', 'uses'=>'Pulsar\Pulsar\Controllers\GoogleServices@index'));
    Route::post(config('pulsar.appName').'/pulsar/google/services/update',                             array('as'=>'updateGoogleServices', 'uses'=>'Pulsar\Pulsar\Controllers\GoogleServices@update'));

    /*
    |--------------------------------------------------------------------------
    | CONTENT BUILDER
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/{theme}/edit/{input}',               array('as'=>'contentbuilderEdit', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@index'));
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/saveimage',                   array('as'=>'contentbuilderSaveImage', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@saveImage'));
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/blocks/{theme}',              array('as'=>'contentbuilderBlocks', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@getBlocks'));


});