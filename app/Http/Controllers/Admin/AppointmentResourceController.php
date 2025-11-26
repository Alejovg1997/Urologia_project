<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Mail\AppointmentCreated;
use App\Mail\AppointmentStatusChanged;

class AppointmentResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('doctor')->latest();

        $doctorId = $request->query('doctor_id');
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $appointments = $query->get();

        $doctors = Doctor::all(['id','name','specialty']);

        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments,
            'doctors' => $doctors,
            'filters' => [ 'doctor_id' => $doctorId ],
        ]);
    }

    public function create()
    {
        $doctors = Doctor::all();
        return Inertia::render('Appointments/Create', [
            'doctors' => $doctors,
        ]);
    }

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

        // Duración configurada desde .env
        $duration = env('APPOINTMENT_DURATION_MINUTES', 20);
        $validated['end_time'] = now()->parse($validated['start_time'])->addMinutes($duration);
        $validated['slug'] = Str::uuid();

        // Validar solapamiento de citas (overlap)
        $newStart = $validated['start_time'];
        $newEnd = $validated['end_time'];
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->where(function ($q) use ($newStart, $newEnd) {
                $q->where('start_time', '<', $newEnd)
                  ->where('end_time', '>', $newStart);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'Este horario ya está ocupado.']);
        }

        $appointment = Appointment::create($validated);

        // notify patient
        try {
            Mail::to($appointment->patient_email)->send(new AppointmentCreated($appointment));
        } catch (\Throwable $e) {
            // ignore mail errors
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Cita creada correctamente y enviada para aprobación.');
    }

    public function show(Appointment $appointment)
    {
        return Inertia::render('Appointments/Show', [
            'appointment' => $appointment->load('doctor'),
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::all();

        return Inertia::render('Appointments/Edit', [
            'appointment' => $appointment,
            'doctors' => $doctors,
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'in:pendiente,confirmada,rechazada,completada',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Cita eliminada correctamente.');
    }

    /**
     * Update only status (confirm/reject) via admin action.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,confirmada,rechazada,completada',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        // Enforce allowed transitions:
        $current = $appointment->status;
        $target = $validated['status'];

        $allowed = false;
        if ($current === 'pendiente' && in_array($target, ['confirmada','rechazada'])) {
            $allowed = true;
        }
        if ($current === 'confirmada' && $target === 'completada') {
            $allowed = true;
        }

        if (! $allowed) {
            return redirect()->back()->withErrors(['status' => 'Transición de estado no permitida.']);
        }

        $appointment->update($validated);

        // send notification to patient about status change
        try {
            Mail::to($appointment->patient_email)->send(new AppointmentStatusChanged($appointment, $validated['rejection_reason'] ?? null));
            \Log::info('AppointmentStatusChanged email sent', ['appointment_id' => $appointment->id, 'to' => $appointment->patient_email, 'status' => $appointment->status]);
        } catch (\Throwable $e) {
            \Log::error('Failed to send AppointmentStatusChanged email', ['error' => $e->getMessage(), 'appointment_id' => $appointment->id, 'to' => $appointment->patient_email]);
        }

        return redirect()->back()->with('success', 'Estado de la cita actualizado.');
    }
}
