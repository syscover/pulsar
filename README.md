# Pulsar App para Laravel 5

Pulsar is an application that generates a control panel where you start creating custom solutions, provides the resources necessary for any web application.

---
- [Installation](#installation)


## Installation

**1 - After install Laravel framework, insert on file composer.json, inside require object this value**
```
"syscover/pulsar": "dev-master"

```

**2 - Register service provider, on file config/app.php add to providers array**

```
'Syscover\Pulsar\PulsarServiceProvider',

```

**3 - Register alias, on file config/app.php add to aliases array**

```
'Miscellaneous'	=> 'Syscover\Pulsar\Libraries\Miscellaneous',

```

**4 - Register middlewares auth.pulsar, locale.pulsar and permission.pulsar on file app/Http/Kernel.php add to routeMiddleware array**

```
'auth.pulsar' 			=> 'Syscover\Pulsar\Middleware\Auth',
'locale.pulsar'         => 'Syscover\Pulsar\Middleware\Locale',
'permission.pulsar' 	=> 'Syscover\Pulsar\Middleware\Permission',

```

**5 - Config file config/database.php with your database parameters connections**

**6 - To publish package, you must type on console**

```
php artisan vendor:publish --force

```

**7 - Optimized class loader**

```
php artisan optimize

```

**8 - Run migrate database**

```
php artisan migrate
```

**9 - Run seed database**

```
php artisan db:seed
```

**10 - When the installation is complete you can access these data**
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
