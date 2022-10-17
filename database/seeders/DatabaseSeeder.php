<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         Config::insert([
             [
                 'name' => 'Eleições Federal 2022 1º Turno',
                 'ordinary' => 544,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
             [
                 'name' => 'Eleições Federal 2022 2º Turno',
                 'ordinary' => 545,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
             [
                 'name' => 'Eleições Estadual 2022 1º Turno',
                 'ordinary' => 546,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
             [
                 'name' => 'Eleições Estadual 2022 2º Turno',
                 'ordinary' => 547,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
         ]);
    }
}
