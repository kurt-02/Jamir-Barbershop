<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        $confirmationUrl = url('/appointments/confirm/' . $this->appointment->confirmation_token);

        return $this->subject('Confirm Your Appointment')
                    ->view('emails.appointment_confirmed')
                    ->with([
                        'appointment' => $this->appointment,
                        'confirmationUrl' => $confirmationUrl,
                    ]);
    }
}
