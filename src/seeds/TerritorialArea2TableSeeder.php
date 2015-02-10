<?php
class TerritorialArea2TableSeeder extends Seeder
{
    public function run()
    {   
        //DATA 001_004_territorial_area_2 (ES)
        DB::insert(DB::raw("INSERT INTO `001_004_territorial_area_2` (`id_004`, `country_004`, `territorial_area_1_004`, `name_004`) VALUES
            ('ES-A',    'ES', 'ES-VC', 'Alicante'),
            ('ES-AB',   'ES', 'ES-CM', 'Albacete'),
            ('ES-AL',   'ES', 'ES-AN', 'Almería'),
            ('ES-AV',   'ES', 'ES-CL', 'Ávila'),
            ('ES-B',    'ES', 'ES-CT', 'Barcelona'),
            ('ES-BA',   'ES', 'ES-EX', 'Badajoz'),
            ('ES-BI',   'ES', 'ES-PV', 'Vizcaya'),
            ('ES-BU',   'ES', 'ES-CL', 'Burgos'),
            ('ES-C',    'ES', 'ES-GA', 'A Coruña'),
            ('ES-CA',   'ES', 'ES-AN', 'Cádiz'),
            ('ES-CC',   'ES', 'ES-EX', 'Cáceres'),
            ('ES-CE',   'ES', 'ES-CE', 'Ceuta'),
            ('ES-CO',   'ES', 'ES-AN', 'Córdoba'),
            ('ES-CR',   'ES', 'ES-CM', 'Ciudad Real'),
            ('ES-CS',   'ES', 'ES-VC', 'Castellón'),
            ('ES-CU',   'ES', 'ES-CM', 'Cuenca'),
            ('ES-GC',   'ES', 'ES-CN', 'Las Palmas'),
            ('ES-GI',   'ES', 'ES-CT', 'Girona'),
            ('ES-GR',   'ES', 'ES-AN', 'Granada'),
            ('ES-GU',   'ES', 'ES-CM', 'Guadalajara'),
            ('ES-H',    'ES', 'ES-AN', 'Huelva'),
            ('ES-HU',   'ES', 'ES-AR', 'Huesca'),
            ('ES-J',    'ES', 'ES-AN', 'Jaén'),
            ('ES-L',    'ES', 'ES-CT', 'Lleida'),
            ('ES-LE',   'ES', 'ES-CL', 'León'),
            ('ES-LO',   'ES', 'ES-RI', 'La Rioja'),
            ('ES-LU',   'ES', 'ES-GA', 'Lugo'),
            ('ES-M',    'ES', 'ES-MD', 'Madrid'),
            ('ES-MA',   'ES', 'ES-AN', 'Málaga'),
            ('ES-ML',   'ES', 'ES-ML', 'Melilla'),
            ('ES-MU',   'ES', 'ES-MC', 'Murcia'),
            ('ES-NA',   'ES', 'ES-NC', 'Navarra'),
            ('ES-O',    'ES', 'ES-AS', 'Asturias'),
            ('ES-OR',   'ES', 'ES-GA', 'Ourense'),
            ('ES-P',    'ES', 'ES-CL', 'Palencia'),
            ('ES-PM',   'ES', 'ES-IB', 'Baleares'),
            ('ES-PO',   'ES', 'ES-GA', 'Pontevedra'),
            ('ES-S',    'ES', 'ES-CB', 'Cantabria'),
            ('ES-SA',   'ES', 'ES-CL', 'Salamanca'),
            ('ES-SE',   'ES', 'ES-AN', 'Sevilla'),
            ('ES-SG',   'ES', 'ES-CL', 'Segovia'),
            ('ES-SO',   'ES', 'ES-CL', 'Soria'),
            ('ES-SS',   'ES', 'ES-PV', 'Guipúzcoa'),
            ('ES-T',    'ES', 'ES-CT', 'Tarragona'),
            ('ES-TE',   'ES', 'ES-AR', 'Teruel'),
            ('ES-TF',   'ES', 'ES-CN', 'Santa Cruz de Tenerife'),
            ('ES-TO',   'ES', 'ES-CM', 'Toledo'),
            ('ES-V',    'ES', 'ES-VC', 'Valencia'),
            ('ES-VA',   'ES', 'ES-CL', 'Valladolid'),
            ('ES-VI',   'ES', 'ES-PV', 'Álava'),
            ('ES-Z',    'ES', 'ES-AR', 'Zaragoza'),
            ('ES-ZA',   'ES', 'ES-CL', 'Zamora');"));
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="TerritorialArea2TableSeeder"
 */