<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Patient::with(['appointments', 'medicalRecords'])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|unique:patients',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|unique:patients',
            'phone' => 'required|string',
            'address' => 'required|string',
            'blood_type' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        $patient = Patient::create($validated);
        
        return response()->json($patient, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return $patient->load(['appointments', 'medicalRecords']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'id_number' => 'sometimes|string|unique:patients,id_number,' . $patient->id,
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'email' => 'sometimes|email|unique:patients,email,' . $patient->id,
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
            'blood_type' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        $patient->update($validated);
        
        return response()->json($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        
        return response()->json(null, 204);
    }
}
