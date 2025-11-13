<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Appointment::with(['patient', 'doctor', 'medicalRecord'])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'status' => 'nullable|in:scheduled,completed,cancelled,no_show',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);
        
        return response()->json($appointment->load(['patient', 'doctor']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return $appointment->load(['patient', 'doctor', 'medicalRecord']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'appointment_date' => 'sometimes|date',
            'status' => 'sometimes|in:scheduled,completed,cancelled,no_show',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);
        
        return response()->json($appointment->load(['patient', 'doctor']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return response()->json(null, 204);
    }
}
