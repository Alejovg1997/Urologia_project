<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentCreated;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'nullable|string|max:15',
            'start_time' => 'required|date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $duration = env('APPOINTMENT_DURATION_MINUTES', 20);
        $start = Carbon::parse($validated['start_time']);
        $validated['end_time'] = $start->copy()->addMinutes($duration);
        $validated['status'] = 'pendiente';
        $validated['slug'] = Str::uuid();

        // check conflict (overlap) -- existing.start < new.end AND existing.end > new.start
        $newStart = $validated['start_time'];
        $newEnd = $validated['end_time'];
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereIn('status', ['pendiente','confirmada'])
            ->where(function ($q) use ($newStart, $newEnd) {
                $q->where('start_time', '<', $newEnd)
                  ->where('end_time', '>', $newStart);
            })->exists();

        if ($conflict) {
            return response()->json(['message' => 'El espacio ya está ocupado.'], 422);
        }

        $appointment = Appointment::create($validated);

        // send notification email to patient
        try {
            Mail::to($appointment->patient_email)->send(new AppointmentCreated($appointment));
            \Log::info('AppointmentCreated email sent', ['appointment_id' => $appointment->id, 'to' => $appointment->patient_email]);
        } catch (\Throwable $e) {
            \Log::error('Failed to send AppointmentCreated email', ['error' => $e->getMessage(), 'appointment_id' => $appointment->id, 'to' => $appointment->patient_email]);
        }

        return response()->json(['success' => true, 'message' => 'Cita solicitada correctamente.', 'appointment' => $appointment], 201);
    }

    public function create(Request $request)
    {
        $doctorSlug = $request->query('doctor');
        $start = $request->query('start');

        $doctor = null;
        if ($doctorSlug) {
            $doctor = Doctor::where('slug', $doctorSlug)->first();
        }

        // Also pass list of doctors so the form can work when no doctor slug provided
        $doctors = Doctor::all(['id','name','specialty','slug']);

        return Inertia::render('Public/AppointmentCreate', [
            'doctor' => $doctor,
            'start' => $start,
            'doctors' => $doctors,
        ]);
    }
}
