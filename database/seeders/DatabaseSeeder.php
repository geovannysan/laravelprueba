<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Pelicula;
use App\Models\Pelicula_cine;
use App\Models\Sala_cine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::transaction(function () {
            // Crear 9 salas de cine con estado
              for ($i = 1; $i <= 9; $i++) {
                Sala_cine::create(['nombre' => 'Sala ' . $i, 'estado' => 1]);
            }

            // Crear 15 películas y asociarlas a las salas de cine
            for ($f = 1; $f <= 15; $f++) {
                $pelicula = Pelicula::create(['nombre' => 'Pelicula ' . $f, 'duracion' => 120]);

                $id_sala = rand(1, 5);

                Pelicula_cine::create([
                    'fecha_publicacion' => now(),
                    'fecha_fin' => now()->addDays(30),
                    'id_pelicula' => $f,
                    'id_pelicula_sala' => $f,
                    'id_sala' => $id_sala,
                ]);
            }
        });
    }
}
