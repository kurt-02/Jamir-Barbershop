<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barber;

class BarberController extends Controller
{
    //
    public function index(){
        $barbers = Barber::with('branch')
        ->withAvg('reviews', 'rating')
        ->paginate(4);
        
        return view('barbers', compact('barbers'));
    }
}
