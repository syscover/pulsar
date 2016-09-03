<?php

return [
    // app name and url access to application
    'name'                      => 'pulsar',

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

    // pattern to number format
    'decimalSeparator'          => ',',
    'thousandSeparator'         => '.',

    // app name and url access to application
    'froalaEditorKey'           => env('FROALA_EDITOR_KEY', 'your editor key'),

    // app name and url access to application
    'advancedSearchLimitLength' => env('ADVANCED_SEARCH_LIMIT_LENGTH', 100),

    //******************************************************************************************************************
    //***   Type gender
    //******************************************************************************************************************
    'genres'                    => [
        (object)['id' => 1,    'name' => 'pulsar::pulsar.male'],
        (object)['id' => 2,    'name' => 'pulsar::pulsar.female']
    ],

    //******************************************************************************************************************
    //***   Type treatments
    //******************************************************************************************************************
    'treatments'                => [
        (object)['id' => 1,    'name' => 'pulsar::pulsar.mr'],
        (object)['id' => 2,    'name' => 'pulsar::pulsar.mrs'],
        (object)['id' => 3,    'name' => 'pulsar::pulsar.ms'],
    ],

    //******************************************************************************************************************
    //***   Type states
    //******************************************************************************************************************
    'states'                    => [
        (object)['id' => 1,    'name' => 'pulsar::pulsar.single'],
        (object)['id' => 2,    'name' => 'pulsar::pulsar.married'],
        (object)['id' => 3,    'name' => 'pulsar::pulsar.divorced'],
        (object)['id' => 4,    'name' => 'pulsar::pulsar.widower'],
    ],

    //******************************************************************************************************************
    //***   Resources that can contain attachments
    //******************************************************************************************************************
    'resourcesAttachments'     => [
        'cms-article',
        'market-product',
        'hotels-hotel',
        'crm-customer',
        'spas-spa',
        'wineries-winery',
    ],

    //******************************************************************************************************************
    //***   Type fields to select on fields section
    //******************************************************************************************************************
    'fieldTypes'                => [
        (object)['id' => 1,    'key' => 'text',             'name' => 'Text',               'view' => 'pulsar::includes.html.form_text_group'],
        (object)['id' => 2,    'key' => 'select',           'name' => 'Select',             'view' => 'pulsar::includes.html.form_select_group'],
        (object)['id' => 3,    'key' => 'selectMultiple',   'name' => 'Select multiple',    'view' => 'pulsar::includes.html.form_select_group'],
        (object)['id' => 4,    'key' => 'number',           'name' => 'Number',             'view' => 'pulsar::includes.html.form_text_group'],
        (object)['id' => 5,    'key' => 'email',            'name' => 'Email',              'view' => 'pulsar::includes.html.form_text_group'],
        (object)['id' => 6,    'key' => 'checkbox',         'name' => 'Checkbox',           'view' => 'pulsar::includes.html.form_checkbox_group'],
        (object)['id' => 7,    'key' => 'select2',          'name' => 'Select 2',           'view' => 'pulsar::includes.html.form_select_group'],
        (object)['id' => 8,    'key' => 'selectMultiple2',  'name' => 'Select multiple 2',  'view' => 'pulsar::includes.html.form_select_group'],
        (object)['id' => 9,    'key' => 'textarea',         'name' => 'Text Area',          'view' => 'pulsar::includes.html.form_textarea_group'],
        (object)['id' => 10,   'key' => 'wysiwyg',          'name' => 'Wysiwyg',            'view' => 'pulsar::includes.html.form_wysiwyg_group'],
    ],

    //******************************************************************************************************************
    //***   Type data to select on fields section
    //******************************************************************************************************************
    'dataTypes'                 => [
        (object)['id' => 1,      'name' => 'String',            'type' => 'string'],
        (object)['id' => 2,      'name' => 'Boolean',           'type' => 'boolean'],
        (object)['id' => 3,      'name' => 'Integer',           'type' => 'integer'],
        (object)['id' => 4,      'name' => 'Float',             'type' => 'float'],
        (object)['id' => 5,      'name' => 'Array',             'type' => 'array'],
        (object)['id' => 6,      'name' => 'Object',            'type' => 'object'],
    ],

    //******************************************************************************************************************
    //***   Resources that can contain custom fields
    //******************************************************************************************************************
    'resourcesCustomFields'     => [
        'cms-article-family',
        'market-product',
        'hotels-hotel',
        'spas-spa',
        'wineries-winery',
    ],

    //******************************************************************************************************************
    //***   Frequencies
    //******************************************************************************************************************
    'frequencies'       => [
        (object)['id' => 1,      'name' => 'pulsar::pulsar.one_time'],
        (object)['id' => 2,      'name' => 'pulsar::pulsar.daily'],
        (object)['id' => 3,      'name' => 'pulsar::pulsar.weekly'],
        (object)['id' => 4,      'name' => 'pulsar::pulsar.monthly'],
        (object)['id' => 5,      'name' => 'pulsar::pulsar.quarterly']
    ],


    //******************************************************************************************************************
    //***   Extensions export file
    //******************************************************************************************************************
    'extensionsExportFile'   => [
        (object)['id' => 'csv',      'name' => '.csv'],
        (object)['id' => 'xls',      'name' => '.xls'],
        (object)['id' => 'xlsx',     'name' => '.xlsx'],
    ],

    //******************************************************************************************************************
    //***   Weekdays
    //******************************************************************************************************************
    'weekdays'   => [
        (object)['id' => 1,     'name' => 'pulsar::pulsar.monday'],
        (object)['id' => 2,     'name' => 'pulsar::pulsar.tuesday'],
        (object)['id' => 3,     'name' => 'pulsar::pulsar.wednesday'],
        (object)['id' => 4,     'name' => 'pulsar::pulsar.thursday'],
        (object)['id' => 5,     'name' => 'pulsar::pulsar.friday'],
        (object)['id' => 6,     'name' => 'pulsar::pulsar.saturday'],
        (object)['id' => 7,     'name' => 'pulsar::pulsar.sunday'],
    ]
];