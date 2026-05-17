<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Service;
use App\Models\Barber;
use App\Models\Appointment;
use App\Models\Review;
use App\Mail\AppointmentConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
    
class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showCalendar']);
    }

    // Step 1: Show location selection
    public function stepLocation()
    {
        // Check if user has an active appointments
        $user = Auth::user();

        // // Check for recent no-show appointments 
        // $recentNoShow = $user->appointments()
        // ->where('status', 'no_show')
        // ->orderByDesc('appointment_date')
        // ->first();

        // // Block user from booking if no-show
        // if ($recentNoShow && Carbon::parse($recentNoShow->appointment_date)->diffInDays(Carbon::now()) < 5) {
        //     return view('appointment.blocked', [
        //         'recentNoShow' => $recentNoShow,
        //         'remainingDays' => max(0, ceil(5 - Carbon::parse($recentNoShow->appointment_date)->diffInDays(Carbon::now())))
        //     ]);
        // }

        // Get the latest future appointment
        $activeAppointment = $user->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->latest()
            ->first();

        if ($activeAppointment) {
            if ($activeAppointment->status === 'confirmed') {
                return view('appointment.reschedule', compact('activeAppointment'));
            }

            if ($activeAppointment->status === 'pending') {
                // Check if another user already confirmed the same slot
                $slotTaken = Appointment::where('barber_id', $activeAppointment->barber_id)
                    ->where('appointment_date', $activeAppointment->appointment_date)
                    ->where('appointment_time', $activeAppointment->appointment_time)
                    ->where('status', 'confirmed')
                    ->exists();

                if ($slotTaken) {
                    // Update status to slot_taken and allow to book again
                    $activeAppointment->status = 'slot_taken';
                    $activeAppointment->save();

                    return view('appointment.slot_taken');
                }

                // Still pending and valid
                return view('appointment.pending_confirmation', compact('activeAppointment'));
            }

        if ($activeAppointment->status === 'slot_taken') {
            return view('appointment.slot_taken');
        }
    }

    // No active appointment or handled case → proceed to location selection
    $branches = Branch::all();
    return view('appointment.location', compact('branches'));
    }

    // Step 2: Show service selection
    public function stepService(Request $request)
    {
        $branch = Branch::find($request->branch_id);
        $services = Service::all();
        return view('appointment.service', compact('services', 'branch'));
    }

    // Step 3: Date & Time, Barber selection
    public function stepDateTimeBarber(Request $request)
    {
        $branch = Branch::find($request->branch_id);
        $services = $request->services ?? [];

        // Build dropdown options map (serviceId => selected option)
        $dropdownOptions = [];
        foreach ($services as $serviceId) {
            $dropdownOptions[$serviceId] = $request->input("dropdown_option_{$serviceId}", '');
        }

        // Collect haircut-related specializations from selected services
        $haircutSpecializations = [];
        foreach ($services as $serviceId) {
            $service = Service::find($serviceId);
            if (! $service) continue;

            // treat any service whose name contains 'haircut' (case-insensitive) as haircut-type
            if (Str::contains(Str::lower($service->name), 'haircut')) {
                $opt = trim($dropdownOptions[$serviceId] ?? '');
                if ($opt !== '') {
                    // normalize to lowercase for safe comparison
                    $haircutSpecializations[] = Str::lower($opt);
                }
            }
        }

        $barbers = Barber::where('branch_id', $branch->id)->get();

        // Normalize each barber's specialties and compute a recommendation score
        $barbers = $barbers->map(function ($barber) use ($haircutSpecializations) {
        $specs = $barber->specialties ?? [];

        if (is_string($specs)) {
            $decoded = json_decode($specs, true);
            $specs = is_array($decoded) ? $decoded : [$specs];
        }

        $normalized = array_filter(array_map(function ($s) {
            return Str::lower(trim($s));
        }, (array) $specs));

        // find which specialization(s) match
        $matched = [];
        foreach ($haircutSpecializations as $hs) {
            if (in_array($hs, $normalized)) {
                $matched[] = $hs;
            }
        }

        $barber->normalized_specialties = $normalized;
        $barber->is_recommended = count($matched) > 0;
        $barber->recommendation_score = count($matched);

        $barber->matched_specialties = array_map(function ($m) {
            return Str::title($m); // "classic haircut" → "Classic Haircut"
        }, $matched);

        return $barber;
        });

        // Sort barbers by recommendation_score desc so matching barbers appear first
        $barbers = $barbers->sortByDesc(function ($b) {
            return $b->recommendation_score ?? 0;
        })->values();

        $appointment_date = $request->appointment_date ?? Carbon::today()->toDateString();
        $appointment_time = $request->appointment_time ?? '08:00';

        return view('appointment.datetime-barber', compact(
            'barbers',
            'branch',
            'services',
            'appointment_date',
            'appointment_time',
            'dropdownOptions'
        ));
    }

    // Step 5: Confirmation
    public function stepConfirmation(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $appointment = new Appointment();
        $appointment->user_id = $user->id;
        $appointment->branch_id = $request->branch_id;
        $appointment->barber_id = $request->barber_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->client_name = $user->name;
        $appointment->client_email = $user->email;
        $appointment->client_phone = $user->contact_number ?? '';
        $appointment->status = 'pending';
        $appointment->confirmation_token = Str::uuid(); // for security ni user na mag confirm ng appointment
        $appointment->confirmation_expires_at = now()->addHours(6); // 6 hours expiration for confirmation
        $appointment->completion_token = Str::uuid(); // for security of barber to mark appointment as complete
        $appointment->otp_sent_at = now(); // for limiting the otp resending
        $appointment->save();
        
        $selectedDropdowns = [];
        foreach ($request->input('services', []) as $serviceId) {
            $key = 'dropdown_option_' . $serviceId;
            if ($request->has($key)) {
                $selectedDropdowns[$serviceId] = $request->input($key);
            }
        }

        // Attach services to appointment, including dropdown_option in the pivot
        $totalDuration = 0;

        foreach ($request->input('services', []) as $serviceId) {
            $service = \App\Models\Service::find($serviceId);
            $totalDuration += $service->duration_minutes;
            $appointment->services()->attach($serviceId, [
                'dropdown_option' => $selectedDropdowns[$serviceId] ?? null,
            ]);
        }

        $appointment->total_duration_minutes = $totalDuration;
        $appointment->save();

        $appointment->load('barber', 'services', 'branch');

        // email to client
        try {
            \Mail::to($appointment->client_email)->send(new \App\Mail\AppointmentConfirmed($appointment));
            \Log::info('Client confirmation email sent to ' . $appointment->client_email);
        } catch (\Exception $e) {
            \Log::error('Failed to send client email: ' . $e->getMessage());
        }
        
        // Send SMS to client
        if (!empty($appointment->client_phone)) {
            try {
                $response = Http::asForm()->post('https://www.iprogsms.com/api/v1/otp/send_otp', [  // REMOVED MESSAGE FOR SMS OTP
                    'api_token'    => config('services.iprogtech.token'),
                    'phone_number' => $appointment->client_phone, // format: 639XXXXXXXXX
                    'message' => "Hello {$appointment->client_name}, your appointment has been booked. Your OTP is :otp. It is valid for 5 minutes."
                ]);
    
                if ($response->successful()) {
                    Log::info('OTP sent to ' . $appointment->client_phone);
                } else {
                    Log::error('OTP send failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Log::error('OTP exception: ' . $e->getMessage());
            }
        }

        $appointment->load('services'); // loads pivot table data too
        return view('appointment.confirmation', compact('appointment'));
    }
    
    
    // FOR SMS OTP 
    public function verifyAppointmentOtp(Request $request, $id)
    {
        $request->validate([
            'otp' => 'required|string'
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Appointment already processed.');
        }

        $response = Http::post('https://www.iprogsms.com/api/v1/otp/verify_otp', [
            'api_token'    => config('services.iprogtech.token'),
            'phone_number' => $appointment->client_phone,
            'otp'          => $request->otp,
        ]);

        if ($response->successful()) {

            // same logic as email confirmation
            $newStart = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
            $newEnd = $newStart->copy()->addMinutes($appointment->total_duration_minutes ?? 30);

            $conflict = Appointment::where('barber_id', $appointment->barber_id)
                ->where('appointment_date', $appointment->appointment_date)
                ->where('status', 'confirmed')
                ->where('id', '!=', $appointment->id)
                ->get()
                ->some(function ($existing) use ($newStart, $newEnd) {
                    $existingStart = Carbon::parse($existing->appointment_date . ' ' . $existing->appointment_time);
                    $existingEnd = $existingStart->copy()->addMinutes($existing->total_duration_minutes ?? 30);
                    return $newStart->lt($existingEnd) && $newEnd->gt($existingStart);
                });

            if ($conflict) {
                $appointment->status = 'slot_taken';
                $appointment->save();

                return back()->with('error', 'Time slot already taken.');
            }

            $appointment->status = 'confirmed';
            $appointment->otp_verified = true;
            $appointment->save();

            return redirect()->route('dashboard')->with('success', 'Appointment confirmed via OTP!');
        }

        return back()->with('error', 'Invalid or expired OTP.');
    }

    // FOR SMS OTP

    public function sendOtp($id)
    {
        $appointment = Appointment::findOrFail($id);
    
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }
    
        // ✅ 10-minute cooldown check
        if ($appointment->otp_sent_at && Carbon::parse($appointment->otp_sent_at)->addMinutes(5)->isFuture()) {
            $remaining = Carbon::parse($appointment->otp_sent_at)
                ->addMinutes(5)
                ->diffInMinutes(now());
    
            return back()->with('error', "You can resend OTP again in {$remaining} minute(s).");
        }
    
        $response = Http::post('https://www.iprogsms.com/api/v1/otp/send_otp', [
            'api_token'    => config('services.iprogtech.token'),
            'phone_number' => $appointment->client_phone,
        ]);
    
        if ($response->successful()) {
            // ✅ update timestamp after successful send
            $appointment->otp_sent_at = now();
            $appointment->save();
    
            return back()->with('status', 'OTP resent successfully. Please wait 5 minutes before requesting again.');
        }
    
        return back()->with('error', 'Failed to resend OTP.');
    }

    public function confirmAppointment($token)
    {

        $appointment = Appointment::where('confirmation_token', $token)->first();
        
        if (! $appointment) {
            return view('appointment.invalid_token');
        }
        
        if (now()->greaterThan($appointment->confirmation_expires_at)) {
            $appointment->status = 'expired';
            $appointment->save();
        
            return view('appointment.expired', compact('appointment'));
        }
        
        if ($appointment->status !== 'pending') {
            return view('appointment.already_confirmed', compact('appointment'));
        }
        
        $newStart = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
        $newEnd = $newStart->copy()->addMinutes($appointment->total_duration_minutes ?? 30);
        

        // Check if the barber has another confirmed appointment at this date/time
        $conflict = Appointment::where('barber_id', $appointment->barber_id)
            ->where('appointment_date', $appointment->appointment_date)
            ->where('status', 'confirmed')
            ->where('id', '!=', $appointment->id)
            ->get()
            ->some(function ($existing) use ($newStart, $newEnd) {
                $existingStart = Carbon::parse($existing->appointment_date . ' ' . $existing->appointment_time);
                $existingEnd = $existingStart->copy()->addMinutes($existing->total_duration_minutes ?? 30);
                return $newStart->lt($existingEnd) && $newEnd->gt($existingStart);
            });

        if ($conflict) {
            // Time slot taken, cannot confirm
            $appointment->status = 'slot_taken';
            $appointment->save();
            return view('appointment.confirmation_failed', ['appointment' => $appointment]);
        }

        // No conflict, confirm the appointment
        $appointment->status = 'confirmed';
        $appointment->save();

        // logic to delete user's previous pending status to confirmed (para maiwasan duplication)
        Appointment::where('user_id', $appointment->user_id)
            ->where('status', 'pending')
            ->where('id', '!=', $appointment->id)
            ->delete();

        // Send confirmed appointment to barber
        try {
            \Mail::to($appointment->barber->email)->send(new \App\Mail\AppointmentReceivedByBarber($appointment));
            \Log::info('Barber notification email sent to ' . $appointment->barber->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send barber email: ' . $e->getMessage());
        }

        return view('appointment.confirmed', ['appointment' => $appointment]);
    }

    public function bookAgain()
    {
        $user = Auth::user();

        // Delete or reset any slot_taken appointment for this user
        $slotTakenAppointment = $user->appointments()
            ->where('status', 'slot_taken')
            ->first();

        if ($slotTakenAppointment) {
            $slotTakenAppointment->update(['status' => 'slot_taken']);
        }

        // Redirect to location selection to start fresh
        return redirect()->route('appointment.location');
    }

    public function barberAvailability($barberId)
    {
        $barber = Barber::findOrFail($barberId);
        $daysOff = array_merge($barber->days_off, ['saturday', 'sunday']);
        $currentAppointmentId = request()->query('current_appointment_id');
        $currentAppointment = $currentAppointmentId ? Appointment::find($currentAppointmentId) : null;

        $daysOffNumbers = array_map(function($day) { // to verify the days off of barbers
            return match(strtolower($day)) {
                'sunday' => 0,
                'monday' => 1,
                'tuesday' => 2,
                'wednesday' => 3,
                'thursday' => 4,
                'friday' => 5,
                'saturday' => 6,
                default => null,
            };
        }, $daysOff);

        $startHour = 8;
        $endHour = 20;
        $intervalMinutes = 30;
        $daysToCheck = 60;
        $today = Carbon::today();
        $now = Carbon::now();

        // 1 hour before current time
        $bufferMinutes = 30;
        $bufferedNow = $now->copy()->addMinutes($bufferMinutes);

        $availableSlots = [];

        for ($dayOffset = 0; $dayOffset < $daysToCheck; $dayOffset++) {
            $date = $today->copy()->addDays($dayOffset);

            if (in_array($date->dayOfWeek, $daysOffNumbers)) { //for the days off
                continue;
            }

            $dateStr = $date->format('Y-m-d');

            $bookedTimesQuery = Appointment::where('barber_id', $barberId)
                ->where('appointment_date', $dateStr)
                ->where('status', 'confirmed');

            if ($currentAppointment && $currentAppointment->appointment_date === $dateStr) {
                $bookedTimesQuery->where('id', '!=', $currentAppointment->id);
            }

            $confirmedAppointments = Appointment::where('barber_id', $barberId)
                ->where('appointment_date', $dateStr)
                ->where('status', 'confirmed')
                ->get();

            $bookedTimeRanges = [];
            foreach ($confirmedAppointments as $appt) {
                $start = Carbon::parse("$dateStr {$appt->appointment_time}");
                $end = $start->copy()->addMinutes($appt->total_duration_minutes ?? 30);
                $bookedTimeRanges[] = [$start, $end];
            }

            $slots = [];

            for ($hour = $startHour; $hour <= $endHour; $hour++) {
                foreach ([0, 30] as $minute) {
                    $slotTime = sprintf('%02d:%02d', $hour, $minute);
                    $slotDateTime = Carbon::parse("$dateStr $slotTime");

                    if ($date->isSameDay($bufferedNow) && $slotDateTime->lessThanOrEqualTo($bufferedNow)) {
                        continue;
                    }

                    $overlaps = false;
                    foreach ($bookedTimeRanges as [$start, $end]) {
                        if ($slotDateTime->between($start, $end->subSecond())) {
                            $overlaps = true;
                            break;
                        }
                    }

                    if (!$overlaps) {
                        $slots[] = $slotTime;
                    }
                }
            }

            if (count($slots) > 0) {
                $availableSlots[$dateStr] = $slots;
            }
        }

        return response()->json($availableSlots);
    }

    public function completeAppointment($token) //function para makapg mark as complete si barber sa emails
    {
        $appointment = Appointment::where('completion_token', $token)->first();

        if (!$appointment) {
            return view('appointment.completeappointmentforbarber.invalid_token');
        }

        if ($appointment->status === 'completed') {
        // If already completed, show a friendly message
        return view('appointment.completeappointmentforbarber.already_completed', compact('appointment'));
    }

        if ($appointment->status !== 'confirmed') {
            return view('appointment.completeappointmentforbarber.not_confirmed', compact('appointment')); //safeguard only 
        }

        $appointment->status = 'completed';
        $appointment->save();

        return view('appointment.completeappointmentforbarber.completed', compact('appointment'));
    }

    public function showCalendar($barberId) // function to show barber's calendar with booked appointments
    {
        $barber = Barber::findOrFail($barberId);

        $appointments = Appointment::where('barber_id', $barberId)
            ->where('status', 'confirmed')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $events = $appointments->map(function ($appt) {
            return [
                'title' => 'Booked @ ' . \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A'),
                'start' => $appt->appointment_date . 'T' . $appt->appointment_time,
                'allDay' => false,
                'color' => '#10b981',
            ];
        });

        $reviews = Review::where('barber_id', $barberId)->get();
        $averageRating = round($reviews->avg('rating'), 1); // like 4.3

        $ratingCounts = $reviews->groupBy('rating')->map->count();
        $ratingsData = [
            '1' => $ratingCounts[1] ?? 0,
            '2' => $ratingCounts[2] ?? 0,
            '3' => $ratingCounts[3] ?? 0,
            '4' => $ratingCounts[4] ?? 0,
            '5' => $ratingCounts[5] ?? 0,
        ];

        return view('calendar', [
            'barber' => $barber,
            'events' => $events,
            'averageRating' => $averageRating,
            'ratingsData' => $ratingsData,
        ]);
    }

    // reschedule apppointment 

    public function showRescheduleForm($id) 
    {
        $appointment = Appointment::with('barber')->findOrFail($id);

        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return redirect()->route('appointment.location')->with('error', 'Only confirmed appointments can be rescheduled.');
        }

        return view('appointment.reschedule-form', compact('appointment'));
    }

    public function submitReschedule(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $newStart = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $newEnd = $newStart->copy()->addMinutes($appointment->total_duration_minutes ?? 30);    

        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        // Check for conflicts
        $conflict = Appointment::where('barber_id', $appointment->barber_id)
        ->where('appointment_date', $request->appointment_date)
        ->where('status', 'confirmed')
        ->where('id', '!=', $appointment->id)
        ->get()
        ->some(function ($existing) use ($newStart, $newEnd) {
            $existingStart = Carbon::parse($existing->appointment_date . ' ' . $existing->appointment_time);
            $existingEnd = $existingStart->copy()->addMinutes($existing->total_duration_minutes ?? 30);
            return $newStart->lt($existingEnd) && $newEnd->gt($existingStart);
        });

        if ($conflict) {
            return back()->with('error', 'The selected time slot is no longer available.');
        }

        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->status = 'pending';
        $appointment->confirmation_token = Str::uuid();
        $appointment->confirmation_expires_at = now()->addHours(6); // 6 hours expiration for confirmation
        $appointment->otp_sent_at = now();
        $appointment->save();

        // Re-send confirmation email
        try {
            \Mail::to($appointment->client_email)->send(new \App\Mail\AppointmentConfirmed($appointment));
        } catch (\Exception $e) {
            \Log::error('Failed to resend confirmation: ' . $e->getMessage());
        }
        
        // Send SMS to client
        if (!empty($appointment->client_phone)) {
            try {
                $response = Http::asForm()->post('https://www.iprogsms.com/api/v1/otp/send_otp', [
                    'api_token'    => config('services.iprogtech.token'),
                    'phone_number' => $appointment->client_phone,
                    'message' => "Hello {$appointment->client_name}, your appointment has been rescheduled. Your OTP is :otp. It is valid for 5 minutes."
                ]);
        
                if ($response->successful()) {
                    Log::info('OTP sent for rescheduled appointment to ' . $appointment->client_phone);
                } else {
                    Log::error('OTP send failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('OTP exception: ' . $e->getMessage());
            }
        }

        $appointment->reminder_sent = 0; // reset so a new reminder can be scheduled

        return view('appointment.pending_confirmation', ['activeAppointment' => $appointment]);
    }

    // cancel appointment

    public function cancelAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->user_id !== Auth::id()) {
            abort(403); // Prevent unauthorized cancellation
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Only pending or confirmed appointments can be cancelled.');
        }

        $request->validate([
        'cancel_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $appointment->status = 'canceled';
        $appointment->cancel_reason = $request->cancel_reason;
        $appointment->save();

        return redirect()->back()->with('success', 'Your appointment has been cancelled.');
    }   
}
