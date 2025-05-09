<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configuration du fuseau horaire et format Carbon
        date_default_timezone_set('Africa/Kigali');
        Carbon::setLocale('fr');
        Carbon::setToStringFormat('Y-m-d H:i:s (e)');

        // Vérification et création de la base de données si nécessaire
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $database = Config::get('database.connections.mysql.database');

            if (!empty($database)) {
                try {
                    $query = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                    DB::connection('mysql_without_db')->statement($query);

                    Config::set('database.connections.mysql.database', $database);
                    DB::purge('mysql');
                    DB::reconnect('mysql');
                } catch (\Exception $createException) {
                    logger()->error('Échec de création de la base de données: ' . $createException->getMessage());
                }
            }
        }
    }
}