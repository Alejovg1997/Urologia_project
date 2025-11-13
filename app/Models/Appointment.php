<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'slug',
        'patient_name',
        'patient_email',
        'patient_phone',
        'start_time',
        'end_time',
        'status',
        'reason',
        'rejection_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            if (empty($appointment->slug)) {
                $appointment->slug = Str::uuid()->toString();
            }
        });
    }

    // Relación con Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Scopes útiles
    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmada');
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
