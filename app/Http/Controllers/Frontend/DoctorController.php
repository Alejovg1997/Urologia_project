<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DoctorController extends Controller
{
    public function show(Doctor $doctor)
    {
        return Inertia::render('Public/DoctorShow', [
            'doctor' => $doctor,
        ]);
    }
}
