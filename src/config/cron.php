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
    //'01' => '\Syscover\Vinipadsalesforcefrontend\Libraries\Cron::checkCallsToQueue',

    //Cron check citas Módulo de Cabinas
    //'02' => '\Syscover\Cabinas\Libraries\Cron::checkCitas',

    // cron para comprobar que hay correos de campañas por enviar a cola de envío o con persistencia activa
    '03' => '\Syscover\Comunik\Libraries\Cron::checkCampaignsToCreate',

    //Cron envios de emails
    '04' => '\Syscover\Comunik\Libraries\Cron::checkSendEmails',



    //Cron envios de sms
    //'05' => '\Syscover\Comunik\Libraries\Cron::checkCallQueueSendSms',

    //Cron vinipad
    //'06' => '\Syscover\Vinipadcuadernocata\Libraries\Cron::checkCallsToQueue',

    //Cron comprobación de correos rebotados y número de correos en cada cuenta
    '07' => ' \Syscover\Comunik\Libraries\Cron::checkBouncedEmailsAccounts',

    //Cron comprobación de correos rebotados y número de correos en cada cuenta
    '08' =>  '\Syscover\Forms\Libraries\Cron::checkMessageToSend'

];