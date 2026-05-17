<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['contact_number'])) {
            $data['contact_number'] = '63' . $data['contact_number'];
        }
    
        $user = $request->user();
        $emailChanged = isset($data['email']) && $data['email'] !== $user->email;
        $phoneChanged = isset($data['contact_number']) && $data['contact_number'] !== $user->contact_number;
    
        $user->fill($data);
    
        if ($emailChanged) {
            $user->email_verified_at = null; // mark as unverified
            $user->save();
            // Send new verification email
            $user->sendEmailVerificationNotification();
        } 
        
        if ($phoneChanged) {
            $user->phone_verified_at = null; // mark as unverified
            $user->save();
    
            // immediately send them to OTP flow
            return redirect()->route('phone.verify')->with('info', 'Please verify your new phone number.');
        }
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
