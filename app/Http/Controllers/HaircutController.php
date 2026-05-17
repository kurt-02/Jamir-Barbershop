<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\HaircutStyle;

class HaircutController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $haircuts = HaircutStyle::latest()->get();

        return view('services', compact('services', 'haircuts'));
    }
}