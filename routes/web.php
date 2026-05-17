<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\Auth\PhoneVerificationController;
use App\Http\Controllers\HaircutController;
use App\Mail\AppointmentConfirmed;
use Illuminate\Support\Facades\Route;
use App\Models\Offer;
use App\Models\Service;
use App\Models\Review;

Route::get('/', function () {
    $offers = Offer::all();
    return view('welcome', compact('offers'));
});

Route::get('/services', function () {
    $services = Service::all(); // 
    return view('services', compact('services'));
});

Route::get('/barbers', [BarberController::class, 'index'])->name('barbers.index');

Route::get('/barber/{barberId}/calendar', [AppointmentController::class, 'showCalendar'])->name('barber.calendar');

Route::get('/about', function () {
    return view('about');
});

Route::get('/reviews', [ReviewController::class, 'showReviews'])->name('reviews');

Route::get('/services', [HaircutController::class, 'index']);

// Can be accessed after login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/verify-phone', function () {
        return view('auth.verify-phone');
    })->name('phone.verify');
    Route::post('/phone/send-otp', [PhoneVerificationController::class, 'sendOtp'])->name('phone.sendOtp');
    Route::post('/phone/verify-otp', [PhoneVerificationController::class, 'verifyOtp'])->name('phone.verifyOtp');
});

// para sa email confirmation
Route::get('/appointments/confirm/{token}', [AppointmentController::class, 'confirmAppointment'])->name('appointments.confirm');
// Route::get('/appointment/confirm/{appointment}', [AppointmentController::class, 'confirmAppointment'])->name('appointment.confirm');

// para sa mark as completed na appointment ni barber
Route::get('/appointment/complete/{token}', [AppointmentController::class, 'completeAppointment'])->name('appointment.complete');

// para sa book again na route if naging slot_taken na yung status
Route::get('/appointment/book-again', [AppointmentController::class, 'bookAgain'])->name('appointment.book-again');

Route::middleware('auth', 'verifiedContact')->group(function () {

    Route::get('/rate', [ReviewController::class, 'create'])->name('rate');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // para sa haircut recommender
    Route::get('/face-shape', [FaceController::class, 'index'])->name('face.index');
    Route::post('/face-shape/analyze', [FaceController::class, 'analyze'])->name('face.analyze');

    // Appointment Process Routes (Step-by-step)

    // Step 1: Select Location
    Route::get('/appointment/location', [AppointmentController::class, 'stepLocation'])->name('appointment.location');

    // Step 2: Select Services (POST from location step)
    Route::post('/appointment/service', [AppointmentController::class, 'stepService'])->name('appointment.service');

    // Step 3: Select Date, Time & Barber
    Route::post('/appointment/datetime-barber', [AppointmentController::class, 'stepDateTimeBarber'])->name('appointment.datetime-barber');

    // Step 4: Confirmation
    Route::post('/appointment/confirmation', [AppointmentController::class, 'stepConfirmation'])->name('appointment.confirmation');
    
    // SMS Appointment OTP
    Route::post('/appointment/{id}/send-otp', [AppointmentController::class, 'sendOtp'])
    ->name('appointment.send.otp');

    Route::post('/appointment/{id}/verify-otp', [AppointmentController::class, 'verifyAppointmentOtp'])
    ->name('appointment.verify.otp');
    
    Route::post('/appointment/confirm', [AppointmentController::class, 'finalize'])
    ->name('appointment.finalize');

    // route para sa date-time-barber availability
    Route::get('/barber-availability/{barber}', [AppointmentController::class, 'barberAvailability'])->name('barber.availability');

    // reschedule appointment route
    Route::get('/appointment/{id}/reschedule', [AppointmentController::class, 'showRescheduleForm'])->name('appointment.reschedule.form');
    Route::post('/appointment/{id}/reschedule', [AppointmentController::class, 'submitReschedule'])->name('appointment.reschedule.submit');

    // cancel appointment route
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancelAppointment'])->name('appointment.cancel');
});

require __DIR__.'/auth.php';
