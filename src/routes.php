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
    Route::any(config('pulsar.appName') . '/pulsar/langs/{page?}',                              ['as' => 'langs',               'uses' => 'Pulsar\Pulsar\Controllers\Langs@index',                      'resource' => 'admin-lang',             'action' => 'access']);
    Route::any(config('pulsar.appName') . '/pulsar/langs/json/data',                            ['as' => 'jsonDataLangs',       'uses' => 'Pulsar\Pulsar\Controllers\Langs@jsonData',                   'resource' => 'admin-lang',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/create/{page}',                        ['as' => 'createLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@createRecord',               'resource' => 'admin-lang',             'action' => 'create']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/store/{page}',                        ['as' => 'storeLang',           'uses' => 'Pulsar\Pulsar\Controllers\Langs@store',                      'resource' => 'admin-lang',             'action' => 'create']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/{id}/edit/{page}',                     ['as' => 'editLang',            'uses' => 'Pulsar\Pulsar\Controllers\Langs@editRecord',                 'resource' => 'admin-lang',             'action' => 'access']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/update/{page}',                       ['as' => 'updateLang',          'uses' => 'Pulsar\Pulsar\Controllers\Langs@update',                     'resource' => 'admin-lang',             'action' => 'access']);
    Route::get(config('pulsar.appName') . '/pulsar/langs/destroy/{id}',                         ['as' => 'destroyLang',         'uses' => 'Pulsar\Pulsar\Controllers\Langs@destroyRecord',              'resource' => 'admin-lang',             'action' => 'edit']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/destroy/select/elements',             ['as' => 'destroySelectLang',   'uses' => 'Pulsar\Pulsar\Controllers\Langs@destroyRecordsSelect',       'resource' => 'admin-lang',             'action' => 'delete']);
    Route::post(config('pulsar.appName') . '/pulsar/langs/delete/image/lang/{id}',               ['as' => 'deleteImageLang',     'uses' => 'Pulsar\Pulsar\Controllers\Langs@ajaxDeleteImage',           'resource' => 'admin-lang',             'action' => 'delete']);

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

    /*
    |--------------------------------------------------------------------------
    | COUNTRIES
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/{page?}',                            array('as'=>'countries',             'uses'=>'Pulsar\Pulsar\Controllers\Countries@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/json/data',                          array('as'=>'jsonDataCountries',     'uses'=>'Pulsar\Pulsar\Controllers\Countries@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/create/{page}/{lang}/{country?}',    array('as'=>'createCountry',         'uses'=>'Pulsar\Pulsar\Controllers\Countries@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/store/{page}',                      array('as'=>'storeCountry',          'uses'=>'Pulsar\Pulsar\Controllers\Countries@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/{id}/edit/{lang}/{page}',            array('as'=>'editCountry',           'uses'=>'Pulsar\Pulsar\Controllers\Countries@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/update/{page}',                     array('as'=>'updateCountry',         'uses'=>'Pulsar\Pulsar\Controllers\Countries@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/destroy/{id}',                       array('as'=>'destroyCountry',        'uses'=>'Pulsar\Pulsar\Controllers\Countries@destroy'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/destroy/lang/{id}/{lang}/{page}',    array('as'=>'destroyCountryLang',    'uses'=>'Pulsar\Pulsar\Controllers\Countries@destroyLang'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/destroy/select/elements',           array('as'=>'destroySelectCountry',  'uses'=>'Pulsar\Pulsar\Controllers\Countries@destroySelect'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/countries/json/get_pais/{country?}',          array('as'=>'jsonGetCountry',        'uses'=>'Pulsar\Pulsar\Controllers\Countries@jsonGetCountry'));

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 1
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/{country}/{page?}', array('as'=>'areasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/json/data/pais/{country}', array('as'=>'jsonDataAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/create/{country}/{page}', array('as'=>'createAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/store/{country}/{page}', array('as'=>'storeAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/{id}/edit/{page}', array('as'=>'editAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/update/{country}/{page}', array('as'=>'updateAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/destroy/{country}/{id}', array('as'=>'destroyAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@destroy'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/destroy/select/elements/{country}', array('as'=>'destroySelectAreasTerritoriales1', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@destroySelect'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales1/json/get_areas_territoriales_1_from_pais/{country?}', array('as'=>'jsonGetAreasTerritoriales1FromPais', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales1@jsonGetAreasTerritoriales1FromPais'));

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 2
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/{country}/{page?}', array('as'=>'areasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/json/data/pais/{country}', array('as'=>'jsonDataAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/create/{country}/{page}', array('as'=>'createAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/store/{country}/{page}', array('as'=>'storeAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/{id}/edit/{page}', array('as'=>'editAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/update/{country}/{page}', array('as'=>'updateAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/destroy/{country}/{id}', array('as'=>'destroyAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@destroy'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/destroy/select/elements/{country}', array('as'=>'destroySelectAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@destroySelect'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales2/json/get_areas_territoriales_2_from_area_territorial_1/{id}', array('as'=>'jsonAreasTerritoriales2', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales2@jsonGetAreasTerritoriales2FromAreaTerritorial1'));

    /*
    |--------------------------------------------------------------------------
    | AREAS TERRITORIALES 3
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/{country}/{page?}', array('as'=>'areasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/json/data/pais/{country}', array('as'=>'jsonDataAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/create/{country}/{page}', array('as'=>'createAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/store/{country}/{page}', array('as'=>'storeAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/{id}/edit/{page}', array('as'=>'editAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/update/{country}/{page}', array('as'=>'updateAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/destroy/{country}/{id}', array('as'=>'destroyAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@destroy'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/destroy/select/elements/{country}', array('as'=>'destroySelectAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@destroySelect'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/areasterritoriales3/json/get_areas_territoriales_3_from_area_territorial_2/{id}', array('as'=>'jsonAreasTerritoriales3', 'uses'=>'Pulsar\Pulsar\Controllers\AreasTerritoriales3@jsonGetAreasTerritoriales3FromAreaTerritorial2'));

    /*
    |--------------------------------------------------------------------------
    | PACKAGES
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/{page?}',                   array('as'=>'packages',              'uses'=>'Pulsar\Pulsar\Controllers\Packages@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/json/data',                 array('as'=>'jsonDataPackages',      'uses'=>'Pulsar\Pulsar\Controllers\Packages@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/create/{page}',             array('as'=>'createPackage',         'uses'=>'Pulsar\Pulsar\Controllers\Packages@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/store/{page}',             array('as'=>'storePackage',          'uses'=>'Pulsar\Pulsar\Controllers\Packages@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/{id}/edit/{page}',          array('as'=>'editPackage',           'uses'=>'Pulsar\Pulsar\Controllers\Packages@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/update/{page}',            array('as'=>'updatePackage',         'uses'=>'Pulsar\Pulsar\Controllers\Packages@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/destroy/{id}',              array('as'=>'destroyPackage',        'uses'=>'Pulsar\Pulsar\Controllers\Packages@destroy'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/packages/destroy/select/elements',  array('as'=>'destroySelectPackage',  'uses'=>'Pulsar\Pulsar\Controllers\Packages@destroySelect'));

    /*
    |--------------------------------------------------------------------------
    | PERFILES
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/{page?}',                                array('as'=>'perfiles', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/json/data',                              array('as'=>'jsonDataPerfiles', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/create/{page}',                          array('as'=>'createPerfil', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/store/{page}',                          array('as'=>'storePerfil', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/{id}/edit/{page}',                       array('as'=>'editPerfil', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/update/{page}',                         array('as'=>'updatePerfil', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/destroy/{id}',                           array('as'=>'destroyPerfil', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@destroy'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/perfiles/destroy/select/elements',               array('as'=>'destroySelectPerfil', 'uses'=>'Pulsar\Pulsar\Controllers\Perfiles@destroySelect'));

    /*
    |--------------------------------------------------------------------------
    | PERMISOS
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/permisos/{perfil}/{inicioPerfl?}/{inicio?}',      array('as'=>'permisos',             'uses'=>'Pulsar\Pulsar\Controllers\Permisos@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/permisos/json/data/perfil/{perfil}',              array('as'=>'jsonDataPermisos',     'uses'=>'Pulsar\Pulsar\Controllers\Permisos@jsonData'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/permisos/json/create/{num}/{num1}/{any}',        array('as'=>'jsonCreatePermiso',    'uses'=>'Pulsar\Pulsar\Controllers\Permisos@jsonCreate'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/permisos/json/destroy/{num}/{num1}/{any}',       array('as'=>'jsonDestroyPermiso',   'uses'=>'Pulsar\Pulsar\Controllers\Permisos@jsonDestroy'));

    /*
    |--------------------------------------------------------------------------
    | USUARIO
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/{page?}',                    array('as'=>'usuarios', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/json/data',                  array('as'=>'jsonDataUsuarios', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/create/{page}',              array('as'=>'createUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/store/{page}',              array('as'=>'storeUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/{id}/edit/{page}',           array('as'=>'editUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/update/{page}',             array('as'=>'updateUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/destroy/{id}',               array('as'=>'destroyUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@destroy'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/usuarios/destroy/select/elements',   array('as'=>'destroySelectUsuario', 'uses'=>'Pulsar\Pulsar\Controllers\Usuarios@destroySelect'));


    /*
    |--------------------------------------------------------------------------
    | TAREAS CRON
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/{page?}',                                 array('as'=>'cronJobs',                     'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@index'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/{id}/run/{page}',                         array('as'=>'runCronJob',                   'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@run'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/json/data',                               array('as'=>'jsonDataCronJobs',             'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/create/{page}',                           array('as'=>'createCronJob',                'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/store/{page}',                           array('as'=>'storeCronJob',                 'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/{id}/edit/{page}',                        array('as'=>'editCronJob',                  'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/update/{page}',                          array('as'=>'updateCronJob',                'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@update'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/destroy/{id}',                            array('as'=>'destroyCronJob',               'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@destroy'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/cron/jobs/destroy/select/elements',                array('as'=>'destroySelectTareaCronJob',    'uses'=>'Pulsar\Pulsar\Controllers\CronJobs@destroySelect'));

    /*
    |--------------------------------------------------------------------------
    | DIRECCIONES
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/direcciones/json/data/{resource}/{object}',         array('as'=>'jsonDataDirecciones',  'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@jsonData'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/direcciones/create/{resource}/{object}',            array('as'=>'createDireccion',      'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@create'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/direcciones/store',                                array('as'=>'storeDireccion',       'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@store'));
    Route::get(Config::get('pulsar::pulsar.rootUri') . '/pulsar/direcciones/{id}/edit/{page}',                      array('as'=>'editDireccion',        'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@edit'));
    Route::post(Config::get('pulsar::pulsar.rootUri') . '/pulsar/direcciones/update/{page}',                        array('as'=>'updateDireccion',      'uses'=>'Pulsar\Pulsar\Controllers\Direcciones@update'));

    /*
    |--------------------------------------------------------------------------
    | GOOGLE SERVICES
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri').'/pulsar/google/services',                                     array('as'=>'googleServices', 'uses'=>'Pulsar\Pulsar\Controllers\GoogleServices@index'));
    Route::post(Config::get('pulsar::pulsar.rootUri').'/pulsar/google/services/update',                             array('as'=>'updateGoogleServices', 'uses'=>'Pulsar\Pulsar\Controllers\GoogleServices@update'));

    /*
    |--------------------------------------------------------------------------
    | CONTENT BUILDER
    |--------------------------------------------------------------------------
    */
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/contentbuilder/{theme}/edit/{input}',               array('as'=>'contentbuilderEdit', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@index'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/contentbuilder/action/saveimage',                   array('as'=>'contentbuilderSaveImage', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@saveImage'));
    Route::any(Config::get('pulsar::pulsar.rootUri') . '/pulsar/contentbuilder/action/blocks/{theme}',              array('as'=>'contentbuilderBlocks', 'uses'=>'Pulsar\Pulsar\Controllers\ContentBuilder@getBlocks'));


});

// LOGIN
Route::post(config('pulsar.appName') . '/pulsar/login',                                     ['as'=>'login',                     'uses'=>'Pulsar\Pulsar\Controllers\Login@login']);
Route::get(config('pulsar.appName') . '/pulsar/login',                                      ['as'=>'loginView',                 'uses'=>'Pulsar\Pulsar\Controllers\Login@loginView']);

// PASSWORD REMINDER
Route::post(config('pulsar.appName') . '/pulsar/password/remind',                           ['as'=>'postRemindPassword',        'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postRemind']);
Route::get(config('pulsar.appName') . '/pulsar/password/reset/{token}',                     ['as'=>'getResetPassword',          'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@getReset']);
Route::post(config('pulsar.appName') . '/pulsar/password/reset/{token}',                    ['as'=>'postResetPassword',         'uses'=>'Pulsar\Pulsar\Controllers\RemindersController@postReset']);