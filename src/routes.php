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
    Route::any(config('pulsar.appName') . '/pulsar/actions/{offset?}',                          ['as'=>'Action',                'uses'=>'Pulsar\Pulsar\Controllers\Actions@index',                      'resource' => 'admin-perm-actions',     'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/actions/json/data',                          ['as'=>'jsonDataAction',        'uses'=>'Pulsar\Pulsar\Controllers\Actions@jsonData',                   'resource' => 'admin-perm-actions',     'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/create/{offset}',                    ['as'=>'createAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@createRecord',               'resource' => 'admin-perm-actions',     'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/store/{offset}',                    ['as'=>'storeAction',           'uses'=>'Pulsar\Pulsar\Controllers\Actions@storeRecord',                'resource' => 'admin-perm-actions',     'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/{id}/edit/{offset}',                 ['as'=>'editAction',            'uses'=>'Pulsar\Pulsar\Controllers\Actions@editRecord',                 'resource' => 'admin-perm-actions',     'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/actions/update/{id}/{offset}',               ['as'=>'updateAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@updateRecord',               'resource' => 'admin-perm-actions',     'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/delete/{id}/{offset}',               ['as'=>'deleteAction',          'uses'=>'Pulsar\Pulsar\Controllers\Actions@deleteRecord',               'resource' => 'admin-perm-actions',     'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/actions/delete/select/records',           ['as'=>'deleteSelectAction',    'uses'=>'Pulsar\Pulsar\Controllers\Actions@deleteRecordsSelect',        'resource' => 'admin-perm-actions',     'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | RESOURCES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/resources/{offset?}',                        ['as'=>'Resource',              'uses'=>'Pulsar\Pulsar\Controllers\Resources@index',                    'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/resources/json/data',                        ['as'=>'jsonDataResource',      'uses'=>'Pulsar\Pulsar\Controllers\Resources@jsonData',                 'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/create/{offset}',                  ['as'=>'createResource',        'uses'=>'Pulsar\Pulsar\Controllers\Resources@createRecord',             'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/resources/store/{offset}',                  ['as'=>'storeResource',         'uses'=>'Pulsar\Pulsar\Controllers\Resources@storeRecord',              'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/{id}/edit/{offset}',               ['as'=>'editResource',          'uses'=>'Pulsar\Pulsar\Controllers\Resources@editRecord',               'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/resources/update/{id}/{offset}',             ['as'=>'updateResource',        'uses'=>'Pulsar\Pulsar\Controllers\Resources@updateRecord',             'resource' => 'admin-perm-resource',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/delete/{id}/{offset}',             ['as'=>'deleteResource',        'uses'=>'Pulsar\Pulsar\Controllers\Resources@deleteRecord',             'resource' => 'admin-perm-resource',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/resources/delete/select/records',         ['as'=>'deleteSelectResource',  'uses'=>'Pulsar\Pulsar\Controllers\Resources@deleteRecordsSelect',      'resource' => 'admin-perm-resource',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PROFILES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/profiles/{offset?}',                         ['as'=>'Profile',               'uses'=>'Pulsar\Pulsar\Controllers\Profiles@index',                     'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/profiles/json/data',                         ['as'=>'jsonDataProfile',       'uses'=>'Pulsar\Pulsar\Controllers\Profiles@jsonData',                  'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/create/{offset}',                   ['as'=>'createProfile',         'uses'=>'Pulsar\Pulsar\Controllers\Profiles@createRecord',              'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/profiles/store/{offset}',                   ['as'=>'storeProfile',          'uses'=>'Pulsar\Pulsar\Controllers\Profiles@storeRecord',               'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/{id}/edit/{offset}',                ['as'=>'editProfile',           'uses'=>'Pulsar\Pulsar\Controllers\Profiles@editRecord',                'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/profiles/update/{id}/{offset}',              ['as'=>'updateProfile',         'uses'=>'Pulsar\Pulsar\Controllers\Profiles@updateRecord',              'resource' => 'admin-perm-profile',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/delete/{id}/{offset}',              ['as'=>'deleteProfile',         'uses'=>'Pulsar\Pulsar\Controllers\Profiles@deleteRecord',              'resource' => 'admin-perm-profile',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/profiles/delete/select/records',          ['as'=>'deleteSelectProfile',   'uses'=>'Pulsar\Pulsar\Controllers\Profiles@deleteRecordsSelect',       'resource' => 'admin-perm-profile',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PACKAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/packages/{offset?}',                         ['as'=>'Package',               'uses'=>'Pulsar\Pulsar\Controllers\Packages@index',                     'resource' => 'admin-packages',         'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/packages/json/data',                         ['as'=>'jsonDataPackage',       'uses'=>'Pulsar\Pulsar\Controllers\Packages@jsonData',                  'resource' => 'admin-packages',         'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/create/{offset}',                   ['as'=>'createPackage',         'uses'=>'Pulsar\Pulsar\Controllers\Packages@createRecord',              'resource' => 'admin-packages',         'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/packages/store/{offset}',                   ['as'=>'storePackage',          'uses'=>'Pulsar\Pulsar\Controllers\Packages@storeRecord',               'resource' => 'admin-packages',         'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/{id}/edit/{offset}',                ['as'=>'editPackage',           'uses'=>'Pulsar\Pulsar\Controllers\Packages@editRecord',                'resource' => 'admin-packages',         'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/packages/update/{id}/{offset}',              ['as'=>'updatePackage',         'uses'=>'Pulsar\Pulsar\Controllers\Packages@updateRecord',              'resource' => 'admin-packages',         'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/delete/{id}/{offset}',              ['as'=>'deletePackage',         'uses'=>'Pulsar\Pulsar\Controllers\Packages@deleteRecord',              'resource' => 'admin-packages',         'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/packages/delete/select/records',          ['as'=>'deleteSelectPackage',   'uses'=>'Pulsar\Pulsar\Controllers\Packages@deleteRecordsSelect',       'resource' => 'admin-packages',         'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/permissions/{offset}/{profile}/{offsetProfile?}',        ['as'=>'Permission',                'uses'=>'Pulsar\Pulsar\Controllers\Permissions@index',          'resource' => 'admin-perm-perm',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/permissions/json/data/profile/{profile}',                ['as'=>'jsonDataPermission',        'uses'=>'Pulsar\Pulsar\Controllers\Permissions@jsonData',       'resource' => 'admin-perm-perm',    'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/create/{num}/{num1}/{any}',            ['as'=>'jsonCreatePermission',      'uses'=>'Pulsar\Pulsar\Controllers\Permissions@jsonCreate',     'resource' => 'admin-perm-perm',    'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/delete/{num}/{num1}/{any}',            ['as'=>'jsonDestroyPermission',     'uses'=>'Pulsar\Pulsar\Controllers\Permissions@jsonDestroy',    'resource' => 'admin-perm-perm',    'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | CRON JOBS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/{offset?}',                         ['as'=>'CronJob',               'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@index',                     'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/run/{offset}',                 ['as'=>'runCronJob',            'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@run',                       'resource' => 'admin-cron',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/json/data',                         ['as'=>'jsonDataCronJob',       'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@jsonData',                  'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/create/{offset}',                   ['as'=>'createCronJob',         'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@createRecord',              'resource' => 'admin-cron',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/cronjobs/store/{offset}',                   ['as'=>'storeCronJob',          'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@storeRecord',               'resource' => 'admin-cron',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/edit/{offset}',                ['as'=>'editCronJob',           'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@editRecord',                'resource' => 'admin-cron',             'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/cronjobs/update/{id}/{offset}',              ['as'=>'updateCronJob',         'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@updateRecord',              'resource' => 'admin-cron',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/delete/{id}/{offset}',              ['as'=>'deleteCronJob',         'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@deleteRecord',              'resource' => 'admin-cron',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/cronjobs/delete/select/records',          ['as'=>'deleteSelectCronJob',   'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@deleteRecordsSelect',       'resource' => 'admin-cron',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | LANGUAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/langs/{offset?}',                            ['as' => 'Lang',                'uses' => 'Pulsar\Pulsar\Controllers\Langs@index',                      'resource' => 'admin-lang',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/langs/json/data',                            ['as' => 'jsonDataLang',        'uses' => 'Pulsar\Pulsar\Controllers\Langs@jsonData',                   'resource' => 'admin-lang',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/create/{offset}',                      ['as' => 'createLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@createRecord',               'resource' => 'admin-lang',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/store/{offset}',                      ['as' => 'storeLang',           'uses' => 'Pulsar\Pulsar\Controllers\Langs@storeRecord',                'resource' => 'admin-lang',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/{id}/edit/{offset}',                   ['as' => 'editLang',            'uses' => 'Pulsar\Pulsar\Controllers\Langs@editRecord',                 'resource' => 'admin-lang',             'action' => 'create']);
    Route::put(config('pulsar.appName') . '/pulsar/langs/update/{id}/{offset}',                 ['as' => 'updateLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@updateRecord',               'resource' => 'admin-lang',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/delete/{id}/{offset}',                 ['as' => 'deleteLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@deleteRecord',               'resource' => 'admin-lang',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/langs/delete/select/records',             ['as' => 'deleteSelectLang',    'uses' => 'Pulsar\Pulsar\Controllers\Langs@deleteRecordsSelect',        'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/delete/image/lang/{id}',              ['as' => 'deleteImageLang',     'uses' => 'Pulsar\Pulsar\Controllers\Langs@ajaxDeleteImage',            'resource' => 'admin-lang',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | COUNTRIES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/countries/{lang}/{offset?}',                         ['as'=>'Country',                   'uses'=>'Pulsar\Pulsar\Controllers\Countries@index',                    'resource' => 'admin-country',          'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/countries/json/data/{lang}',                         ['as'=>'jsonDataCountry',           'uses'=>'Pulsar\Pulsar\Controllers\Countries@jsonData',                 'resource' => 'admin-country',          'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/create/{lang}/{offset}/{id?}',             ['as'=>'createCountry',             'uses'=>'Pulsar\Pulsar\Controllers\Countries@createRecord',             'resource' => 'admin-country',          'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/store/{lang}/{offset}/{id?}',             ['as'=>'storeCountry',              'uses'=>'Pulsar\Pulsar\Controllers\Countries@storeRecord',              'resource' => 'admin-country',          'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/{id}/edit/{lang}/{offset}',                ['as'=>'editCountry',               'uses'=>'Pulsar\Pulsar\Controllers\Countries@editRecord',               'resource' => 'admin-country',          'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/countries/update/{lang}/{id}/{offset}',              ['as'=>'updateCountry',             'uses'=>'Pulsar\Pulsar\Controllers\Countries@updateRecord',             'resource' => 'admin-country',          'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/delete/{lang}/{id}/{offset}',              ['as'=>'deleteCountry',             'uses'=>'Pulsar\Pulsar\Controllers\Countries@deleteRecord',             'resource' => 'admin-country',          'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/delete/translation/{lang}/{id}/{offset}',  ['as'=>'deleteTranslationCountry',  'uses'=>'Pulsar\Pulsar\Controllers\Countries@deleteTranslationRecord',  'resource' => 'admin-country',          'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/countries/delete/select/records/{lang}',          ['as'=>'deleteSelectCountry',       'uses'=>'Pulsar\Pulsar\Controllers\Countries@deleteRecordsSelect',      'resource' => 'admin-country',          'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/json/{country?}',                         ['as'=>'jsonGetCountry',            'uses'=>'Pulsar\Pulsar\Controllers\Countries@jsonCountry',              'resource' => 'admin-country',          'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 1
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/territorialareas1/{country}/{offset?}',                                        ['as'=>'TerritorialArea1',                      'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@index',                                'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas1/json/data/{country}',                                        ['as'=>'jsonDataTerritorialArea1',              'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@jsonData',                             'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas1/create/{country}/{offset}',                                  ['as'=>'createTerritorialArea1',                'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@createRecord',                         'resource' => 'admin-country-at1',  'action' => 'create']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas1/store/{country}/{offset}',                                  ['as'=>'storeTerritorialArea1',                 'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@storeRecord',                          'resource' => 'admin-country-at1',  'action' => 'create']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas1/{id}/edit/{country}/{offset}',                               ['as'=>'editTerritorialArea1',                  'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@editRecord',                           'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::put(config('pulsar.appName').'/pulsar/territorialareas1/update/{country}/{id}/{offset}',                             ['as'=>'updateTerritorialArea1',                'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@updateRecord',                         'resource' => 'admin-country-at1',  'action' => 'edit']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas1/delete/{country}/{id}/{offset}',                             ['as'=>'deleteTerritorialArea1',                'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@deleteRecord',                         'resource' => 'admin-country-at1',  'action' => 'delete']);
    Route::delete(config('pulsar.appName').'/pulsar/territorialareas1/delete/select/records/{country}',                         ['as'=>'deleteSelectTerritorialArea1',          'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@deleteRecordsSelect',                  'resource' => 'admin-country-at1',  'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas1/json/get_areas_territoriales_1_from_country/{country?}',    ['as'=>'jsonGetTerritorialArea1',               'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas1@jsonGetAreasTerritoriales1FromPais',   'resource' => 'admin-country-at1',  'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 2
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/territorialareas2/{country}/{offset?}',                                                ['as'=>'TerritorialArea2',                 'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@index',                               'resource' => 'admin-country-at2',             'action' => 'access']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas2/json/data/{country}',                                                ['as'=>'jsonDataTerritorialArea2',         'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@jsonData',                            'resource' => 'admin-country-at2',             'action' => 'access']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas2/create/{country}/{offset}',                                          ['as'=>'createTerritorialArea2',           'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@createRecord',                        'resource' => 'admin-country-at2',             'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas2/store/{country}/{offset}',                                          ['as'=>'storeTerritorialArea2',            'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@storeRecord',                         'resource' => 'admin-country-at2',             'action' => 'delete']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas2/{id}/edit/{country}/{offset}',                                       ['as'=>'editTerritorialArea2',             'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@editRecord',                          'resource' => 'admin-country-at2',             'action' => 'delete']);
    Route::put(config('pulsar.appName').'/pulsar/territorialareas2/update/{country}/{id}/{offset}',                                     ['as'=>'updateTerritorialArea2',           'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@updateRecord',                        'resource' => 'admin-country-at2',             'action' => 'delete']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas2/delete/{country}/{id}/{offset}',                                     ['as'=>'deleteTerritorialArea2',           'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@deleteRecord',                        'resource' => 'admin-country-at2',             'action' => 'delete']);
    Route::delete(config('pulsar.appName').'/pulsar/territorialareas2/delete/select/records/{country}',                                 ['as'=>'deleteSelectTerritorialArea2',     'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@deleteRecordsSelect',                 'resource' => 'admin-country-at2',             'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas2/json/get_areas_territoriales_2_from_area_territorial_1/{id}',       ['as'=>'jsonTerritorialArea2',             'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas2@jsonGetAreasTerritoriales2FromAreaTerritorial1',            'resource' => 'admin-country-at2',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 3
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/territorialareas3/{country}/{offset?}',                                                ['as'=>'TerritorialArea3',              'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@index',                                'resource' => 'admin-country-at3',             'action' => 'access']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas3/json/data/country/{country}',                                        ['as'=>'jsonDataTerritorialArea3',      'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@jsonData',                             'resource' => 'admin-country-at3',             'action' => 'access']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas3/create/{country}/{offset}',                                          ['as'=>'createTerritorialArea3',        'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@createRecord',                               'resource' => 'admin-country-at3',             'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas3/store/{country}/{offset}',                                          ['as'=>'storeTerritorialArea3',         'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@storeRecord',                                'resource' => 'admin-country-at3',             'action' => 'delete']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas3/{id}/edit/{offset}',                                                 ['as'=>'editTerritorialArea3',          'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@editRecord',                                 'resource' => 'admin-country-at3',             'action' => 'delete']);
    Route::put(config('pulsar.appName').'/pulsar/territorialareas3/update/{country}/{offset}',                                          ['as'=>'updateTerritorialArea3',        'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@updateRecord',                               'resource' => 'admin-country-at3',             'action' => 'delete']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas3/delete/{country}/{id?}',                                             ['as'=>'deleteTerritorialArea3',        'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@deleteRecord',                               'resource' => 'admin-country-at3',             'action' => 'delete']);
    Route::delete(config('pulsar.appName').'/pulsar/territorialareas3/delete/select/records/{country}',                                 ['as'=>'deleteSelectTerritorialArea3',  'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@deleteRecordsSelect',                         'resource' => 'admin-country-at3',             'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas3/json/get_areas_territoriales_3_from_area_territorial_2/{id}',       ['as'=>'jsonTerritorialArea3',          'uses'=>'Pulsar\Pulsar\Controllers\TerritorialAreas3@jsonGetAreasTerritoriales3FromAreaTerritorial2',            'resource' => 'admin-country-at3',             'action' => 'delete']);

    



    /*
    |--------------------------------------------------------------------------
    | USUARIO
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/users/{offset?}',                                  ['as'=>'User', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@index',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/users/json/data',                  ['as'=>'jsonDataUsuarios', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@jsonData',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/users/create/{offset}',              ['as'=>'createUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@create',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/users/store/{offset}',              ['as'=>'storeUser', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@store',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/users/{id}/edit/{offset}',           ['as'=>'editUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@edit',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/users/update/{offset}',             ['as'=>'updateUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@update',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/users/delete/{id}',               ['as'=>'deleteUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@delete',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/users/delete/select/records',   ['as'=>'deleteSelectUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@deleteSelect',            'resource' => 'admin-lang',             'action' => 'delete']);




    /*
    |--------------------------------------------------------------------------
    | DIRECCIONES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/direcciones/json/data/{resource}/{object}',         ['as'=>'jsonDataDirecciones',  'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@jsonData',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/direcciones/create/{resource}/{object}',            ['as'=>'createDireccion',      'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@create',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/direcciones/store',                                ['as'=>'storeDireccion',       'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@store',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/direcciones/{id}/edit/{offset}',                      ['as'=>'editDireccion',        'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@edit',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/direcciones/update/{offset}',                        ['as'=>'updateDireccion',      'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@update',            'resource' => 'admin-lang',             'action' => 'delete']);








    /*
    |--------------------------------------------------------------------------
    | GOOGLE SERVICES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/google/services',                                     ['as'=>'googleServices', 'uses'=>'Pulsar\Pulsar\Controllers\GoogleServices@index',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/google/services/update',                             ['as'=>'updateGoogleServices', 'uses'=>'Pulsar\Pulsar\Controllers\GoogleServices@update',            'resource' => 'admin-lang',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | CONTENT BUILDER
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/{theme}/edit/{input}',               ['as'=>'contentbuilderEdit', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@index',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/saveimage',                   ['as'=>'contentbuilderSaveImage', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@saveImage',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/blocks/{theme}',              ['as'=>'contentbuilderBlocks', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@getBlocks',            'resource' => 'admin-lang',             'action' => 'delete']);


});