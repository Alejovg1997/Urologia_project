<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MedicalRecord::with(['patient', 'doctor', 'appointment'])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'record_date' => 'required|date',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'prescription' => 'nullable|string',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $medicalRecord = MedicalRecord::create($validated);
        
        return response()->json($medicalRecord->load(['patient', 'doctor', 'appointment']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecord $medicalRecord)
    {
        return $medicalRecord->load(['patient', 'doctor', 'appointment']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'record_date' => 'sometimes|date',
            'diagnosis' => 'sometimes|string',
            'treatment' => 'sometimes|string',
            'prescription' => 'nullable|string',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $medicalRecord->update($validated);
        
        return response()->json($medicalRecord->load(['patient', 'doctor', 'appointment']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        
        return response()->json(null, 204);
    }
}
