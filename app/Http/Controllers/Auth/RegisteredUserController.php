<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalize the contact number BEFORE validation
        $contact_number = $request->contact_number;
    
        if (!empty($contact_number)) {
            $contact_number = preg_replace('/\D+/', '', $contact_number); // remove non-digits
    
            if (preg_match('/^0\d+$/', $contact_number)) {
                $contact_number = '63' . substr($contact_number, 1); // replace leading 0
            } elseif (!str_starts_with($contact_number, '63')) {
                $contact_number = '63' . $contact_number; // prepend if missing
            }
    
            // overwrite request value before validation
            $request->merge(['contact_number' => $contact_number]);
        }
    
        // Now validate using the normalized format
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'contact_number' => ['nullable', 'string', 'regex:/^63[0-9]{10}$/', 'unique:users,contact_number'],
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols(),
                ],
        ], [
            'contact_number.unique' => 'This contact number is already registered.',
        ]);
        
        
        if (empty($request->email) && empty($request->contact_number)) {
            return back()
                ->withErrors(['contact' => 'Please provide either an email address or a contact number.'])
                ->withInput();
        }
    
        // Create the user with the normalized number
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'password' => Hash::make($request->password),
        ]);
    
        Auth::login($user);
    
        if (!empty($user->email)) {
            event(new Registered($user));
            return redirect()->route('verification.notice');
        }
    
        if (!empty($user->contact_number)) {
            return redirect()->route('phone.verify');
        }
    
        return redirect(route('dashboard', absolute: false));
    }
}
