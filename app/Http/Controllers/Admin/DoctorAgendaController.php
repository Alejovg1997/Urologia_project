<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class DoctorAgendaController extends Controller
{
    public function index(Request $request, Doctor $doctor)
    {
        $startParam = $request->input('start');
        if ($startParam) {
            try {
                $weekStart = Carbon::parse($startParam)->startOfWeek(Carbon::MONDAY)->startOfDay();
            } catch (\Throwable $e) {
                $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
            }
        } else {
            $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        }

        $weekEnd = (clone $weekStart)->addDays(6)->endOfDay();

        $appointments = $doctor->appointments()
            ->whereBetween('start_time', [$weekStart, $weekEnd])
            ->orWhereBetween('end_time', [$weekStart, $weekEnd])
            ->orderBy('start_time')
            ->get();

        return Inertia::render('Admin/DoctorAgenda', [
            'doctor' => $doctor,
            'week_start' => $weekStart->toDateString(),
            'week_end' => $weekEnd->toDateString(),
            'appointments' => $appointments,
        ]);
    }
}
