<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Barber;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //

    public function create(){
        $barbers = Auth::user()->barbersWithAppointments(); // show barbers that had appointments with the user
        return view('rate', compact('barbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'rating' => 'required|integer|min:1|max:5',
            'quick_feedback' => 'nullable|array',
            'comment' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ]);

        // ✅ Only check for COMPLETED appointments
        $hasCompletedAppointment = Auth::user()
            ->appointments()
            ->where('barber_id', $validated['barber_id'])
            ->where('status', 'completed') // <-- enforce completed status
            ->exists();

        if (! $hasCompletedAppointment) {
            return back()->withErrors([
                'barber_id' => 'You can only review barbers after completing an appointment with them.'
            ])->withInput();
        }

        Review::create([
            'user_id' => Auth::id(),
            'barber_id' => $validated['barber_id'],
            'rating' => $validated['rating'],
            'quick_feedback' => $validated['quick_feedback'] ?? [],
            'comment' => $validated['comment'],
            'is_anonymous' => $request->input('is_anonymous') == '1',
            'approved' => true, 
        ]);

        return redirect()->route('reviews')->with('success', 'Review submitted successfully.');
    }


    public function showReviews(Request $request)
    {
        $barberId = $request->input('barber_id');
        $barbers = Barber::all();

        $reviews = Review::with(['user', 'barber'])
        ->when($barberId, function ($query) use ($barberId) {
            $query->where('barber_id', $barberId);
        })
        // ->where('approved', true) // optional, safe to remove
        ->latest()
        ->paginate(4);

        return view('reviews', compact('reviews', 'barbers', 'barberId'));
    }
}
