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
Route::get(config('pulsar.appName') . '/pulsar/login',                                          ['as'=>'getLogin',              'uses'=>'Syscover\Pulsar\Controllers\Auth\AuthController@getLogin']);
Route::post(config('pulsar.appName') . '/pulsar/login',                                         ['as'=>'postLogin',             'uses'=>'Syscover\Pulsar\Controllers\Auth\AuthController@postLogin']);

// LOGOUT
Route::get(config('pulsar.appName') . '/pulsar/logout',                                         ['as' => 'logout',              'uses'=>'Syscover\Pulsar\Controllers\Auth\AuthController@getLogout']);

// PASSWORD REMINDER
Route::any(config('pulsar.appName') . '/pulsar/email/reset/password',                           ['as'=>'emailResetPassword',    'uses'=>'Syscover\Pulsar\Controllers\Auth\PasswordController@postEmail']);
Route::get(config('pulsar.appName') . '/pulsar/password/reset/{token}',                         ['as'=>'getResetPassword',      'uses'=>'Syscover\Pulsar\Controllers\Auth\PasswordController@getReset']);
Route::post(config('pulsar.appName') . '/pulsar/password/reset/{token}',                        ['as'=>'postResetPassword',     'uses'=>'Syscover\Pulsar\Controllers\Auth\PasswordController@postReset']);

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get(config('pulsar.appName') . '/pulsar/dashboard',                                  ['as' => 'dashboard',               'uses' => 'Syscover\Pulsar\Controllers\Dashboard@index',                'middleware' => 'auth.pulsar']);


Route::group(['middleware' => ['auth.pulsar','permission.pulsar','locale.pulsar']], function() {



    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/actions/{offset?}',                          ['as'=>'Action',                'uses'=>'Syscover\Pulsar\Controllers\Actions@index',                      'resource' => 'admin-perm-action',        'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/actions/json/data',                          ['as'=>'jsonDataAction',        'uses'=>'Syscover\Pulsar\Controllers\Actions@jsonData',                   'resource' => 'admin-perm-action',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/create/{offset}',                    ['as'=>'createAction',          'uses'=>'Syscover\Pulsar\Controllers\Actions@createRecord',               'resource' => 'admin-perm-action',        'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/store/{offset}',                    ['as'=>'storeAction',           'uses'=>'Syscover\Pulsar\Controllers\Actions@storeRecord',                'resource' => 'admin-perm-action',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/{id}/edit/{offset}',                 ['as'=>'editAction',            'uses'=>'Syscover\Pulsar\Controllers\Actions@editRecord',                 'resource' => 'admin-perm-action',        'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/actions/update/{id}/{offset}',               ['as'=>'updateAction',          'uses'=>'Syscover\Pulsar\Controllers\Actions@updateRecord',               'resource' => 'admin-perm-action',        'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/delete/{id}/{offset}',               ['as'=>'deleteAction',          'uses'=>'Syscover\Pulsar\Controllers\Actions@deleteRecord',               'resource' => 'admin-perm-action',        'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/actions/delete/select/records',           ['as'=>'deleteSelectAction',    'uses'=>'Syscover\Pulsar\Controllers\Actions@deleteRecordsSelect',        'resource' => 'admin-perm-action',        'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | RESOURCES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/resources/{offset?}',                        ['as'=>'Resource',              'uses'=>'Syscover\Pulsar\Controllers\Resources@index',                    'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/resources/json/data',                        ['as'=>'jsonDataResource',      'uses'=>'Syscover\Pulsar\Controllers\Resources@jsonData',                 'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/create/{offset}',                  ['as'=>'createResource',        'uses'=>'Syscover\Pulsar\Controllers\Resources@createRecord',             'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/resources/store/{offset}',                  ['as'=>'storeResource',         'uses'=>'Syscover\Pulsar\Controllers\Resources@storeRecord',              'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/{id}/edit/{offset}',               ['as'=>'editResource',          'uses'=>'Syscover\Pulsar\Controllers\Resources@editRecord',               'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/resources/update/{id}/{offset}',             ['as'=>'updateResource',        'uses'=>'Syscover\Pulsar\Controllers\Resources@updateRecord',             'resource' => 'admin-perm-resource',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/delete/{id}/{offset}',             ['as'=>'deleteResource',        'uses'=>'Syscover\Pulsar\Controllers\Resources@deleteRecord',             'resource' => 'admin-perm-resource',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/resources/delete/select/records',         ['as'=>'deleteSelectResource',  'uses'=>'Syscover\Pulsar\Controllers\Resources@deleteRecordsSelect',      'resource' => 'admin-perm-resource',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PROFILES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/profiles/{offset?}',                         ['as'=>'Profile',               'uses'=>'Syscover\Pulsar\Controllers\Profiles@index',                     'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/profiles/json/data',                         ['as'=>'jsonDataProfile',       'uses'=>'Syscover\Pulsar\Controllers\Profiles@jsonData',                  'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/create/{offset}',                   ['as'=>'createProfile',         'uses'=>'Syscover\Pulsar\Controllers\Profiles@createRecord',              'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/profiles/store/{offset}',                   ['as'=>'storeProfile',          'uses'=>'Syscover\Pulsar\Controllers\Profiles@storeRecord',               'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/{id}/edit/{offset}',                ['as'=>'editProfile',           'uses'=>'Syscover\Pulsar\Controllers\Profiles@editRecord',                'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/profiles/update/{id}/{offset}',              ['as'=>'updateProfile',         'uses'=>'Syscover\Pulsar\Controllers\Profiles@updateRecord',              'resource' => 'admin-perm-profile',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/delete/{id}/{offset}',              ['as'=>'deleteProfile',         'uses'=>'Syscover\Pulsar\Controllers\Profiles@deleteRecord',              'resource' => 'admin-perm-profile',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/profiles/delete/select/records',          ['as'=>'deleteSelectProfile',   'uses'=>'Syscover\Pulsar\Controllers\Profiles@deleteRecordsSelect',       'resource' => 'admin-perm-profile',    'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/allpermissions/{id}/{offset}',      ['as'=>'allPermissionsProfile', 'uses'=>'Syscover\Pulsar\Controllers\Profiles@setAllPermissions',         'resource' => 'admin-perm-perm',       'action' => 'create']);

    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/permissions/{offset}/{profile}/{offsetProfile?}',        ['as'=>'Permission',                'uses'=>'Syscover\Pulsar\Controllers\Permissions@index',                'resource' => 'admin-perm-perm',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/permissions/json/data/profile/{profile}',                ['as'=>'jsonDataPermission',        'uses'=>'Syscover\Pulsar\Controllers\Permissions@jsonData',             'resource' => 'admin-perm-perm',    'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/create/{num}/{num1}/{any}',            ['as'=>'jsonCreatePermission',      'uses'=>'Syscover\Pulsar\Controllers\Permissions@jsonCreate',           'resource' => 'admin-perm-perm',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/delete/{num}/{num1}/{any}',            ['as'=>'jsonDestroyPermission',     'uses'=>'Syscover\Pulsar\Controllers\Permissions@jsonDestroy',          'resource' => 'admin-perm-perm',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PACKAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/packages/{offset?}',                         ['as'=>'Package',               'uses'=>'Syscover\Pulsar\Controllers\Packages@index',                     'resource' => 'admin-package',         'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/packages/json/data',                         ['as'=>'jsonDataPackage',       'uses'=>'Syscover\Pulsar\Controllers\Packages@jsonData',                  'resource' => 'admin-package',         'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/create/{offset}',                   ['as'=>'createPackage',         'uses'=>'Syscover\Pulsar\Controllers\Packages@createRecord',              'resource' => 'admin-package',         'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/packages/store/{offset}',                   ['as'=>'storePackage',          'uses'=>'Syscover\Pulsar\Controllers\Packages@storeRecord',               'resource' => 'admin-package',         'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/{id}/edit/{offset}',                ['as'=>'editPackage',           'uses'=>'Syscover\Pulsar\Controllers\Packages@editRecord',                'resource' => 'admin-package',         'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/packages/update/{id}/{offset}',              ['as'=>'updatePackage',         'uses'=>'Syscover\Pulsar\Controllers\Packages@updateRecord',              'resource' => 'admin-package',         'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/delete/{id}/{offset}',              ['as'=>'deletePackage',         'uses'=>'Syscover\Pulsar\Controllers\Packages@deleteRecord',              'resource' => 'admin-package',         'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/packages/delete/select/records',          ['as'=>'deleteSelectPackage',   'uses'=>'Syscover\Pulsar\Controllers\Packages@deleteRecordsSelect',       'resource' => 'admin-package',         'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | CRON JOBS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/{offset?}',                         ['as'=>'CronJob',               'uses'=>'Syscover\Pulsar\Controllers\CronJobs@index',                     'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/run/{offset}',                 ['as'=>'runCronJob',            'uses'=>'Syscover\Pulsar\Controllers\CronJobs@run',                       'resource' => 'admin-cron',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/json/data',                         ['as'=>'jsonDataCronJob',       'uses'=>'Syscover\Pulsar\Controllers\CronJobs@jsonData',                  'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/create/{offset}',                   ['as'=>'createCronJob',         'uses'=>'Syscover\Pulsar\Controllers\CronJobs@createRecord',              'resource' => 'admin-cron',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/cronjobs/store/{offset}',                   ['as'=>'storeCronJob',          'uses'=>'Syscover\Pulsar\Controllers\CronJobs@storeRecord',               'resource' => 'admin-cron',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/edit/{offset}',                ['as'=>'editCronJob',           'uses'=>'Syscover\Pulsar\Controllers\CronJobs@editRecord',                'resource' => 'admin-cron',             'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/cronjobs/update/{id}/{offset}',              ['as'=>'updateCronJob',         'uses'=>'Syscover\Pulsar\Controllers\CronJobs@updateRecord',              'resource' => 'admin-cron',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/delete/{id}/{offset}',              ['as'=>'deleteCronJob',         'uses'=>'Syscover\Pulsar\Controllers\CronJobs@deleteRecord',              'resource' => 'admin-cron',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/cronjobs/delete/select/records',          ['as'=>'deleteSelectCronJob',   'uses'=>'Syscover\Pulsar\Controllers\CronJobs@deleteRecordsSelect',       'resource' => 'admin-cron',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | LANGUAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/langs/{offset?}',                            ['as' => 'Lang',                'uses' => 'Syscover\Pulsar\Controllers\Langs@index',                      'resource' => 'admin-lang',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/langs/json/data',                            ['as' => 'jsonDataLang',        'uses' => 'Syscover\Pulsar\Controllers\Langs@jsonData',                   'resource' => 'admin-lang',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/create/{offset}',                      ['as' => 'createLang',          'uses' => 'Syscover\Pulsar\Controllers\Langs@createRecord',               'resource' => 'admin-lang',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/store/{offset}',                      ['as' => 'storeLang',           'uses' => 'Syscover\Pulsar\Controllers\Langs@storeRecord',                'resource' => 'admin-lang',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/{id}/edit/{offset}',                   ['as' => 'editLang',            'uses' => 'Syscover\Pulsar\Controllers\Langs@editRecord',                 'resource' => 'admin-lang',             'action' => 'create']);
    Route::put(config('pulsar.appName') . '/pulsar/langs/update/{id}/{offset}',                 ['as' => 'updateLang',          'uses' => 'Syscover\Pulsar\Controllers\Langs@updateRecord',               'resource' => 'admin-lang',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/delete/{id}/{offset}',                 ['as' => 'deleteLang',          'uses' => 'Syscover\Pulsar\Controllers\Langs@deleteRecord',               'resource' => 'admin-lang',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/langs/delete/select/records',             ['as' => 'deleteSelectLang',    'uses' => 'Syscover\Pulsar\Controllers\Langs@deleteRecordsSelect',        'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/delete/image/lang/{id}',              ['as' => 'deleteImageLang',     'uses' => 'Syscover\Pulsar\Controllers\Langs@ajaxDeleteImage',            'resource' => 'admin-lang',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | COUNTRIES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/countries/{lang}/{offset?}',                         ['as'=>'Country',                   'uses'=>'Syscover\Pulsar\Controllers\Countries@index',                    'resource' => 'admin-country',          'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/countries/json/data/{lang}',                         ['as'=>'jsonDataCountry',           'uses'=>'Syscover\Pulsar\Controllers\Countries@jsonData',                 'resource' => 'admin-country',          'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/create/{lang}/{offset}/{id?}',             ['as'=>'createCountry',             'uses'=>'Syscover\Pulsar\Controllers\Countries@createRecord',             'resource' => 'admin-country',          'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/store/{lang}/{offset}/{id?}',             ['as'=>'storeCountry',              'uses'=>'Syscover\Pulsar\Controllers\Countries@storeRecord',              'resource' => 'admin-country',          'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/{id}/edit/{lang}/{offset}',                ['as'=>'editCountry',               'uses'=>'Syscover\Pulsar\Controllers\Countries@editRecord',               'resource' => 'admin-country',          'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/countries/update/{lang}/{id}/{offset}',              ['as'=>'updateCountry',             'uses'=>'Syscover\Pulsar\Controllers\Countries@updateRecord',             'resource' => 'admin-country',          'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/delete/{lang}/{id}/{offset}',              ['as'=>'deleteCountry',             'uses'=>'Syscover\Pulsar\Controllers\Countries@deleteRecord',             'resource' => 'admin-country',          'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/delete/translation/{lang}/{id}/{offset}',  ['as'=>'deleteTranslationCountry',  'uses'=>'Syscover\Pulsar\Controllers\Countries@deleteTranslationRecord',  'resource' => 'admin-country',          'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/countries/delete/select/records/{lang}',          ['as'=>'deleteSelectCountry',       'uses'=>'Syscover\Pulsar\Controllers\Countries@deleteRecordsSelect',      'resource' => 'admin-country',          'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/json/country/{country?}',                 ['as'=>'jsonGetCountry',            'uses'=>'Syscover\Pulsar\Controllers\Countries@jsonCountry',              'resource' => 'admin-country',          'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/countries/json/countries/{lang?}',                   ['as'=>'jsonGetCountries',          'uses'=>'Syscover\Pulsar\Controllers\Countries@jsonCountries',            'resource' => 'admin-country',          'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | TERRITORIAL AREAS 1
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/territorialareas1/{country}/{offset?}',                            ['as'=>'TerritorialArea1',                      'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@index',                                'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas1/json/data/{country}',                            ['as'=>'jsonDataTerritorialArea1',              'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@jsonData',                             'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas1/create/{country}/{offset}',                      ['as'=>'createTerritorialArea1',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@createRecord',                         'resource' => 'admin-country-at1',  'action' => 'create']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas1/store/{country}/{offset}',                      ['as'=>'storeTerritorialArea1',                 'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@storeRecord',                          'resource' => 'admin-country-at1',  'action' => 'create']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas1/{id}/edit/{country}/{offset}',                   ['as'=>'editTerritorialArea1',                  'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@editRecord',                           'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::put(config('pulsar.appName').'/pulsar/territorialareas1/update/{country}/{id}/{offset}',                 ['as'=>'updateTerritorialArea1',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@updateRecord',                         'resource' => 'admin-country-at1',  'action' => 'edit']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas1/delete/{country}/{id}/{offset}',                 ['as'=>'deleteTerritorialArea1',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@deleteRecord',                         'resource' => 'admin-country-at1',  'action' => 'delete']);
    Route::delete(config('pulsar.appName').'/pulsar/territorialareas1/delete/select/records/{country}',             ['as'=>'deleteSelectTerritorialArea1',          'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@deleteRecordsSelect',                  'resource' => 'admin-country-at1',  'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas1/json/from/country/{country?}',                  ['as'=>'jsonGetTerritorialArea1',               'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas1@jsonTerritorialAreas1FromCountry',     'resource' => 'admin-country-at1',  'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | TERRITORIAL AREAS 2
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/territorialareas2/{country}/{offset?}',                            ['as'=>'TerritorialArea2',                 'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@index',                                     'resource' => 'admin-country-at2',  'action' => 'access']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas2/json/data/{country}',                            ['as'=>'jsonDataTerritorialArea2',         'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@jsonData',                                  'resource' => 'admin-country-at2',  'action' => 'access']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas2/create/{country}/{offset}',                      ['as'=>'createTerritorialArea2',           'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@createRecord',                              'resource' => 'admin-country-at2',  'action' => 'create']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas2/store/{country}/{offset}',                      ['as'=>'storeTerritorialArea2',            'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@storeRecord',                               'resource' => 'admin-country-at2',  'action' => 'create']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas2/{id}/edit/{country}/{offset}',                   ['as'=>'editTerritorialArea2',             'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@editRecord',                                'resource' => 'admin-country-at2',  'action' => 'access']);
    Route::put(config('pulsar.appName').'/pulsar/territorialareas2/update/{country}/{id}/{offset}',                 ['as'=>'updateTerritorialArea2',           'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@updateRecord',                              'resource' => 'admin-country-at2',  'action' => 'edit']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas2/delete/{country}/{id}/{offset}',                 ['as'=>'deleteTerritorialArea2',           'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@deleteRecord',                              'resource' => 'admin-country-at2',  'action' => 'delete']);
    Route::delete(config('pulsar.appName').'/pulsar/territorialareas2/delete/select/records/{country}',             ['as'=>'deleteSelectTerritorialArea2',     'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@deleteRecordsSelect',                       'resource' => 'admin-country-at2',  'action' => 'delete']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas2/json/from/territorialarea1/{country}/{id}',      ['as'=>'jsonTerritorialArea2',             'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas2@jsonTerritorialAreas2FromTerritorialArea1', 'resource' => 'admin-country-at2',  'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | TERRITORIAL AREAS 3
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/territorialareas3/{country}/{offset?}',                            ['as'=>'TerritorialArea3',              'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@index',                                        'resource' => 'admin-country-at3',  'action' => 'access']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas3/json/data/country/{country}',                    ['as'=>'jsonDataTerritorialArea3',      'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@jsonData',                                     'resource' => 'admin-country-at3',  'action' => 'access']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas3/create/{country}/{offset}',                      ['as'=>'createTerritorialArea3',        'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@createRecord',                                 'resource' => 'admin-country-at3',  'action' => 'create']);
    Route::post(config('pulsar.appName').'/pulsar/territorialareas3/store/{country}/{offset}',                      ['as'=>'storeTerritorialArea3',         'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@storeRecord',                                  'resource' => 'admin-country-at3',  'action' => 'create']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas3/{id}/edit/{country}/{offset}',                   ['as'=>'editTerritorialArea3',          'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@editRecord',                                   'resource' => 'admin-country-at3',  'action' => 'access']);
    Route::put(config('pulsar.appName').'/pulsar/territorialareas3/update/{country}/{id}/{offset}',                 ['as'=>'updateTerritorialArea3',        'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@updateRecord',                                 'resource' => 'admin-country-at3',  'action' => 'edit']);
    Route::get(config('pulsar.appName').'/pulsar/territorialareas3/delete/{country}/{id?}',                         ['as'=>'deleteTerritorialArea3',        'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@deleteRecord',                                 'resource' => 'admin-country-at3',  'action' => 'delete']);
    Route::delete(config('pulsar.appName').'/pulsar/territorialareas3/delete/select/records/{country}',             ['as'=>'deleteSelectTerritorialArea3',  'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@deleteRecordsSelect',                          'resource' => 'admin-country-at3',  'action' => 'delete']);
    Route::any(config('pulsar.appName').'/pulsar/territorialareas3/json/from/territorialarea2/{country}/{id?}',    ['as'=>'jsonTerritorialArea3',          'uses'=>'Syscover\Pulsar\Controllers\TerritorialAreas3@jsonTerritorialAreas3FromTerritorialArea2',    'resource' => 'admin-country-at3',  'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/users/{offset?}',                        ['as'=>'User',              'uses'=>'Syscover\Pulsar\Controllers\Users@index',                'resource' => 'admin-user',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/users/json/data',                        ['as'=>'jsonDataUser',      'uses'=>'Syscover\Pulsar\Controllers\Users@jsonData',             'resource' => 'admin-user',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/users/create/{offset}',                  ['as'=>'createUser',        'uses'=>'Syscover\Pulsar\Controllers\Users@createRecord',         'resource' => 'admin-user',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/users/store/{offset}',                  ['as'=>'storeUser',         'uses'=>'Syscover\Pulsar\Controllers\Users@storeRecord',          'resource' => 'admin-user',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/users/{id}/edit/{offset}',               ['as'=>'editUser',          'uses'=>'Syscover\Pulsar\Controllers\Users@editRecord',           'resource' => 'admin-user',             'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/users/update/{id}/{offset}',             ['as'=>'updateUser',        'uses'=>'Syscover\Pulsar\Controllers\Users@updateRecord',         'resource' => 'admin-user',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/users/delete/{id}/{offset}',             ['as'=>'deleteUser',        'uses'=>'Syscover\Pulsar\Controllers\Users@deleteRecord',         'resource' => 'admin-user',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/users/delete/select/records',         ['as'=>'deleteSelectUser',  'uses'=>'Syscover\Pulsar\Controllers\Users@deleteRecordsSelect',  'resource' => 'admin-user',             'action' => 'delete']);









    /*
    |--------------------------------------------------------------------------
    | ADDRESS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/address/json/data/{resource}/{object}',         ['as'=>'jsonDataDirecciones',  'uses'=>'Syscover\Pulsar\Controllers\Direcciones@jsonData',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/address/create/{resource}/{object}',            ['as'=>'createDireccion',      'uses'=>'Syscover\Pulsar\Controllers\Direcciones@create',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/address/store',                                ['as'=>'storeDireccion',       'uses'=>'Syscover\Pulsar\Controllers\Direcciones@store',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/address/{id}/edit/{offset}',                    ['as'=>'editDireccion',        'uses'=>'Syscover\Pulsar\Controllers\Direcciones@edit',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/address/update/{offset}',                      ['as'=>'updateDireccion',      'uses'=>'Syscover\Pulsar\Controllers\Direcciones@update',            'resource' => 'admin-lang',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | GOOGLE SERVICES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName').'/pulsar/google/services',                                     ['as'=>'googleServices', 'uses'=>'Syscover\Pulsar\Controllers\GoogleServices@index',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName').'/pulsar/google/services/update',                             ['as'=>'updateGoogleServices', 'uses'=>'Syscover\Pulsar\Controllers\GoogleServices@update',            'resource' => 'admin-lang',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | CONTENT BUILDER
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/{theme}/edit/{input}',               ['as'=>'contentbuilderEdit', 'uses'=>'Syscover\Pulsar\Controllers\ContentBuilder@index',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/saveimage',                   ['as'=>'contentbuilderSaveImage', 'uses'=>'Syscover\Pulsar\Controllers\ContentBuilder@saveImage',            'resource' => 'admin-lang',             'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/blocks/{theme}',              ['as'=>'contentbuilderBlocks', 'uses'=>'Syscover\Pulsar\Controllers\ContentBuilder@getBlocks',            'resource' => 'admin-lang',             'action' => 'delete']);
    
});