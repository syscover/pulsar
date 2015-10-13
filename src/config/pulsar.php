<?php

return [
    // app name and url access to application
    'appName'                   => 'pulsar',
	
    // html error delimiter
    'errorDelimiters'           =>  '<label class="has-error help-block" for="req1" generated="true">:message</label>',

    // default html error delimite to common errors
    'globalErrorDelimiters'     =>  '<div class="alert alert-danger fade in"><i class="icon-remove close" data-dismiss="alert"></i><strong>Error!</strong> :message</div>',

    //******************************************************************************************************************
    //***   Date pattern
    //***   Pattern     Description                                             Values
    //***   d           Day of the month, 2 digits with leading zeros	        01 to 31
    //***   m           Numeric representation of a month, with leading zeros   01 through 12
    //***   Y           A full numeric representation of a year, 4 digits       Examples: 1999 or 2015
    //***   y           A two digit representation of a year                    Examples: 99 or 03
    //***
    //***   To get all patterns see  http://php.net/manual/en/function.date.php
    //***
    //******************************************************************************************************************
    'datePattern'               => 'd-m-Y',

    // app name and url access to application
    'froalaEditorKey'           => env('FROALA_EDITOR_KEY', 'your editor key'),
];