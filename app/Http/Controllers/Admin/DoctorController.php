<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DoctorController extends Controller
{
    /**
     * Mostrar todos los doctores.
     */
    public function index()
    {
        $doctors = Doctor::orderBy('created_at', 'desc')->get();

        return Inertia::render('Doctors/Index', [
            'doctors' => $doctors,
        ]);
    }

    /**
     * Mostrar formulario para crear un nuevo doctor.
     */
    public function create()
    {
        return Inertia::render('Doctors/Create');
    }

    /**
     * Guardar un nuevo doctor en la base de datos.
     */
    public function store(Request $request)
{
    // Verificar que hay un usuario autenticado (Sanctum / sesión)
    if (! auth()->check()) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para crear un doctor.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255|unique:doctors,email',
    ]);

    $validated['slug'] = Str::slug($validated['name']);
    $validated['specialty'] = 'Urología';
    // Asignar user_id del usuario autenticado
    $validated['user_id'] = auth()->id();

    Doctor::create($validated);

    return redirect()->route('doctors.index')->with('success', 'Doctor creado correctamente.');
}


    /**
     * Mostrar la información de un doctor.
     */
    public function show(Doctor $doctor)
    {
        return Inertia::render('Doctors/Show', [
            'doctor' => $doctor,
        ]);
    }

    /**
     * Mostrar formulario para editar un doctor existente.
     */
    public function edit(Doctor $doctor)
    {
        return Inertia::render('Doctors/Edit', [
            'doctor' => $doctor,
        ]);
    }

    /**
     * Actualizar un doctor existente.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:doctors,email,' . $doctor->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['specialty'] = 'Urología';
        $validated['user_id'] = auth()->id() ?? $doctor->user_id;

        $doctor->update($validated);

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor actualizado correctamente.');
    }

    /**
     * Eliminar un doctor.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor eliminado correctamente.');
    }
}
