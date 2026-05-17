<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $activeAppointment = $user->appointments()
            ->where('status', 'confirmed')
            ->where('appointment_date', '>=', now()->toDateString())
            ->latest()
            ->first();

        $pendingAppointment = $user->appointments()
            ->where('status', 'pending')
            ->where('appointment_date', '>=', now()->toDateString())
            ->latest()
            ->first();

        $completedAppointments = $user->appointments()
            ->where('status', 'completed')
            ->count();

        return view('dashboard', compact('activeAppointment', 'pendingAppointment', 'completedAppointments'));
    }
} 