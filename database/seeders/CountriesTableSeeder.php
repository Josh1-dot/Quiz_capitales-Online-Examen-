<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['France', 'Paris'],
            ['Allemagne', 'Berlin'],
            ['Japon', 'Tokyo'],
            ['Congo', 'Kinshasa'],
            ['Kenya', 'Nairobi'],
            ['Italie', 'Rome'],
            ['Espagne', 'Madrid'],
            ['Portugal', 'Lisbonne'],
            ['Maroc', 'Rabat'],
            ['Algérie', 'Alger']
        ];

        foreach ($countries as $country) {
            Country::create([
                'name' => $country[0],
                'capital' => $country[1]
            ]);
        }
    }
}