<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::create([
            'first_name' => 'Carlos',
            'last_name' => 'Rodríguez',
            'id_number' => 'DOC001',
            'email' => 'carlos.rodriguez@hospital.com',
            'phone' => '555-1001',
            'specialty' => 'Urología',
            'license_number' => 'MED-001',
            'education' => 'MD Universidad Nacional, Especialidad en Urología',
            'is_active' => true,
        ]);

        Doctor::create([
            'first_name' => 'María',
            'last_name' => 'González',
            'id_number' => 'DOC002',
            'email' => 'maria.gonzalez@hospital.com',
            'phone' => '555-1002',
            'specialty' => 'Urología',
            'license_number' => 'MED-002',
            'education' => 'MD Universidad de los Andes, Especialidad en Urología',
            'is_active' => true,
        ]);
    }
}
