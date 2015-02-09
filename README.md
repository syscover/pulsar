# Pulsar App para Laravel 5

Pulsar is an application that generates a control panel where you start creating custom solutions, provides the resources necessary for any web application.

---
- [Installation](#installation)


## Installation

**1 - Register service provider, on file config/app.php add to providers array**

```
'Pulsar\Pulsar\PulsarServiceProvider',

```

**2 - Register middleware auth.pulsar, on file app/Http/Kernel.php add to routeMiddleware array**

```
'auth.pulsar' 	=> 'Pulsar\Pulsar\Middleware\Auth',

```

**3 - To publish package, you must type on console**

```
php artisan vendor:publish

```




Pulsar es una aplicación que genera un panel de control donde empezar a crear soluciones personalizadas, provee de los recursos necesarios para cualquier aplicación web.

---
- [Instalación](#instalacion)
- [ACL Acces Colntrol List](#acl)
- [Gestión de usuarios](#usuarios)
- [Gestión de localización](#registering-the-package)
- [Gestón de tareas CRON](#cron)

**1 - Pulsar extiende varias clases del core del Laravel, para que estas extensiones sean cargadas por Laravel es necesario poner al inicio del fichero app/start/global.php el siguinte código:**



```
/*
|-------------------------------------------------------------------------
| Auth 
|--------------------------------------------------------------------------
|
| Extendemos el objeto Auth con cada acceso que deseemos tener independizado.
| De esta manera podemoa tener dos accesos usando tablas diferentes de usuarios para 
| varios paneles de control.
|
*/
Auth::extend('eloquent.pulsar', function(){
    return new Pulsar\Pulsar\Libraries\PulsarGuard(
            new Illuminate\Auth\EloquentUserProvider(
                    new Illuminate\Hashing\BcryptHasher, Illuminate\Support\Facades\Config::get('pulsar::auth.model')),
            App::make('session.store'),
            'pulsar');
});

Auth::extend('eloquent.vsf', function(){
    return new Pulsar\Pulsar\Libraries\PulsarGuard(
            new Illuminate\Auth\EloquentUserProvider(
                    new Illuminate\Hashing\BcryptHasher, Illuminate\Support\Facades\Config::get('vinipadsalesforcefrontend::auth.model')),
           App::make('session.store'),
           'vsf');
});
```


**2 - Configuración de logs:**
Dentro del fichero app/start/global.php deberemos sobreescribir la siguiente función para el correcto registro de logs
```
/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel_'.date('d-m-Y').'.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
        
        //cambiamos el fichero a permisos 777 para evitar conflictos entre el usuario de apache y de cron
        if(substr(sprintf('%o', fileperms(storage_path().'/logs/laravel_'.date('d-m-Y').'.log')), -4) != '0777'){
            chmod(storage_path().'/logs/laravel_'.date('d-m-Y').'.log', 0777);
        }
});
```


**3 - Configurar gestión de colas (queue):**
Pulsar requiere un servidor de gestión de colas, para hemos usado iron Mq, se ha de configurar en el fichero app/config/queue.php con los siguientes datos
```
return array(
	'default' => 'iron',
	'connections' => array(
            'iron' => array(
                    'driver'  => 'iron',
                    'project' => 'XXXXXXXXXXXXXXX', //Dato proporcionado por iron.io
                    'token'   => 'XXXXXXXXXXXXXXX', //Dato proporcionado por iron.io
                    'queue'   => 'app', //nombre de la cola de mensajes por defecto
                    'encrypt' => true,
            )
	),
```

**4 - Debemos añadir a nuestro fichero composer.json, la propiedad repositories en caso de no existir con las siguientes líneas:
´´´
"repositories": [
    { "type": "composer", "url": "http://packages.syscover.com/" }
],
´´´


**5 - Asegurarse que en el fichero composer.json el valor "minimum-stability" es igual a "dev", de lo contrario cambiarlo.
´´´
"minimum-stability": "dev"
´´´


**6 - Debemos añadir a nuestro fichero composer.json, dentro de la propiedad requir las siguientes líneas:

```
"pulsar/pulsar": "dev-master"
```


**7 - Esto datos es para configurar tu proyecto laravel, usa los siguientes valores en el fichero app/config/app.php los valores:
´´´
'url'       => 'http://midominio.com',
'timezone'  => 'Europe/Madrid',
'locale'    => 'es',
´´´


**8 - En el fichero app/config/app.php añadir al array provider el valor:
´´´
'Pulsar\Pulsar\PulsarServiceProvider',
´´´


**9 - Configurar datos de acceso a la base de datos en el fichero app/config/database.php**


**10 - Ejecutar las migraciones para cargar la base de datos**
```
php artisan migrate --package="pulsar/pulsar"
```
si lo estamos haciendo desde el workbench sería
```
php artisan migrate --bench=pulsar/pulsar
```


**11 - Ejecutar los seeds para poblar la base de datos**
```
php artisan db:seed --class="LangTableSeeder"
php artisan db:seed --class="PaisTableSeeder"
php artisan db:seed --class="AreaTerritorial1TableSeeder"
php artisan db:seed --class="AreaTerritorial2TableSeeder"
php artisan db:seed --class="AccionTableSeeder"
php artisan db:seed --class="ModuloTableSeeder"
php artisan db:seed --class="PerfilTableSeeder"
php artisan db:seed --class="RecursoTableSeeder"
php artisan db:seed --class="UsuarioTableSeeder"
php artisan db:seed --class="PermisoTableSeeder"
```


**12 - Publicar los assets del package, usar el siguiente comando:**
```
php artisan asset:publish pulsar/pulsar
```
si lo estamos haciendo desde el workbench sería
```
php artisan asset:publish --bench="pulsar/pulsar"
```


**13 - Hay que dar permisos de escritura (0777) a las carpetas:**

```
app/storage
public/packages/pulsar/pulsar/storage
```


**14 - Resgistrar el comando cron para ejecutar las tareas programadas**
Para registrar el comando cron dentro de laravel devemos acceder al fichero app/start/artisan.php y registrar el método:
```
Artisan::add(new Pulsar\Pulsar\Commands\CronCommand);
```
Para asegurarnos que nuestro comando cron funciona correctamente podemos ejecutar la siguietne linea desde el directorio de instalación de laravel
```
php artisan cron --v
```
Nos debería devolver la versión del módulo cron.


**15 - Configurar un proceso cron con una llamada a la siguiente URL, recomendamos configurarlo cada 5 minutas (*/5 * * * *)**
```
php -q /var/www/vhosts/midominio.com/httpdocs/artisan cron
```



**Posibles problemas**

Puede que nuestros archivos dentro de la caprta public no se visualizen (css, js, etc.) esto puede ser debido a que en vuestro servidor tengais instalado un proxy como Nginx.
Debeis modificar la



(Revisar)
**Opcional - En el caso de no disponer de un panel de gestión para configurar nuestra tarea cron, tendríamos que realizarlo por medio de consola

## Cron
Para la implementación del sistema cron debemos seguir los siguietnes pasos:


### Instanciar el cron en nuestro servidor

Para ello necesitaremos instanciar en nuestro servidor una única tarea cron que se encargará de revisar si tiene que disparar algún comando, normalmente con el comando /usr/bin/php y apuntando 
a la ruta absoluta del fichero artisan que se debe de encontrar en la raiz de nuestro proyecto web.
La opción -q es para evitar escritura por consola del cron

```
* * * * * /usr/bin/php -q /ruta/absoluta/a/nuestra/carpeta/raiz/artisan cron
``` 

Para editar nuestro fichero crontab para añadir la tarea, podemos hacerlo con el siguiente comando
```
# crontab -e
```

O si queremos editar el crontab de un usuario en concreto
```
# crontab -e -u usertoedit
```

### Nuestra primera tarea cron

Desde el apartado Tareas Cron podremos configurar las tareas necesarias que nuestro panel requiera ejecutar, nos encontraremos los siguientes campos:

Nombre: Descripción de la tarea cron.

Módulo: Módulo al que pertenece la tarea cron que vamos a sentenciar.

Expresión Cron: 
Periodicidad de cada tarea mediante una expresión que representará el tiempo de ejecución:

```
    *    *    *    *    *    *
    -    -    -    -    -    -
    |    |    |    |    |    |
    |    |    |    |    |    + Año [opcional]
    |    |    |    |    +----- día de la semana (0 - 7) (Sunday=0 or 7)
    |    |    |    +---------- mes (1 - 12)
    |    |    +--------------- día del mes (1 - 31)
    |    +-------------------- hora (0 - 23)
    +------------------------- minuto (0 - 59)

```

Activa: Indicamos si nuestra tarea queremos que está activa o no.

Key: Código de tarea a ejecutar, este código lo instanciamos nosotros mismos en el fichero src/config/cron.php que contiene un array de claves y fuciones

```
    return array(
    //Cron alarmas Vinipad Sales Force
    '01'       => function() { 
                            \Pulsar\Pulsar\Libraries\Cron::llamadaCron(); 
                        }
);
```
En este caso, instanciaríamos con 01 la key, si queremos que ejecute el método llamadaCron() de la clase estática Cron. 
