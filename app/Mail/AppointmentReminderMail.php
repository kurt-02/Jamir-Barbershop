<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $formattedDateTime;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;

        // Combine date + time and format it for the email
        $this->formattedDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $appointment->appointment_date . ' ' . $appointment->appointment_time
        )->format('F j, Y g:i A');
    }

    public function build()
    {
        return $this->subject('Appointment Reminder')
                    ->view('emails.appointment-reminder');
    }
}
