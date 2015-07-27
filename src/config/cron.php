<?php

return [
    /*
    |--------------------------------------------------------------------------
    | cron
    |--------------------------------------------------------------------------
    |
    | Array que contiene las posibles llamadas al cron
    |
    */
    //Cron alarmas Vinipad Sales Force
    '01'       => function() { 
        \Syscover\Vinipadsalesforcefrontend\Libraries\Cron::checkCallsToQueue();
    },
    //Cron check citas Módulo de Cabinas
    '02'       => function() { 
        \Syscover\Cabinas\Libraries\Cron::checkCitas();
    },
    //Cron envios de emails
    '03'       => function() { 
        \Syscover\Comunik\Libraries\Cron::checkCallQueueSendEmails();
    },
    //Cron comprobación de la llamadas que mandaremos a cola para comprobar nuevos emails para cola de envío
    '04'       => function() { 
        \Syscover\Comunik\Libraries\Cron::checkCallsToQueue();
    },
    //Cron envios de sms
    '05'       => function() { 
        \Syscover\Comunik\Libraries\Cron::checkCallQueueSendSms();
    },
    //Cron vinipad
    '06'       => function() { 
        \Syscover\Vinipadcuadernocata\Libraries\Cron::checkCallsToQueue();
    },
    //Cron comprobación de correos rebotados y número de correos en cada cuenta
    '07'       => function() {
        \Syscover\Comunik\Libraries\Cron::checkBouncedEmailsAccountsToQueue();
    },
    //Cron comprobación de correos a enviar recibidos en el froms
    '08'       => function() {
        \Syscover\Forms\Libraries\Cron::checkMessageToSend();
    }
];