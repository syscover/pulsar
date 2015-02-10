<?php
class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::insert(DB::raw("INSERT INTO `001_010_user` (`id_010`, `lang_010`, `profile_010`, `access_010`, `user_010`, `password_010`, `email_010`, `name_010`, `surname_010`, `updated_at`, `created_at`) VALUES
            (1, 'es', 1, 1, 'cpalacin@syscover.com', '\$2y$10\$BWz3whvzxlfCJKVDYGAwguohPYQLSHlIBAa6.PCpE8Qdy/Ccc5jYq', 'cpalacin@syscover.com', 'José Carlos', 'Rodríguez Palacín', '0000-00-00 00:00:00', '0000-00-00 00:00:00');"));
    }
}

/*
 * Comand to run:
 * php artisan db:seed --class="UserTableSeeder"
 */