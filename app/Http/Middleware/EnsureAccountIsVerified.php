<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $hasEmail = ! empty($user->email);
        $hasPhone = ! empty($user->contact_number);

        $emailVerified = $user->hasVerifiedEmail();
        $phoneVerified = $user->hasVerifiedPhone(); // make sure this exists

        // If at least one contact is verified → allow
        if (($hasEmail && $emailVerified) || ($hasPhone && $phoneVerified)) {
            return $next($request);
        }

        // If email exists but not verified
        if ($hasEmail && ! $emailVerified) {
            return redirect()->route('verification.notice');
        }

        // If phone exists but not verified
        if ($hasPhone && ! $phoneVerified) {
            return redirect()->route('phone.verify')->with('info', 'Please verify your phone.');
        }

        // fallback
        return redirect()->route('verification.notice');
    }
}
