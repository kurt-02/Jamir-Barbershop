<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class PhoneVerificationController extends Controller
{
    //
    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        if (! $user->contact_number) {
            return back()->with('error', 'No contact number found.');
        }

        $response = Http::post('https://www.iprogsms.com/api/v1/otp/send_otp', [
            'api_token' => config('services.iprogtech.token'),
            'phone_number' => $user->contact_number,
        ]);

        if ($response->successful()) {
            session(['otp_sent' => true]);
            return back()->with('status', 'OTP sent to your phone.');
        }

        return back()->with('error', 'Failed to send OTP.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $user = Auth::user();

        $response = Http::post('https://www.iprogsms.com/api/v1/otp/verify_otp', [
            'api_token' => config('services.iprogtech.token'),
            'phone_number' => $user->contact_number,
            'otp' => $request->otp,
        ]);

        if ($response->successful()) {
            Log::info('OTP verified for ' . $user->contact_number . ' | Response: ' . $response->body());

            $user->markPhoneAsVerified();

            return redirect()->intended('/dashboard')
                ->with('status', 'Your phone number has been verified successfully!');
        }

        Log::error('Failed to verify OTP for ' . $user->contact_number . ' | Response: ' . $response->body());

        return back()->with('error', 'Invalid or expired OTP.');
    }
}
