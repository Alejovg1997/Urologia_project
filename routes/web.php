<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\AppointmentResourceController;
use App\Http\Controllers\Admin\DoctorAvailabilityResourceController;
use App\Http\Controllers\Frontend\AvailabilityController as PublicAvailabilityController;
use App\Http\Controllers\Frontend\AppointmentController as PublicAppointmentController;

// Backwards-compatible redirect: avoid route-model binding collisions when older links request `/appointments/new`
Route::get('/appointments/new', function () {
    return redirect()->route('public.appointments.create');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // ✅ Solo aquí, sin duplicar
    Route::resource('doctors', DoctorController::class);
    Route::resource('appointments', AppointmentResourceController::class);
    Route::resource('availabilities', DoctorAvailabilityResourceController::class);
    // Admin actions for appointments
    Route::patch('admin/appointments/{appointment}/status', [AppointmentResourceController::class, 'updateStatus'])->name('admin.appointments.status');
    Route::get('admin/doctor/{doctor}/agenda', [\App\Http\Controllers\Admin\DoctorAgendaController::class, 'index'])->name('admin.doctor.agenda');
    // Alias route: /home -> dashboard
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Alias protected calendar route: /calendar?doctor={slug} -> admin.doctor.agenda
    Route::get('/calendar', function (Illuminate\Http\Request $request) {
        $slug = $request->query('doctor');
        if (! $slug) {
            return redirect()->route('dashboard');
        }
        $doctor = \App\Models\Doctor::where('slug', $slug)->firstOrFail();
        return redirect()->route('admin.doctor.agenda', ['doctor' => $doctor->id]);
    })->name('admin.calendar');
});

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Public endpoints for viewing availability and booking
Route::get('/public/availability', [PublicAvailabilityController::class, 'index'])->name('public.availability.index');
// Bind by slug for public access (slugs are stable and user-friendly)
Route::get('/public/doctors/{doctor:slug}/availability', [PublicAvailabilityController::class, 'week'])->name('public.doctor.availability');
Route::post('/public/appointments', [PublicAppointmentController::class, 'store'])->name('public.appointments.store');

// Public list of doctors
Route::get('/public/doctors', [\App\Http\Controllers\Frontend\DoctorController::class, 'indexPublic'])->name('public.doctors.index');

// Dev-only: clear all doctor availabilities inserted by seeders to allow testing
if (app()->environment('local') || env('APP_DEBUG')) {
    Route::post('/dev/availabilities/clear', function () {
        \App\Models\DoctorAvailability::query()->delete();
        return response()->json(['success' => true, 'message' => 'All doctor availabilities cleared (dev).']);
    })->name('dev.availabilities.clear');
}

// Public doctor profile (shows doctor & lets frontend fetch availability)
Route::get('/doctors/{doctor:slug}', [\App\Http\Controllers\Frontend\DoctorController::class, 'show'])->name('public.doctors.show');

// Public appointment confirmation form (prefill doctor & start via query)
// Use a /public/* path to avoid conflicting with admin resource routes like appointments/{appointment}
Route::get('/public/appointments/new', [\App\Http\Controllers\Frontend\AppointmentController::class, 'create'])->name('public.appointments.create');



