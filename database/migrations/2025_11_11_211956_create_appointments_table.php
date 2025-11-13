<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique(); // Requisito: Model Binding por slug en rutas de citas

            // Información del paciente (Público sin autenticación)
            $table->string('patient_name');
            $table->string('patient_email'); // Necesario para el envío de correos
            $table->string('patient_phone')->nullable();

            // Fechas y Duración
            $table->dateTime('start_time');
            $table->dateTime('end_time'); // Se calcula en el backend usando APPOINTMENT_DURATION_MINUTES

            // Flujo de estados
            $table->enum('status', ['pendiente', 'confirmada', 'rechazada', 'completada'])
                  ->default('pendiente');
            
            $table->text('reason')->nullable(); // Razón de la cita
            $table->text('rejection_reason')->nullable(); // Razón de rechazo

            $table->timestamps();

            // Índice para mejorar el rendimiento en búsquedas de agenda
            $table->index(['doctor_id', 'start_time', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
