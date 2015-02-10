<?php
class PackageTableSeeder extends Seeder
{
    public function run()
    {
        DB::insert(DB::raw("INSERT INTO `001_012_package` (`id_012`, `name_012`, `active_012`) VALUES
            (1, 'Pulsar', 1),
            (2, 'Package de administración', 1),
            (3, 'Package Vinipad', 0),
            (4, 'Package Reserva de Cabinas', 0),
            (5, 'Package Comunik', 0),
            (6, 'Package Updater', 0),
            (7, 'Package Vinipad Sales Force', 0),
            (8, 'Package Vinipad Sales Force Front End', 0),
            (9, 'Package Vinipad Cuaderno de Cata', 0),
            (10,'Package de Gestión de Soportes Publicitarios', 0),
            (11,'Package Website', 0),
            (12,'Package Marketplace', 0),
            (13,'Package Hotels', 0);"));
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PackageTableSeeder"
 */