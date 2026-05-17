<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Mail\AppointmentReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders 30 minutes before confirmed appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info("SendAppointmentReminders command running at " . now());
        $now = Carbon::now();
        
        // For testing only
        // $startTime = Carbon::now()->subMinutes(2); 
        // $endTime   = Carbon::now(); 

        // $appointments = Appointment::where('status', 'confirmed')
        //     ->where('reminder_sent', false)
        //     ->where('created_at', '<=', Carbon::now()->subMinutes(2))
        //     ->get();
        
        
        $startTime = $now->copy()->addMinutes(30);
        $endTime = $startTime->copy()->addMinutes(5);
        
        $appointments = Appointment::where('status', 'confirmed')
            ->where('reminder_sent', false)
            ->whereRaw(
                "STR_TO_DATE(CONCAT(appointment_date,' ',appointment_time), '%Y-%m-%d %H:%i:%s') BETWEEN ? AND ?",
                [$startTime->toDateTimeString(), $endTime->toDateTimeString()]
            )
            ->get();
    
        foreach ($appointments as $appointment) {
            $sent = false; // Track if at least one notification was sent

            // --- Send Email ---
            if (!empty($appointment->client_email)) {
                try {
                    Mail::to($appointment->client_email)
                        ->send(new AppointmentReminderMail($appointment));
                    \Log::info("Reminder email sent to {$appointment->client_email}");
                    $sent = true;
                } catch (\Exception $e) {
                    \Log::error("Failed to send email to {$appointment->client_email}: " . $e->getMessage());
                }
            }
    
            // --- Send SMS ---
            if (!empty($appointment->client_phone)) {
                try {
                    $message = "Reminder from Jamir Barbershop\n\n"
                        . "Hello {$appointment->client_name}, this is a reminder for your appointment at "
                        . "{$appointment->branch->name} on {$appointment->appointment_date} at {$appointment->appointment_time}.";
    
                    $response = Http::asForm()->post('https://sms.iprogtech.com/api/v1/sms_messages', [
                        'api_token'    => config('services.iprogtech.token'),
                        'phone_number' => $appointment->client_phone,
                        'message'      => $message,
                        'sms_provider' => 2,
                    ]);
    
                    if ($response->successful()) {
                        \Log::info("Reminder SMS sent to {$appointment->client_phone} | Response: {$response->body()}");
                        $sent = true;
                    } else {
                        \Log::error("Reminder SMS failed for {$appointment->client_phone}: {$response->body()}");
                    }
                } catch (\Exception $e) {
                    \Log::error("SMS exception for {$appointment->client_phone}: " . $e->getMessage());
                }
            }
    
            // --- Mark appointment as reminded if at least one notification was sent ---
            if ($sent) {
                $appointment->update(['reminder_sent' => true]);
                \Log::info("Reminder sent to {$appointment->client_email} / {$appointment->client_phone}");
                $this->info("Reminder sent to {$appointment->client_email} / {$appointment->client_phone}");
            } else {
                $this->info("No notifications sent for appointment ID {$appointment->id} (missing email/phone or errors).");
            }
        }

        return Command::SUCCESS;
    }
}
