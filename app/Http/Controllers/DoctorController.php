<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Doctor::with(['appointments', 'medicalRecords'])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|unique:doctors',
            'email' => 'required|email|unique:doctors',
            'phone' => 'required|string',
            'specialty' => 'nullable|string',
            'license_number' => 'required|string|unique:doctors',
            'education' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $doctor = Doctor::create($validated);
        
        return response()->json($doctor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        return $doctor->load(['appointments', 'medicalRecords']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'id_number' => 'sometimes|string|unique:doctors,id_number,' . $doctor->id,
            'email' => 'sometimes|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'sometimes|string',
            'specialty' => 'nullable|string',
            'license_number' => 'sometimes|string|unique:doctors,license_number,' . $doctor->id,
            'education' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $doctor->update($validated);
        
        return response()->json($doctor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        
        return response()->json(null, 204);
    }
}
