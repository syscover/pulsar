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

Route::group(['middleware' => ['web']], function() {

    // ENTER TO APPLICATION
    Route::get(config('pulsar.appName'), function () {
        return Redirect::to(route('dashboard'));
    });

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get(config('pulsar.appName') . '/pulsar/dashboard', ['middleware' => 'auth.pulsar', 'as' => 'dashboard', 'uses' => 'Syscover\Pulsar\Controllers\DashboardController@index']);

    /*
    |--------------------------------------------------------------------------
    | LOGIN AND LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::get(config('pulsar.appName') . '/pulsar/login',      ['as' => 'getLogin',    'uses' => 'Syscover\Pulsar\Controllers\Auth\AuthController@getLogin']);
    Route::post(config('pulsar.appName') . '/pulsar/login',     ['as' => 'postLogin',   'uses' => 'Syscover\Pulsar\Controllers\Auth\AuthController@authenticate']);
    Route::get(config('pulsar.appName') . '/pulsar/logout',     ['as' => 'logout',      'uses' => 'Syscover\Pulsar\Controllers\Auth\AuthController@getLogout']);

    /*
    |--------------------------------------------------------------------------
    | PASSWORD REMINDER
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/email/send/reset/link/email',    ['as' => 'sendResetLinkEmail',      'uses' => 'Syscover\Pulsar\Controllers\Auth\PasswordController@postEmail']);
    Route::get(config('pulsar.appName') . '/pulsar/password/reset/{token}',         ['as' => 'showResetForm',           'uses' => 'Syscover\Pulsar\Controllers\Auth\PasswordController@getReset']);
    Route::post(config('pulsar.appName') . '/pulsar/password/reset/{token}',        ['as' => 'postResetPassword',       'uses' => 'Syscover\Pulsar\Controllers\Auth\PasswordController@postReset']);
});

Route::group(['middleware' => ['web', 'pulsar']], function() {

    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/actions/{offset?}',                          ['as'=>'action',                'uses'=>'Syscover\Pulsar\Controllers\ActionController@index',                      'resource' => 'admin-perm-action',        'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/actions/json/data',                          ['as'=>'jsonDataAction',        'uses'=>'Syscover\Pulsar\Controllers\ActionController@jsonData',                   'resource' => 'admin-perm-action',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/create/{offset}',                    ['as'=>'createAction',          'uses'=>'Syscover\Pulsar\Controllers\ActionController@createRecord',               'resource' => 'admin-perm-action',        'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/actions/store/{offset}',                    ['as'=>'storeAction',           'uses'=>'Syscover\Pulsar\Controllers\ActionController@storeRecord',                'resource' => 'admin-perm-action',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/{id}/edit/{offset}',                 ['as'=>'editAction',            'uses'=>'Syscover\Pulsar\Controllers\ActionController@editRecord',                 'resource' => 'admin-perm-action',        'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/actions/update/{id}/{offset}',               ['as'=>'updateAction',          'uses'=>'Syscover\Pulsar\Controllers\ActionController@updateRecord',               'resource' => 'admin-perm-action',        'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/actions/delete/{id}/{offset}',               ['as'=>'deleteAction',          'uses'=>'Syscover\Pulsar\Controllers\ActionController@deleteRecord',               'resource' => 'admin-perm-action',        'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/actions/delete/select/records',           ['as'=>'deleteSelectAction',    'uses'=>'Syscover\Pulsar\Controllers\ActionController@deleteRecordsSelect',        'resource' => 'admin-perm-action',        'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | RESOURCES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/resources/{offset?}',                        ['as'=>'resource',              'uses'=>'Syscover\Pulsar\Controllers\ResourceController@index',                    'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/resources/json/data',                        ['as'=>'jsonDataResource',      'uses'=>'Syscover\Pulsar\Controllers\ResourceController@jsonData',                 'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/create/{offset}',                  ['as'=>'createResource',        'uses'=>'Syscover\Pulsar\Controllers\ResourceController@createRecord',             'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/resources/store/{offset}',                  ['as'=>'storeResource',         'uses'=>'Syscover\Pulsar\Controllers\ResourceController@storeRecord',              'resource' => 'admin-perm-resource',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/{id}/edit/{offset}',               ['as'=>'editResource',          'uses'=>'Syscover\Pulsar\Controllers\ResourceController@editRecord',               'resource' => 'admin-perm-resource',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/resources/update/{id}/{offset}',             ['as'=>'updateResource',        'uses'=>'Syscover\Pulsar\Controllers\ResourceController@updateRecord',             'resource' => 'admin-perm-resource',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/resources/delete/{id}/{offset}',             ['as'=>'deleteResource',        'uses'=>'Syscover\Pulsar\Controllers\ResourceController@deleteRecord',             'resource' => 'admin-perm-resource',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/resources/delete/select/records',         ['as'=>'deleteSelectResource',  'uses'=>'Syscover\Pulsar\Controllers\ResourceController@deleteRecordsSelect',      'resource' => 'admin-perm-resource',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PROFILES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/profiles/{offset?}',                         ['as'=>'profile',               'uses'=>'Syscover\Pulsar\Controllers\ProfileController@index',                     'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/profiles/json/data',                         ['as'=>'jsonDataProfile',       'uses'=>'Syscover\Pulsar\Controllers\ProfileController@jsonData',                  'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/create/{offset}',                   ['as'=>'createProfile',         'uses'=>'Syscover\Pulsar\Controllers\ProfileController@createRecord',              'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/profiles/store/{offset}',                   ['as'=>'storeProfile',          'uses'=>'Syscover\Pulsar\Controllers\ProfileController@storeRecord',               'resource' => 'admin-perm-profile',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/{id}/edit/{offset}',                ['as'=>'editProfile',           'uses'=>'Syscover\Pulsar\Controllers\ProfileController@editRecord',                'resource' => 'admin-perm-profile',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/profiles/update/{id}/{offset}',              ['as'=>'updateProfile',         'uses'=>'Syscover\Pulsar\Controllers\ProfileController@updateRecord',              'resource' => 'admin-perm-profile',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/delete/{id}/{offset}',              ['as'=>'deleteProfile',         'uses'=>'Syscover\Pulsar\Controllers\ProfileController@deleteRecord',              'resource' => 'admin-perm-profile',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/profiles/delete/select/records',          ['as'=>'deleteSelectProfile',   'uses'=>'Syscover\Pulsar\Controllers\ProfileController@deleteRecordsSelect',       'resource' => 'admin-perm-profile',    'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/profiles/allpermissions/{id}/{offset}',      ['as'=>'allPermissionsProfile', 'uses'=>'Syscover\Pulsar\Controllers\ProfileController@setAllPermissions',         'resource' => 'admin-perm-perm',       'action' => 'create']);

    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/permissions/{offset}/{profile}/{offsetProfile?}',        ['as'=>'permission',                'uses'=>'Syscover\Pulsar\Controllers\PermissionController@index',                'resource' => 'admin-perm-perm',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/permissions/json/data/profile/{profile}',                ['as'=>'jsonDataPermission',        'uses'=>'Syscover\Pulsar\Controllers\PermissionController@jsonData',             'resource' => 'admin-perm-perm',    'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/create/{num}/{num1}/{any}',            ['as'=>'jsonCreatePermission',      'uses'=>'Syscover\Pulsar\Controllers\PermissionController@jsonCreate',           'resource' => 'admin-perm-perm',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/permissions/json/delete/{num}/{num1}/{any}',            ['as'=>'jsonDestroyPermission',     'uses'=>'Syscover\Pulsar\Controllers\PermissionController@jsonDestroy',          'resource' => 'admin-perm-perm',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | PACKAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/packages/{offset?}',                         ['as'=>'package',               'uses'=>'Syscover\Pulsar\Controllers\PackageController@index',                     'resource' => 'admin-package',         'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/packages/json/data',                         ['as'=>'jsonDataPackage',       'uses'=>'Syscover\Pulsar\Controllers\PackageController@jsonData',                  'resource' => 'admin-package',         'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/create/{offset}',                   ['as'=>'createPackage',         'uses'=>'Syscover\Pulsar\Controllers\PackageController@createRecord',              'resource' => 'admin-package',         'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/packages/store/{offset}',                   ['as'=>'storePackage',          'uses'=>'Syscover\Pulsar\Controllers\PackageController@storeRecord',               'resource' => 'admin-package',         'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/{id}/edit/{offset}',                ['as'=>'editPackage',           'uses'=>'Syscover\Pulsar\Controllers\PackageController@editRecord',                'resource' => 'admin-package',         'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/packages/update/{id}/{offset}',              ['as'=>'updatePackage',         'uses'=>'Syscover\Pulsar\Controllers\PackageController@updateRecord',              'resource' => 'admin-package',         'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/packages/delete/{id}/{offset}',              ['as'=>'deletePackage',         'uses'=>'Syscover\Pulsar\Controllers\PackageController@deleteRecord',              'resource' => 'admin-package',         'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/packages/delete/select/records',          ['as'=>'deleteSelectPackage',   'uses'=>'Syscover\Pulsar\Controllers\PackageController@deleteRecordsSelect',       'resource' => 'admin-package',         'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | CRON JOBS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/{offset?}',                         ['as'=>'cronJob',               'uses'=>'Syscover\Pulsar\Controllers\CronJobController@index',                     'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/run/{offset}',                 ['as'=>'runCronJob',            'uses'=>'Syscover\Pulsar\Controllers\CronJobController@run',                       'resource' => 'admin-cron',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/cronjobs/json/data',                         ['as'=>'jsonDataCronJob',       'uses'=>'Syscover\Pulsar\Controllers\CronJobController@jsonData',                  'resource' => 'admin-cron',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/create/{offset}',                   ['as'=>'createCronJob',         'uses'=>'Syscover\Pulsar\Controllers\CronJobController@createRecord',              'resource' => 'admin-cron',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/cronjobs/store/{offset}',                   ['as'=>'storeCronJob',          'uses'=>'Syscover\Pulsar\Controllers\CronJobController@storeRecord',               'resource' => 'admin-cron',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/{id}/edit/{offset}',                ['as'=>'editCronJob',           'uses'=>'Syscover\Pulsar\Controllers\CronJobController@editRecord',                'resource' => 'admin-cron',             'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/cronjobs/update/{id}/{offset}',              ['as'=>'updateCronJob',         'uses'=>'Syscover\Pulsar\Controllers\CronJobController@updateRecord',              'resource' => 'admin-cron',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/cronjobs/delete/{id}/{offset}',              ['as'=>'deleteCronJob',         'uses'=>'Syscover\Pulsar\Controllers\CronJobController@deleteRecord',              'resource' => 'admin-cron',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/cronjobs/delete/select/records',          ['as'=>'deleteSelectCronJob',   'uses'=>'Syscover\Pulsar\Controllers\CronJobController@deleteRecordsSelect',       'resource' => 'admin-cron',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | LANGUAGES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/langs/{offset?}',                            ['as' => 'lang',                'uses' => 'Syscover\Pulsar\Controllers\LangController@index',                      'resource' => 'admin-lang',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/langs/json/data',                            ['as' => 'jsonDataLang',        'uses' => 'Syscover\Pulsar\Controllers\LangController@jsonData',                   'resource' => 'admin-lang',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/create/{offset}',                      ['as' => 'createLang',          'uses' => 'Syscover\Pulsar\Controllers\LangController@createRecord',               'resource' => 'admin-lang',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/store/{offset}',                      ['as' => 'storeLang',           'uses' => 'Syscover\Pulsar\Controllers\LangController@storeRecord',                'resource' => 'admin-lang',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/{id}/edit/{offset}',                   ['as' => 'editLang',            'uses' => 'Syscover\Pulsar\Controllers\LangController@editRecord',                 'resource' => 'admin-lang',             'action' => 'create']);
    Route::put(config('pulsar.appName') . '/pulsar/langs/update/{id}/{offset}',                 ['as' => 'updateLang',          'uses' => 'Syscover\Pulsar\Controllers\LangController@updateRecord',               'resource' => 'admin-lang',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/delete/{id}/{offset}',                 ['as' => 'deleteLang',          'uses' => 'Syscover\Pulsar\Controllers\LangController@deleteRecord',               'resource' => 'admin-lang',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/langs/delete/select/records',             ['as' => 'deleteSelectLang',    'uses' => 'Syscover\Pulsar\Controllers\LangController@deleteRecordsSelect',        'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/delete/image/lang/{id}',              ['as' => 'deleteImageLang',     'uses' => 'Syscover\Pulsar\Controllers\LangController@ajaxDeleteImage',            'resource' => 'admin-lang',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | COUNTRIES
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/countries/{lang}/{offset?}',                         ['as'=>'country',                   'uses'=>'Syscover\Pulsar\Controllers\CountryController@index',                    'resource' => 'admin-country',          'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/countries/json/data/{lang}',                         ['as'=>'jsonDataCountry',           'uses'=>'Syscover\Pulsar\Controllers\CountryController@jsonData',                 'resource' => 'admin-country',          'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/create/{lang}/{offset}/{id?}',             ['as'=>'createCountry',             'uses'=>'Syscover\Pulsar\Controllers\CountryController@createRecord',             'resource' => 'admin-country',          'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/store/{lang}/{offset}/{id?}',             ['as'=>'storeCountry',              'uses'=>'Syscover\Pulsar\Controllers\CountryController@storeRecord',              'resource' => 'admin-country',          'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/{id}/edit/{lang}/{offset}',                ['as'=>'editCountry',               'uses'=>'Syscover\Pulsar\Controllers\CountryController@editRecord',               'resource' => 'admin-country',          'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/countries/update/{lang}/{id}/{offset}',              ['as'=>'updateCountry',             'uses'=>'Syscover\Pulsar\Controllers\CountryController@updateRecord',             'resource' => 'admin-country',          'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/delete/{lang}/{id}/{offset}',              ['as'=>'deleteCountry',             'uses'=>'Syscover\Pulsar\Controllers\CountryController@deleteRecord',             'resource' => 'admin-country',          'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/countries/delete/translation/{lang}/{id}/{offset}',  ['as'=>'deleteTranslationCountry',  'uses'=>'Syscover\Pulsar\Controllers\CountryController@deleteTranslationRecord',  'resource' => 'admin-country',          'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/countries/delete/select/records/{lang}',          ['as'=>'deleteSelectCountry',       'uses'=>'Syscover\Pulsar\Controllers\CountryController@deleteRecordsSelect',      'resource' => 'admin-country',          'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/countries/json/country/{country?}',                 ['as'=>'jsonGetCountry',            'uses'=>'Syscover\Pulsar\Controllers\CountryController@jsonCountry',              'resource' => 'admin-country',          'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/countries/json/countries/{lang?}',                   ['as'=>'jsonGetCountries',          'uses'=>'Syscover\Pulsar\Controllers\CountryController@jsonCountries',            'resource' => 'admin-country',          'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | TERRITORIAL AREAS 1
    |--------------------------------------------------------------------------
    | URL jsonDataTerritorialArea1 has to go first to avoid enter in TerritorialArea1 when we call to jsonDataTerritorialArea1
    */
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas1/json/data/{country}/{parentOffset}/{offset?}',             ['as'=>'jsonDataTerritorialArea1',              'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@jsonData',                             'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas1/{country}/{parentOffset}/{offset?}',                       ['as'=>'territorialArea1',                      'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@index',                                'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas1/create/{country}/{parentOffset}/{offset}',                 ['as'=>'createTerritorialArea1',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@createRecord',                         'resource' => 'admin-country-at1',  'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/territorialareas1/store/{country}/{parentOffset}/{offset}',                 ['as'=>'storeTerritorialArea1',                 'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@storeRecord',                          'resource' => 'admin-country-at1',  'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas1/{id}/edit/{country}/{parentOffset}/{offset}',              ['as'=>'editTerritorialArea1',                  'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@editRecord',                           'resource' => 'admin-country-at1',  'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/territorialareas1/update/{country}/{parentOffset}/{id}/{offset}',            ['as'=>'updateTerritorialArea1',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@updateRecord',                         'resource' => 'admin-country-at1',  'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas1/delete/{country}/{parentOffset}/{id}/{offset}',            ['as'=>'deleteTerritorialArea1',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@deleteRecord',                         'resource' => 'admin-country-at1',  'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/territorialareas1/delete/select/records/{country}/{parentOffset}',        ['as'=>'deleteSelectTerritorialArea1',          'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@deleteRecordsSelect',                  'resource' => 'admin-country-at1',  'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/territorialareas1/json/from/country/{country?}',                            ['as'=>'jsonGetTerritorialArea1',               'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea1Controller@jsonTerritorialAreas1FromCountry',     'resource' => 'admin-country-at1',  'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | TERRITORIAL AREAS 2
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas2/json/data/{country}/{parentOffset}/{offset?}',             ['as'=>'jsonDataTerritorialArea2',              'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@jsonData',                                  'resource' => 'admin-country-at2',  'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas2/{country}/{parentOffset}/{offset?}',                       ['as'=>'territorialArea2',                      'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@index',                                     'resource' => 'admin-country-at2',  'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas2/create/{country}/{parentOffset}/{offset}',                 ['as'=>'createTerritorialArea2',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@createRecord',                              'resource' => 'admin-country-at2',  'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/territorialareas2/store/{country}/{parentOffset}/{offset}',                 ['as'=>'storeTerritorialArea2',                 'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@storeRecord',                               'resource' => 'admin-country-at2',  'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas2/{id}/edit/{country}/{parentOffset}/{offset}',              ['as'=>'editTerritorialArea2',                  'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@editRecord',                                'resource' => 'admin-country-at2',  'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/territorialareas2/update/{country}/{parentOffset}/{id}/{offset}',            ['as'=>'updateTerritorialArea2',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@updateRecord',                              'resource' => 'admin-country-at2',  'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas2/delete/{country}/{parentOffset}/{id}/{offset}',            ['as'=>'deleteTerritorialArea2',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@deleteRecord',                              'resource' => 'admin-country-at2',  'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/territorialareas2/delete/select/records/{country}/{parentOffset}',        ['as'=>'deleteSelectTerritorialArea2',          'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@deleteRecordsSelect',                       'resource' => 'admin-country-at2',  'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas2/json/from/territorialarea1/{country}/{id}',                ['as'=>'jsonTerritorialArea2',                  'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea2Controller@jsonTerritorialAreas2FromTerritorialArea1', 'resource' => 'admin-country-at2',  'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | TERRITORIAL AREAS 3
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas3/json/data/country/{country}/{parentOffset}/{offset?}',     ['as'=>'jsonDataTerritorialArea3',              'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@jsonData',                                     'resource' => 'admin-country-at3',  'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas3/{country}/{parentOffset}/{offset?}',                       ['as'=>'territorialArea3',                      'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@index',                                        'resource' => 'admin-country-at3',  'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas3/create/{country}/{parentOffset}/{offset}',                 ['as'=>'createTerritorialArea3',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@createRecord',                                 'resource' => 'admin-country-at3',  'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/territorialareas3/store/{country}/{parentOffset}/{offset}',                 ['as'=>'storeTerritorialArea3',                 'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@storeRecord',                                  'resource' => 'admin-country-at3',  'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas3/{id}/edit/{country}/{parentOffset}/{offset}',              ['as'=>'editTerritorialArea3',                  'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@editRecord',                                   'resource' => 'admin-country-at3',  'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/territorialareas3/update/{country}/{parentOffset}/{id}/{offset}',            ['as'=>'updateTerritorialArea3',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@updateRecord',                                 'resource' => 'admin-country-at3',  'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/territorialareas3/delete/{country}/{parentOffset}/{id}/{offset}',            ['as'=>'deleteTerritorialArea3',                'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@deleteRecord',                                 'resource' => 'admin-country-at3',  'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/territorialareas3/delete/select/records/{country}/{parentOffset}',        ['as'=>'deleteSelectTerritorialArea3',          'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@deleteRecordsSelect',                          'resource' => 'admin-country-at3',  'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/territorialareas3/json/from/territorialarea2/{country}/{id?}',               ['as'=>'jsonTerritorialArea3',                  'uses'=>'Syscover\Pulsar\Controllers\TerritorialArea3Controller@jsonTerritorialAreas3FromTerritorialArea2',    'resource' => 'admin-country-at3',  'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/users/{offset?}',                        ['as'=>'user',              'uses'=>'Syscover\Pulsar\Controllers\UserController@index',                'resource' => 'admin-user',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/users/json/data',                        ['as'=>'jsonDataUser',      'uses'=>'Syscover\Pulsar\Controllers\UserController@jsonData',             'resource' => 'admin-user',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/users/create/{offset}',                  ['as'=>'createUser',        'uses'=>'Syscover\Pulsar\Controllers\UserController@createRecord',         'resource' => 'admin-user',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/users/store/{offset}',                  ['as'=>'storeUser',         'uses'=>'Syscover\Pulsar\Controllers\UserController@storeRecord',          'resource' => 'admin-user',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/users/{id}/edit/{offset}',               ['as'=>'editUser',          'uses'=>'Syscover\Pulsar\Controllers\UserController@editRecord',           'resource' => 'admin-user',             'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/users/update/{id}/{offset}',             ['as'=>'updateUser',        'uses'=>'Syscover\Pulsar\Controllers\UserController@updateRecord',         'resource' => 'admin-user',             'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/users/delete/{id}/{offset}',             ['as'=>'deleteUser',        'uses'=>'Syscover\Pulsar\Controllers\UserController@deleteRecord',         'resource' => 'admin-user',             'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/users/delete/select/records',         ['as'=>'deleteSelectUser',  'uses'=>'Syscover\Pulsar\Controllers\UserController@deleteRecordsSelect',  'resource' => 'admin-user',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | EMAIL ACCOUNTS
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/email/accounts/{offset?}',                   ['as'=>'emailAccount',              'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@index',                  'resource' => 'admin-email-account',    'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/email/accounts/json/data',                   ['as'=>'jsonDataEmailAccount',      'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@jsonData',               'resource' => 'admin-email-account',    'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/email/accounts/create/{offset}',             ['as'=>'createEmailAccount',        'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@createRecord',           'resource' => 'admin-email-account',    'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/email/accounts/store/{offset}',             ['as'=>'storeEmailAccount',         'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@storeRecord',            'resource' => 'admin-email-account',    'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/email/accounts/{id}/edit/{offset}',          ['as'=>'editEmailAccount',          'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@editRecord',             'resource' => 'admin-email-account',    'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/email/accounts/update/{id}/{offset}',        ['as'=>'updateEmailAccount',        'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@updateRecord',           'resource' => 'admin-email-account',    'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/email/accounts/delete/{id}/{offset}',        ['as'=>'deleteEmailAccount',        'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@deleteRecord',           'resource' => 'admin-email-account',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/email/accounts/delete/select/records',    ['as'=>'deleteSelectEmailAccount',  'uses'=>'Syscover\Pulsar\Controllers\EmailAccountController@deleteRecordsSelect',    'resource' => 'admin-email-account',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | ATTACHMENT
    |--------------------------------------------------------------------------
    */
    Route::post(config('pulsar.appName') . '/pulsar/attachment/store/{object}/{lang}',          ['as'=>'storeAttachment',        'uses'=>'Syscover\Pulsar\Controllers\AttachmentController@storeAttachment',            'resource' => 'pulsar',    'action' => 'create']);
    Route::put(config('pulsar.appName') . '/pulsar/attachment/update/{object}/{lang}/{id}',     ['as'=>'updateAttachment',       'uses'=>'Syscover\Pulsar\Controllers\AttachmentController@apiUpdateAttachment',        'resource' => 'pulsar',    'action' => 'edit']);
    Route::put(config('pulsar.appName') . '/pulsar/attachment/update/{object}/{lang}',          ['as'=>'updatesAttachment',      'uses'=>'Syscover\Pulsar\Controllers\AttachmentController@apiUpdatesAttachment',       'resource' => 'pulsar',    'action' => 'edit']);
    Route::delete(config('pulsar.appName') . '/pulsar/attachment/delete/{lang}/{id}',           ['as'=>'deleteAttachment',       'uses'=>'Syscover\Pulsar\Controllers\AttachmentController@apiDeleteAttachment',        'resource' => 'pulsar',    'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/attachment/delete/tmp',                   ['as'=>'deleteTmpAttachment',    'uses'=>'Syscover\Pulsar\Controllers\AttachmentController@apiDeleteTmpAttachment',     'resource' => 'pulsar',    'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | LIBRARY
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/library/{offset?}',                 ['as'=>'attachmentLibrary',                   'uses'=>'Syscover\Pulsar\Controllers\AttachmentLibraryController@index',                  'resource' => 'admin-attachment-library',        'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/library/json/data',                 ['as'=>'jsonDataAttachmentLibrary',           'uses'=>'Syscover\Pulsar\Controllers\AttachmentLibraryController@jsonData',               'resource' => 'admin-attachment-library',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/library/create/{offset}',           ['as'=>'createAttachmentLibrary',             'uses'=>'Syscover\Pulsar\Controllers\AttachmentLibraryController@createRecord',           'resource' => 'admin-attachment-library',        'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/library/store/api',                ['as'=>'storeAttachmentLibrary',              'uses'=>'Syscover\Pulsar\Controllers\AttachmentLibraryController@storeAttachmentLibrary', 'resource' => 'admin-attachment-library',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/library/{id}/edit/{offset}',        ['as'=>'editAttachmentLibrary',               'uses'=>'Syscover\Pulsar\Controllers\AttachmentLibraryController@editRecord',             'resource' => 'admin-attachment-library',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/library/delete/{id}/{offset}',      ['as'=>'deleteAttachmentLibrary',             'uses'=>'Syscover\Pulsar\Controllers\AttachmentLibraryController@deleteRecord',           'resource' => 'admin-attachment-library',        'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/library/delete/select/records',  ['as'=>'deleteSelectAttachmentLibrary',       'uses'=>'Syscover\Pulsar\Controllers\AttachmentLibraryController@deleteRecordsSelect',    'resource' => 'admin-attachment-library',        'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | ATTACHMENT FAMILY
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/attachment/families/{offset?}',                  ['as'=>'attachmentFamily',                'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@index',                     'resource' => 'admin-attachment-family',        'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/attachment/families/json/data',                  ['as'=>'jsonDataAttachmentFamily',        'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@jsonData',                  'resource' => 'admin-attachment-family',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/attachment/families/create/{offset}',            ['as'=>'createAttachmentFamily',          'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@createRecord',              'resource' => 'admin-attachment-family',        'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/attachment/families/store/{offset}',            ['as'=>'storeAttachmentFamily',           'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@storeRecord',               'resource' => 'admin-attachment-family',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/attachment/families/{id}/edit/{offset}',         ['as'=>'editAttachmentFamily',            'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@editRecord',                'resource' => 'admin-attachment-family',        'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/attachment/families/update/{id}/{offset}',       ['as'=>'updateAttachmentFamily',          'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@updateRecord',              'resource' => 'admin-attachment-family',        'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/attachment/families/delete/{id}/{offset}',       ['as'=>'deleteAttachmentFamily',          'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@deleteRecord',              'resource' => 'admin-attachment-family',        'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/attachment/families/delete/select/records',   ['as'=>'deleteSelectAttachmentFamily',    'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@deleteRecordsSelect',       'resource' => 'admin-attachment-family',        'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/attachment/families/{id}/show/{api}',            ['as'=>'apiShowAttachmentFamily',         'uses'=>'Syscover\Pulsar\Controllers\AttachmentFamilyController@showRecord',                'resource' => 'admin-attachment-family',        'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | CONTENT BUILDER
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/{package}/{theme}/edit/{input}',              ['as'=>'contentbuilder',            'uses'=>'Syscover\Pulsar\Controllers\ContentBuilderController@index',         'resource' => 'admin',     'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/saveimage',                            ['as'=>'contentbuilderSaveImage',   'uses'=>'Syscover\Pulsar\Controllers\ContentBuilderController@saveImage',     'resource' => 'admin',     'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/contentbuilder/action/blocks/{theme}',                       ['as'=>'contentbuilderBlocks',      'uses'=>'Syscover\Pulsar\Controllers\ContentBuilderController@getBlocks',     'resource' => 'admin',     'action' => 'access']);

    /*
    |--------------------------------------------------------------------------
    | FIELD GROUP
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/custom/fields/groups/{offset?}',                  ['as'=>'customFieldGroup',                'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@index',                     'resource' => 'admin-field-group',        'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/custom/fields/groups/json/data',                  ['as'=>'jsonDataCustomFieldGroup',        'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@jsonData',                  'resource' => 'admin-field-group',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/groups/create/{offset}',            ['as'=>'createCustomFieldGroup',          'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@createRecord',              'resource' => 'admin-field-group',        'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/custom/fields/groups/store/{offset}',            ['as'=>'storeCustomFieldGroup',           'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@storeRecord',               'resource' => 'admin-field-group',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/groups/{id}/edit/{offset}',         ['as'=>'editCustomFieldGroup',            'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@editRecord',                'resource' => 'admin-field-group',        'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/custom/fields/groups/update/{id}/{offset}',       ['as'=>'updateCustomFieldGroup',          'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@updateRecord',              'resource' => 'admin-field-group',        'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/groups/delete/{id}/{offset}',       ['as'=>'deleteCustomFieldGroup',          'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@deleteRecord',              'resource' => 'admin-field-group',        'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/custom/fields/groups/delete/select/records',   ['as'=>'deleteSelectCustomFieldGroup',    'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@deleteRecordsSelect',       'resource' => 'admin-field-group',        'action' => 'delete']);
    Route::any(config('pulsar.appName') . '/pulsar/custom/fields/groups/{id}/show/{api}',            ['as'=>'apiShowCustomFieldGroup',         'uses'=>'Syscover\Pulsar\Controllers\CustomFieldGroupController@showRecord',                'resource' => 'admin-field-group',        'action' => 'access']);


    /*
    |--------------------------------------------------------------------------
    | FIELD
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/custom/fields/{lang}/{offset?}',                             ['as'=>'customField',                    'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@index',                      'resource' => 'admin-field',        'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/custom/fields/json/data/{lang}',                             ['as'=>'jsonDataCustomField',            'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@jsonData',                   'resource' => 'admin-field',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/create/{lang}/{offset}/{id?}',                 ['as'=>'createCustomField',              'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@createRecord',               'resource' => 'admin-field',        'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/custom/fields/store/{lang}/{offset}/{id?}',                 ['as'=>'storeCustomField',               'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@storeRecord',                'resource' => 'admin-field',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/{id}/edit/{lang}/{offset}',                    ['as'=>'editCustomField',                'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@editRecord',                 'resource' => 'admin-field',        'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/custom/fields/update/{lang}/{id}/{offset}',                  ['as'=>'updateCustomField',              'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@updateRecord',               'resource' => 'admin-field',        'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/delete/{lang}/{id}/{offset}',                  ['as'=>'deleteCustomField',              'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@deleteRecord',               'resource' => 'admin-field',        'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/delete/translation/{lang}/{id}/{offset}',      ['as'=>'deleteTranslationCustomField',   'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@deleteTranslationRecord',    'resource' => 'admin-field',        'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/custom/fields/delete/select/records/{lang}',              ['as'=>'deleteSelectCustomField',        'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@deleteRecordsSelect',        'resource' => 'admin-field',        'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/custom/fields/api/get/custom/fields',                       ['as'=>'apiGetCustomFields',             'uses'=>'Syscover\Pulsar\Controllers\CustomFieldController@apiGetCustomFields',         'resource' => 'admin-field',        'action' => 'access']);


    /*
    |--------------------------------------------------------------------------
    | FIELD VALUE
    |--------------------------------------------------------------------------
    */
    Route::any(config('pulsar.appName') . '/pulsar/custom/fields/values/{field}/{lang}/{offset?}',                          ['as'=>'customFieldValue',                    'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@index',                      'resource' => 'admin-field-value',        'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/custom/fields/values/json/data/{field}/{lang}',                          ['as'=>'jsonDataCustomFieldValue',            'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@jsonData',                   'resource' => 'admin-field-value',        'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/values/create/{field}/{lang}/{offset}/{id?}',              ['as'=>'createCustomFieldValue',              'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@createRecord',               'resource' => 'admin-field-value',        'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/custom/fields/values/store/{field}/{lang}/{offset}/{id?}',              ['as'=>'storeCustomFieldValue',               'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@storeRecord',                'resource' => 'admin-field-value',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/values/{field}/{id}/edit/{lang}/{offset}',                 ['as'=>'editCustomFieldValue',                'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@editRecord',                 'resource' => 'admin-field-value',        'action' => 'access']);
    Route::put(config('pulsar.appName') . '/pulsar/custom/fields/values/update/{field}/{lang}/{id}/{offset}',               ['as'=>'updateCustomFieldValue',              'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@updateRecord',               'resource' => 'admin-field-value',        'action' => 'edit']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/values/delete/{field}/{lang}/{id}/{offset}',               ['as'=>'deleteCustomFieldValue',              'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@deleteRecord',               'resource' => 'admin-field-value',        'action' => 'delete']);
    Route::get(config('pulsar.appName') . '/pulsar/custom/fields/values/delete/translation/{field}/{lang}/{id}/{offset}',   ['as'=>'deleteTranslationCustomFieldValue',   'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@deleteTranslationRecord',    'resource' => 'admin-field-value',        'action' => 'delete']);
    Route::delete(config('pulsar.appName') . '/pulsar/custom/fields/values/delete/select/records/{field}/{lang}',           ['as'=>'deleteSelectCustomFieldValue',        'uses'=>'Syscover\Pulsar\Controllers\CustomFieldValueController@deleteRecordsSelect',        'resource' => 'admin-field-value',        'action' => 'delete']);


    /*
    |--------------------------------------------------------------------------
    | FROALA
    |--------------------------------------------------------------------------
    */
    Route::post(config('pulsar.appName') . '/pulsar/froala/upload/image',                                       ['as'=>'froalaUploadImage',             'uses'=>'Syscover\Pulsar\Controllers\FroalaController@uploadImage',     'resource' => 'admin-field-value',        'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/froala/load/images/{package}',                               ['as'=>'froalaLoadImages',              'uses'=>'Syscover\Pulsar\Controllers\FroalaController@loadImages',      'resource' => 'admin-field-value',        'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/froala/delete/image',                                       ['as'=>'froalaDeleteImage',             'uses'=>'Syscover\Pulsar\Controllers\FroalaController@deleteImage',     'resource' => 'admin-field-value',        'action' => 'delete']);



    /*
    |--------------------------------------------------------------------------
    | ADDRESS
    |--------------------------------------------------------------------------
    */
//    Route::any(config('pulsar.appName') . '/pulsar/address/json/data/{resource}/{object}',              ['as'=>'jsonDataDirecciones',  'uses'=>'Syscover\Pulsar\Controllers\Direcciones@jsonData',      'resource' => 'admin-lang',             'action' => 'delete']);
//    Route::get(config('pulsar.appName') . '/pulsar/address/create/{resource}/{object}',                 ['as'=>'createDireccion',      'uses'=>'Syscover\Pulsar\Controllers\Direcciones@create',        'resource' => 'admin-lang',             'action' => 'delete']);
//    Route::post(config('pulsar.appName') . '/pulsar/address/store',                                     ['as'=>'storeDireccion',       'uses'=>'Syscover\Pulsar\Controllers\Direcciones@store',         'resource' => 'admin-lang',             'action' => 'delete']);
//    Route::get(config('pulsar.appName') . '/pulsar/address/{id}/edit/{offset}',                         ['as'=>'editDireccion',        'uses'=>'Syscover\Pulsar\Controllers\Direcciones@edit',          'resource' => 'admin-lang',             'action' => 'delete']);
//    Route::post(config('pulsar.appName') . '/pulsar/address/update/{offset}',                           ['as'=>'updateDireccion',      'uses'=>'Syscover\Pulsar\Controllers\Direcciones@update',        'resource' => 'admin-lang',             'action' => 'delete']);

    /*
    |--------------------------------------------------------------------------
    | GOOGLE SERVICES
    |--------------------------------------------------------------------------
    */
    //Route::any(config('pulsar.appName').'/pulsar/google/services',                                      ['as'=>'googleServices', 'uses'=>'Syscover\Pulsar\Controllers\GoogleServices@index',            'resource' => 'admin-lang',             'action' => 'delete']);
    //Route::post(config('pulsar.appName').'/pulsar/google/services/update',                              ['as'=>'updateGoogleServices', 'uses'=>'Syscover\Pulsar\Controllers\GoogleServices@update',            'resource' => 'admin-lang',             'action' => 'delete']);
});