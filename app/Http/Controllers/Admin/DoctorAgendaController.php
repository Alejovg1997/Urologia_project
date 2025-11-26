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

        // Only include appointments that overlap the requested week and are confirmed
        $appointments = $doctor->appointments()
            ->where('status', 'confirmada')
            ->where(function ($q) use ($weekStart, $weekEnd) {
                $q->whereBetween('start_time', [$weekStart, $weekEnd])
                  ->orWhereBetween('end_time', [$weekStart, $weekEnd])
                  ->orWhere(function ($q2) use ($weekStart, $weekEnd) {
                      // appointments that start before the week and end after the week
                      $q2->where('start_time', '<', $weekStart)->where('end_time', '>', $weekEnd);
                  });
            })
            ->orderBy('start_time')
            ->get();

        return Inertia::render('Admin/DoctorAgenda', [
            'doctor' => $doctor,
            'week_start' => $weekStart->toDateString(),
            'week_end' => $weekEnd->toDateString(),
            'appointments' => $appointments,
        ]);
    }

    /**
     * Show confirmed appointments grouped by doctor (global admin agenda).
     */
    public function all(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $doctorId = $request->query('doctor_id');

        $doctorsQuery = Doctor::query();
        if ($doctorId) {
            $doctorsQuery->where('id', $doctorId);
        }

        $doctors = $doctorsQuery->with(['appointments' => function ($q) use ($start, $end) {
            $q->where('status', 'confirmada');
            if ($start) {
                $q->where('start_time', '>=', $start);
            }
            if ($end) {
                $q->where('start_time', '<=', $end);
            }
            $q->orderBy('start_time');
        }])->get();

        // list of doctors for the filter select
        $doctorsList = Doctor::select('id', 'name', 'specialty')->get();

        return Inertia::render('Admin/AllDoctorAgendas', [
            'doctors' => $doctors,
            'doctors_list' => $doctorsList,
            'filters' => [
                'start_date' => $start,
                'end_date' => $end,
                'doctor_id' => $doctorId,
            ],
        ]);
    }
}
