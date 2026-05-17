<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentReceivedByBarber extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        //
        $this->appointment = $appointment;
    }

    public function build(){
        return $this->subject('New Appointment Received')->view('emails.appointment_received_by_barber')
            ->with([
                'appointment' => $this->appointment,
            ]);
    }
}
