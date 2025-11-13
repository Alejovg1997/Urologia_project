<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'id_number' => '12345678',
            'date_of_birth' => '1980-01-15',
            'gender' => 'male',
            'email' => 'juan.perez@example.com',
            'phone' => '555-2001',
            'address' => 'Calle Principal 123, Bogotá',
            'blood_type' => 'O+',
            'allergies' => 'Penicilina',
        ]);

        Patient::create([
            'first_name' => 'Ana',
            'last_name' => 'Martínez',
            'id_number' => '87654321',
            'date_of_birth' => '1990-05-20',
            'gender' => 'female',
            'email' => 'ana.martinez@example.com',
            'phone' => '555-2002',
            'address' => 'Avenida Libertador 456, Medellín',
            'blood_type' => 'A+',
            'allergies' => null,
        ]);

        Patient::create([
            'first_name' => 'Pedro',
            'last_name' => 'Sánchez',
            'id_number' => '11223344',
            'date_of_birth' => '1975-11-30',
            'gender' => 'male',
            'email' => 'pedro.sanchez@example.com',
            'phone' => '555-2003',
            'address' => 'Carrera 10 #20-30, Cali',
            'blood_type' => 'B+',
            'allergies' => 'Aspirina',
        ]);
    }
}
