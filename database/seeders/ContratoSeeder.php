<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vinculo;
use App\Models\Personal;
use Faker\Factory as Faker;

class ContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $personales = Personal::pluck('id_personal')->toArray(); // Obtener todos los DNIs de la tabla personal

        for ($i = 0; $i < 1000; $i++) {
            Vinculo::create([
                'personal_id' => $faker->randomElement($personales),
                'id_unidad_organica' => $faker->numberBetween(1, 50),
                'id_unidad_organica' => $faker->numberBetween(1, 20),
                'id_depens' => $faker->numberBetween(1, 10),
                'descripcion_cese' => $faker->sentence(5),
                'id_regimen' => $faker->numberBetween(1, 5),
                'archivo' => $faker->randomElement([null, $faker->numberBetween(1, 100)]),
                'archivo_cese' => $faker->randomElement([null, $faker->numberBetween(1, 100)]),
                'id_condicion_laboral' => $faker->numberBetween(1, 4),
                'fecha_ini' => $faker->dateTimeBetween('-10 years', 'now'),
                'fecha_fin' => $faker->optional(0.7)->dateTimeBetween('now', '+5 years'),
                'id_tipo_documento' => $faker->numberBetween(1, 3),
                'id_tipo_documento_fin' => $faker->numberBetween(1, 3),
                'nro_doc' => $faker->numerify('DOC-#####'),
                'nro_doc_fin' => $faker->optional(0.5)->numerify('FIN-#####'),
                'id_accion_vin' => $faker->numberBetween(1, 10),
                'fecha_doc' => $faker->optional()->dateTimeBetween('-5 years', 'now'),
            ]);
        }
    }
}
