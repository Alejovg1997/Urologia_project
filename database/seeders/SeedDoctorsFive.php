<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class SeedDoctorsFive extends Seeder
{
    public function run()
    {
        $doctors = [
            ['name' => 'Dr. Alejandro Ruiz', 'specialty' => 'Urología', 'slug' => 'alejandro-ruiz', 'bio' => 'Especialista en urología clínica y quirúrgica.'],
            ['name' => 'Dra. María Gómez',     'specialty' => 'Urología', 'slug' => 'maria-gomez',     'bio' => 'Experiencia en diagnóstico y tratamiento de enfermedades renales.'],
            ['name' => 'Dr. Carlos Fernández', 'specialty' => 'Urología', 'slug' => 'carlos-fernandez','bio' => 'Urólogo con interés en litiasis y andrología.'],
            ['name' => 'Dra. Laura Méndez',    'specialty' => 'Urología', 'slug' => 'laura-mendez',    'bio' => 'Uróloga pediátrica y manejo de infecciones urinarias.'],
            ['name' => 'Dr. Jorge Ramos',      'specialty' => 'Urología', 'slug' => 'jorge-ramos',     'bio' => 'Urólogo con experiencia en cirugía mínimamente invasiva.'],
        ];

        foreach ($doctors as $d) {
            Doctor::updateOrCreate(
                ['slug' => $d['slug']],
                ['name' => $d['name'], 'specialty' => $d['specialty'], 'bio' => $d['bio']]
            );
        }
    }
}
