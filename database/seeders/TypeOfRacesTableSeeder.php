<?php

namespace Database\Seeders;

use App\Models\TypeOfRace;
use Illuminate\Database\Seeder;

class TypeOfRacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        TypeOfRace::firstOrCreate([
            'id' => 3,
            'name' => '3KM',
            'description' => 'Prova de 3KM'
        ]);

        TypeOfRace::firstOrCreate([
            'id' => 5,
            'name' => '5KM',
            'description' => 'Prova de 5KM'
        ]);

        TypeOfRace::firstOrCreate([
            'id' => 10,
            'name' => '10KM',
            'description' => 'Prova de 10KM'
        ]);

        TypeOfRace::firstOrCreate([
            'id' => 21,
            'name' => '21KM',
            'description' => 'Prova de 21KM'
        ]);

        TypeOfRace::firstOrCreate([
            'id' => 42,
            'name' => '42KM',
            'description' => 'Prova de 42KM'
        ]);
    }
}
