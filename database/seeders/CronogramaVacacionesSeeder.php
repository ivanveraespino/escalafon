<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CronogramaVacaciones;
use App\Models\Personal;
use Faker\Factory as Faker;

class CronogramaVacacionesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES'); // Configura Faker en español
        $dnips = Personal::pluck('id_personal'); // Obtener todos los DNIs de la tabla `personal`

        foreach ($dnips as $dni) {
            $numVacaciones = $faker->numberBetween(1, 25); // Cada persona puede tener entre 1 y 3 vacaciones registradas

            for ($i = 0; $i < $numVacaciones; $i++) {
                CronogramaVacaciones::create([
                    'personal_id' => $dni,
                    'idtd' => $faker->numberBetween(1, 5), // Tipo de documento
                    'nrodoc' => $faker->unique()->randomNumber(8), // Número de documento
                    'estado' => $faker->randomElement(["['ENERO']", "['AGOSTO']", "['MARZO']"]), // Estado aleatorio
                    'fecha_ini' => $faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d'), // Fecha inicio entre 1 y 2 años atrás
                    'fecha_fin' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Fecha fin entre hace 1 año y ahora
                    'dias' => $faker->numberBetween(5, 30), // Número de días de vacaciones
                    'observaciones' => $faker->sentence, // Observaciones aleatorias
                    'estado' => $faker->randomElement(['APROBADO', 'PENDIENTE', 'RECHAZADO']), // Estado aleatorio
                    'periodo' => 2023, // Año del periodo
                    'fechadoc' => $faker->dateTimeThisYear->format('Y-m-d'), // Fecha del documento este año
                    'archivo' => null, // Puedes simular archivos si lo necesitas
                    'id_subida' => $faker->numberBetween(1, 100), // ID de subida
                ]);
            }
        }
    }
}
