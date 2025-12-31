<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $records = [];
        for ($i = 0; $i < 200; $i++) {
            $records[] = [
                'nro_documento_id' => $faker->unique()->numerify('########'),
                'Apaterno' => $faker->lastName(),
                'Amaterno' => $faker->lastName(),
                'Nombres' => $faker->firstName(),
                'FechaNacimiento' => $faker->date('Y-m-d', '2000-01-01'),
                'Correo' => $faker->unique()->safeEmail(),
                'NroCelular' => $faker->numerify('9########'),
                'id_tipo_personal' => $faker->randomElement(['ACTIVO','ACTIVO']),
                'id_regimen' => $faker->numberBetween(1, 10),
                'id_regimen_modalidad' => $faker->numberBetween(1, 10),
     
            ];
        }
        //'created_at' => now(),
       // 'updated_at' => now(),
        // Insertar en lotes para mayor eficiencia
        $chunkSize = 500; // Tamaño del lote
        foreach (array_chunk($records, $chunkSize) as $chunk) {
            DB::table('personal')->insert($chunk);
        }
    }
}
