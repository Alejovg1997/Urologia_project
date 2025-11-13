<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $reason;

    public function __construct(Appointment $appointment, $reason = null)
    {
        $this->appointment = $appointment;
        $this->reason = $reason;
    }

    public function build()
    {
        $subject = 'Estado de su cita: ' . $this->appointment->status;
        return $this->subject($subject)
                    ->view('emails.appointments.status_changed')
                    ->with(['appointment' => $this->appointment, 'reason' => $this->reason]);
    }
}
