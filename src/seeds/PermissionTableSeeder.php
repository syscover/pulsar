<?php
class PermissionTableSeeder extends Seeder {

    public function run()
    {
        DB::insert(DB::raw("INSERT INTO `001_009_permission` (`profile_009`, `resource_009`, `action_009`) VALUES
            (1, 'pulsar',                           'access'),
            (1, 'pulsar',                           'create'),
            (1, 'pulsar',                           'delete'),
            (1, 'pulsar',                           'edit'),
            (1, 'pulsar',                           'show'),
            (1, 'admin',                            'access'),
            (1, 'admin',                            'create'),
            (1, 'admin',                            'delete'),
            (1, 'admin',                            'edit'),
            (1, 'admin',                            'show'),
            (1, 'admin-country',                    'access'),
            (1, 'admin-country',                    'create'),
            (1, 'admin-country',                    'delete'),
            (1, 'admin-country',                    'edit'),
            (1, 'admin-country',                    'show'),
            (1, 'admin-country-at1',                'access'),
            (1, 'admin-country-at1',                'create'),
            (1, 'admin-country-at1',                'delete'),
            (1, 'admin-country-at1',                'edit'),
            (1, 'admin-country-at1',                'show'),
            (1, 'admin-country-at2',                'access'),
            (1, 'admin-country-at2',                'create'),
            (1, 'admin-country-at2',                'delete'),
            (1, 'admin-country-at2',                'edit'),
            (1, 'admin-country-at2',                'show'),
            (1, 'admin-country-at3',                'access'),
            (1, 'admin-country-at3',                'create'),
            (1, 'admin-country-at3',                'delete'),
            (1, 'admin-country-at3',                'edit'),
            (1, 'admin-country-at3',                'show'),
            (1, 'admin-cron',                       'access'),
            (1, 'admin-cron',                       'create'),
            (1, 'admin-cron',                       'delete'),
            (1, 'admin-cron',                       'edit'),
            (1, 'admin-cron',                       'show'),
            (1, 'admin-google-services',            'access'),
            (1, 'admin-google-services',            'create'),
            (1, 'admin-google-services',            'delete'),
            (1, 'admin-google-services',            'edit'),
            (1, 'admin-google-services',            'show'),
            (1, 'admin-lang',                       'access'),
            (1, 'admin-lang',                       'create'),
            (1, 'admin-lang',                       'delete'),
            (1, 'admin-lang',                       'edit'),
            (1, 'admin-lang',                       'show'),
            (1, 'admin-package',                    'access'),
            (1, 'admin-package',                    'create'),
            (1, 'admin-package',                    'delete'),
            (1, 'admin-package',                    'edit'),
            (1, 'admin-package',                    'show'),
            (1, 'admin-pass',                       'access'),
            (1, 'admin-pass',                       'create'),
            (1, 'admin-pass',                       'delete'),
            (1, 'admin-pass',                       'edit'),
            (1, 'admin-pass',                       'show'),
            (1, 'admin-pass-actions',               'access'),
            (1, 'admin-pass-actions',               'create'),
            (1, 'admin-pass-actions',               'delete'),
            (1, 'admin-pass-actions',               'edit'),
            (1, 'admin-pass-actions',               'show'),
            (1, 'admin-pass-pass',                  'access'),
            (1, 'admin-pass-pass',                  'create'),
            (1, 'admin-pass-pass',                  'delete'),
            (1, 'admin-pass-pass',                  'edit'),
            (1, 'admin-pass-pass',                  'show'),
            (1, 'admin-pass-profile',               'access'),
            (1, 'admin-pass-profile',               'create'),
            (1, 'admin-pass-profile',               'delete'),
            (1, 'admin-pass-profile',               'edit'),
            (1, 'admin-pass-profile',               'show'),
            (1, 'admin-pass-resource',              'access'),
            (1, 'admin-pass-resource',              'create'),
            (1, 'admin-pass-resource',              'delete'),
            (1, 'admin-pass-resource',              'edit'),
            (1, 'admin-pass-resource',              'show'),
            (1, 'admin-socials',                    'access'),
            (1, 'admin-socials',                    'create'),
            (1, 'admin-socials',                    'delete'),
            (1, 'admin-socials',                    'edit'),
            (1, 'admin-socials',                    'show'),
            (1, 'admin-user',                       'access'),
            (1, 'admin-user',                       'create'),
            (1, 'admin-user',                       'delete'),
            (1, 'admin-user',                       'edit'),
            (1, 'admin-user',                       'show'),
            (1, 'cabinas',                          'access'),
            (1, 'cabinas',                          'create'),
            (1, 'cabinas',                          'delete'),
            (1, 'cabinas',                          'edit'),
            (1, 'cabinas-cabinas',                  'access'),
            (1, 'cabinas-cabinas',                  'create'),
            (1, 'cabinas-cabinas',                  'delete'),
            (1, 'cabinas-cabinas',                  'edit'),
            (1, 'cabinas-citas',                    'access'),
            (1, 'cabinas-citas',                    'create'),
            (1, 'cabinas-citas',                    'delete'),
            (1, 'cabinas-citas',                    'edit'),
            (1, 'cabinas-esteticistas',             'access'),
            (1, 'cabinas-esteticistas',             'create'),
            (1, 'cabinas-esteticistas',             'delete'),
            (1, 'cabinas-esteticistas',             'edit'),
            (1, 'cabinas-horarios',                 'access'),
            (1, 'cabinas-horarios',                 'create'),
            (1, 'cabinas-horarios',                 'delete'),
            (1, 'cabinas-horarios',                 'edit'),
            (1, 'cabinas-locales',                  'access'),
            (1, 'cabinas-locales',                  'create'),
            (1, 'cabinas-locales',                  'delete'),
            (1, 'cabinas-locales',                  'edit'),
            (1, 'cabinas-preferencias',             'access'),
            (1, 'cabinas-preferencias',             'create'),
            (1, 'cabinas-preferencias',             'delete'),
            (1, 'cabinas-preferencias',             'edit'),
            (1, 'cabinas-tratamientos',             'access'),
            (1, 'cabinas-tratamientos',             'create'),
            (1, 'cabinas-tratamientos',             'delete'),
            (1, 'cabinas-tratamientos',             'edit'),
            (1, 'comunik',                          'access'),
            (1, 'comunik',                          'create'),
            (1, 'comunik',                          'delete'),
            (1, 'comunik',                          'edit'),
            (1, 'comunik-contacts',                 'access'),
            (1, 'comunik-contacts',                 'create'),
            (1, 'comunik-contacts',                 'delete'),
            (1, 'comunik-contacts',                 'edit'),
            (1, 'comunik-groups',                   'access'),
            (1, 'comunik-groups',                   'create'),
            (1, 'comunik-groups',                   'delete'),
            (1, 'comunik-groups',                   'edit'),
            (1, 'comunik-email-campaigns',          'access'),
            (1, 'comunik-email-campaigns',          'create'),
            (1, 'comunik-email-campaigns',          'delete'),
            (1, 'comunik-email-campaigns',          'edit'),
            (1, 'comunik-email-campaigns',          'show'),
            (1, 'comunik-email-sendings',           'access'),
            (1, 'comunik-email-sendings',           'create'),
            (1, 'comunik-email-sendings',           'delete'),
            (1, 'comunik-email-sendings',           'edit'),
            (1, 'comunik-email-sendings',           'show'),
            (1, 'comunik-email-accounts',           'access'),
            (1, 'comunik-email-accounts',           'create'),
            (1, 'comunik-email-accounts',           'delete'),
            (1, 'comunik-email-accounts',           'edit'),
            (1, 'comunik-email-templates',          'access'),
            (1, 'comunik-email-templates',          'create'),
            (1, 'comunik-email-templates',          'delete'),
            (1, 'comunik-email-templates',          'edit'),
            (1, 'comunik-email-templates',          'show'),
            (1, 'comunik-email-preferences',        'access'),
            (1, 'comunik-email-preferences',        'create'),
            (1, 'comunik-email-preferences',        'delete'),
            (1, 'comunik-email-preferences',        'edit'),
            (1, 'comunik-email-messages',           'access'),
            (1, 'comunik-email-messages',           'create'),
            (1, 'comunik-email-messages',           'delete'),
            (1, 'comunik-email-messages',           'edit'),
            (1, 'comunik-email-patterns',           'access'),
            (1, 'comunik-email-patterns',           'create'),
            (1, 'comunik-email-patterns',           'delete'),
            (1, 'comunik-email-patterns',           'edit'),
            (1, 'comunik-sms-campaigns',            'access'),
            (1, 'comunik-sms-campaigns',            'create'),
            (1, 'comunik-sms-campaigns',            'delete'),
            (1, 'comunik-sms-campaigns',            'edit'),
            (1, 'comunik-sms-campaigns',            'show'),
            (1, 'comunik-sms-templates',            'access'),
            (1, 'comunik-sms-templates',            'create'),
            (1, 'comunik-sms-templates',            'delete'),
            (1, 'comunik-sms-templates',            'edit'),
            (1, 'comunik-sms-templates',            'show'),
            (1, 'comunik-sms-preferences',          'access'),
            (1, 'comunik-sms-preferences',          'create'),
            (1, 'comunik-sms-preferences',          'delete'),
            (1, 'comunik-sms-preferences',          'edit'),
            (1, 'comunik-sms-senders',              'access'),
            (1, 'comunik-sms-senders',              'create'),
            (1, 'comunik-sms-senders',              'delete'),
            (1, 'comunik-sms-senders',              'edit'),
            (1, 'soportespub',                      'access'),
            (1, 'soportespub',                      'create'),
            (1, 'soportespub',                      'delete'),
            (1, 'soportespub',                      'edit'),
            (1, 'soportespub-familias',             'access'),
            (1, 'soportespub-familias',             'create'),
            (1, 'soportespub-familias',             'delete'),
            (1, 'soportespub-familias',             'edit'),
            (1, 'soportespub-laboratorios',         'access'),
            (1, 'soportespub-laboratorios',         'create'),
            (1, 'soportespub-laboratorios',         'delete'),
            (1, 'soportespub-laboratorios',         'edit'),
            (1, 'soportespub-marcas',               'access'),
            (1, 'soportespub-marcas',               'create'),
            (1, 'soportespub-marcas',               'delete'),
            (1, 'soportespub-marcas',               'edit'),
            (1, 'soportespub-preferencias',         'access'),
            (1, 'soportespub-preferencias',         'create'),
            (1, 'soportespub-preferencias',         'delete'),
            (1, 'soportespub-preferencias',         'edit'),
            (1, 'soportespub-productos',            'access'),
            (1, 'soportespub-productos',            'create'),
            (1, 'soportespub-productos',            'delete'),
            (1, 'soportespub-productos',            'edit'),
            (1, 'vinipad',                          'access'),
            (1, 'vinipad',                          'create'),
            (1, 'vinipad',                          'delete'),
            (1, 'vinipad',                          'edit'),
            (1, 'vinipad-activaciones',             'access'),
            (1, 'vinipad-activaciones',             'create'),
            (1, 'vinipad-activaciones',             'delete'),
            (1, 'vinipad-activaciones',             'edit'),
            (1, 'vinipad-clientes',                 'access'),
            (1, 'vinipad-clientes',                 'create'),
            (1, 'vinipad-clientes',                 'delete'),
            (1, 'vinipad-clientes',                 'edit'),
            (1, 'vinipad-locales',                  'access'),
            (1, 'vinipad-locales',                  'create'),
            (1, 'vinipad-locales',                  'delete'),
            (1, 'vinipad-locales',                  'edit'),
            (1, 'vinipad-systems',                  'access'),
            (1, 'vinipad-systems',                  'create'),
            (1, 'vinipad-systems',                  'delete'),
            (1, 'vinipad-systems',                  'edit'),
            (1, 'vinipadcc',                        'access'),
            (1, 'vinipadcc',                        'create'),
            (1, 'vinipadcc',                        'delete'),
            (1, 'vinipadcc',                        'edit'),
            (1, 'vinipadcc-bodegas',                'access'),
            (1, 'vinipadcc-bodegas',                'create'),
            (1, 'vinipadcc-bodegas',                'delete'),
            (1, 'vinipadcc-bodegas',                'edit'),
            (1, 'vinipadcc-do',                     'access'),
            (1, 'vinipadcc-do',                     'create'),
            (1, 'vinipadcc-do',                     'delete'),
            (1, 'vinipadcc-do',                     'edit'),
            (1, 'vinipadcc-maridajes',              'access'),
            (1, 'vinipadcc-maridajes',              'create'),
            (1, 'vinipadcc-maridajes',              'delete'),
            (1, 'vinipadcc-maridajes',              'edit'),
            (1, 'vinipadcc-tipos',                  'access'),
            (1, 'vinipadcc-tipos',                  'create'),
            (1, 'vinipadcc-tipos',                  'delete'),
            (1, 'vinipadcc-tipos',                  'edit'),
            (1, 'vinipadcc-uvas',                   'access'),
            (1, 'vinipadcc-uvas',                   'create'),
            (1, 'vinipadcc-uvas',                   'delete'),
            (1, 'vinipadcc-uvas',                   'edit'),
            (1, 'vinipadcc-vinos',                  'access'),
            (1, 'vinipadcc-vinos',                  'create'),
            (1, 'vinipadcc-vinos',                  'delete'),
            (1, 'vinipadcc-vinos',                  'edit'),
            (1, 'vinipadsalesforce',                'access'),
            (1, 'vinipadsalesforce',                'create'),
            (1, 'vinipadsalesforce',                'delete'),
            (1, 'vinipadsalesforce',                'edit'),
            (1, 'vinipadsalesforce-perfiles',       'access'),
            (1, 'vinipadsalesforce-perfiles',       'create'),
            (1, 'vinipadsalesforce-perfiles',       'delete'),
            (1, 'vinipadsalesforce-perfiles',       'edit'),
            (1, 'vinipadsalesforce-preferencias',   'access'),
            (1, 'vinipadsalesforce-preferencias',   'create'),
            (1, 'vinipadsalesforce-preferencias',   'delete'),
            (1, 'vinipadsalesforce-preferencias',   'edit'),
            (1, 'vinipadsalesforce-proveedores',    'access'),
            (1, 'vinipadsalesforce-proveedores',    'create'),
            (1, 'vinipadsalesforce-proveedores',    'delete'),
            (1, 'vinipadsalesforce-proveedores',    'edit'),
            (1, 'vinipadsalesforce-usuarios',       'access'),
            (1, 'vinipadsalesforce-usuarios',       'create'),
            (1, 'vinipadsalesforce-usuarios',       'delete'),
            (1, 'vinipadsalesforce-usuarios',       'edit'),
            (1, 'vinipadsf-frontend',               'access'),
            (1, 'vinipadsf-frontend',               'create'),
            (1, 'vinipadsf-frontend',               'delete'),
            (1, 'vinipadsf-frontend',               'edit'),
            (1, 'vinipadsf-frontend-alarmas',       'access'),
            (1, 'vinipadsf-frontend-alarmas',       'create'),
            (1, 'vinipadsf-frontend-alarmas',       'delete'),
            (1, 'vinipadsf-frontend-alarmas',       'edit'),
            (1, 'vinipadsf-frontend-locales',       'access'),
            (1, 'vinipadsf-frontend-locales',       'create'),
            (1, 'vinipadsf-frontend-locales',       'delete'),
            (1, 'vinipadsf-frontend-locales',       'edit'),
            (1, 'vinipadsf-frontend-locales',       'show'),
            (1, 'vinipadsf-frontend-notificacio',   'access'),
            (1, 'vinipadsf-frontend-notificacio',   'create'),
            (1, 'vinipadsf-frontend-notificacio',   'delete'),
            (1, 'vinipadsf-frontend-notificacio',   'edit'),
            (1, 'vinipadsf-frontend-usuarios',      'access'),
            (1, 'vinipadsf-frontend-usuarios',      'create'),
            (1, 'vinipadsf-frontend-usuarios',      'delete'),
            (1, 'vinipadsf-frontend-usuarios',      'edit'),
            (1, 'hotels',                           'access'),
            (1, 'hotels',                           'show'),
            (1, 'hotels',                           'create'),
            (1, 'hotels',                           'delete'),
            (1, 'hotels',                           'edit'),
            (1, 'hotels-atenciones',                'access'),
            (1, 'hotels-atenciones',                'show'),
            (1, 'hotels-atenciones',                'create'),
            (1, 'hotels-atenciones',                'delete'),
            (1, 'hotels-atenciones',                'edit'),
            (1, 'hotels-decoraciones',              'access'),
            (1, 'hotels-decoraciones',              'show'),
            (1, 'hotels-decoraciones',              'create'),
            (1, 'hotels-decoraciones',              'delete'),
            (1, 'hotels-decoraciones',              'edit'),
            (1, 'hotels-entornos',                  'access'),
            (1, 'hotels-entornos',                  'show'),
            (1, 'hotels-entornos',                  'create'),
            (1, 'hotels-entornos',                  'delete'),
            (1, 'hotels-entornos',                  'edit');"));
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PermissionTableSeeder"
 */