# Pulsar App for Laravel 5.2

[![Total Downloads](https://poser.pugx.org/syscover/pulsar/downloads)](https://packagist.org/packages/syscover/pulsar)

Pulsar is an application that generates a control panel where you start creating custom solutions, provides the resources necessary for any web application.

---

## Installation

**1 - After install Laravel framework, insert on file composer.json, inside require object this value**
```
"syscover/pulsar": "dev-master"

```
execute on console:
```
composer update
```

**2 - Register service provider, on file config/app.php add to providers array**

```
/*
 * Thirdparty Application Service Providers...
 */
Maatwebsite\Excel\ExcelServiceProvider::class,

/*
 * Pulsar Application Service Providers...
 */
Syscover\Pulsar\PulsarServiceProvider::class,

```

**3 - Register alias, on file config/app.php add to aliases array**

```
'Miscellaneous'	=> Syscover\Pulsar\Libraries\Miscellaneous::class,

```

**4 - Register middlewares auth.pulsar, locale.pulsar and permission.pulsar on file app/Http/Kernel.php add to routeMiddleware array**

```
'pulsar.auth' 	        => \Syscover\Pulsar\Middleware\Authenticate::class,
'pulsar.locale'         => \Syscover\Pulsar\Middleware\Locale::class,
'pulsar.permission' 	=> \Syscover\Pulsar\Middleware\Permission::class,
'pulsar.https'          => \Syscover\Pulsar\Middleware\HttpsProtocol::class,

```

also you must to add inside $middlewareGroups array this values:

```
'noCsrWeb' => [
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
],

'pulsar' => [
    \Syscover\Pulsar\Middleware\Authenticate::class,
    \Syscover\Pulsar\Middleware\Locale::class,
    \Syscover\Pulsar\Middleware\Permission::class,
],
```

**5 - Register cron command on file app/Console/Kernel.php add to $commands array**

```
\Syscover\Pulsar\Commands\Cron::class,

```

**6 - Change on file config/mail.php this line**

```
'from' => ['address' => null, 'name' => null],
```

for that

```
'from' => ['address' => env('MAIL_ADDRESS'), 'name' => env('MAIL_NAME')],
```

**7 - include this arrays in config/auth.php**

Inside guards array
```
'pulsar' => [
    'driver'    => 'session',
    'provider'  => 'pulsarUser',
],
```

Inside providers array
```
'pulsarUser' => [
    'driver'    => 'eloquent',
    'model'     => Syscover\Pulsar\Models\User::class,
],
```

Inside passwords array
```
'pulsarPasswordBroker' => [
    'provider'  => 'pulsarUser',
    'email'     => 'pulsar::emails.password',
    'table'     => '001_021_password_resets',
    'expire'    => 60,
],
```

**8 - Config .env file with your database parameters connections and this example parameters**
```
APP_URL=http://mydomain.local
APP_LOG=daily

MAIL_ADDRESS=info@mydomain.local
MAIL_NAME="MY DOMAIN"
```

**9 - setup your composer.json, to updates and installation pulsar package, replace post-update-cmd for this code**

```
"post-update-cmd": [
    "Illuminate\\Foundation\\ComposerScripts::postUpdate",
    "php artisan vendor:publish --force",
    "php artisan migrate",
    "php artisan migrate --path=database/migrations/updates",
    "php artisan optimize"
]

```

**10 - execute on console:**
```
composer update
```

**11 - Run seed database**

```
php artisan db:seed --class="PulsarTableSeeder"
```

**12 - When the installation is complete you can access these data**
```
url: http://www.your-domain.com/pulsar
user: admin@pulsar.local
pasword: 123456
```

## Cron task
To implement the cron system must follow the following steps:


### set cron on our server

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