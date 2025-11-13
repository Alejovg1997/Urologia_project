<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorAvailability;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DoctorAvailabilityResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $availabilities = DoctorAvailability::with('doctor')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $doctors = Doctor::orderBy('name')->get();

        return Inertia::render('DoctorAvailabilities/Index', [
            'availabilities' => $availabilities,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::orderBy('name')->get();
        return Inertia::render('DoctorAvailabilities/Create', [
            'doctors' => $doctors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|string|max:20',
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        DoctorAvailability::create($validated);

        return redirect()->back()->with('success', 'Disponibilidad creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DoctorAvailability $availability)
    {
        $availability->load('doctor');

        return Inertia::render('DoctorAvailabilities/Show', [
            'availability' => $availability,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoctorAvailability $availability)
    {
        $doctors = Doctor::orderBy('name')->get();

        return Inertia::render('DoctorAvailabilities/Edit', [
            'availability' => $availability,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DoctorAvailability $availability)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|string|max:20',
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $availability->update($validated);

        return redirect()->back()->with('success', 'Disponibilidad actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorAvailability $availability)
    {
        $availability->delete();

        return redirect()->back()->with('success', 'Disponibilidad eliminada correctamente.');
    }
}
