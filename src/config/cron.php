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

    // Cron alarmas Vinipad Sales Force
    //'01' => '\Syscover\Vinipadsalesforcefrontend\Libraries\Cron::checkCallsToQueue',

    // Cron check citas Módulo de Cabinas
    //'02' => '\Syscover\Cabinas\Libraries\Cron::checkCitas',

    // Cron para comprobar que hay correos de campañas por enviar a cola de envío o con persistencia activa
    '03' => '\Syscover\Comunik\Libraries\Cron::checkCampaignsToCreate',

    // Cron envios de emails
    '04' => '\Syscover\Comunik\Libraries\Cron::checkSendEmails',

    // Cron envios de sms
    //'05' => '\Syscover\Comunik\Libraries\Cron::checkCallQueueSendSms',

    // Cron vinipad
    //'06' => '\Syscover\Vinipadcuadernocata\Libraries\Cron::checkCallsToQueue',

    // Cron comprobación de correos rebotados y número de correos en cada cuenta
    '07' => '\Syscover\Comunik\Libraries\Cron::checkBouncedEmailsAccounts',

    // Cron comprobación de correos rebotados y número de correos en cada cuenta
    '08' =>  '\Syscover\Forms\Libraries\Cron::checkMessageToSend',
    
    // Cron comprobación de correos rebotados y número de correos en cada cuenta
    '09' =>  '\Syscover\Booking\Libraries\Cron::checkVouchersToCreate',

    // Cron to create advanced search exports
    '10' =>  '\Syscover\Pulsar\Libraries\Cron::checkAdvancedSearchExports',

    // Cron to delivery schedule reports
    '11' =>  '\Syscover\Pulsar\Libraries\Cron::checkReportTaskDelivery',
];