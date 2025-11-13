<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'specialty',
        'slug',
        'bio',
        'phone',
        'email',
    ];

    /**
     * Define la clave de ruteo para usar el slug en lugar del ID.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Un médico tiene muchas citas.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Un médico tiene muchos registros de disponibilidad semanal.
     */
    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class);
    }

    /**
     * Un médico pertenece, opcionalmente, a un usuario para login.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}