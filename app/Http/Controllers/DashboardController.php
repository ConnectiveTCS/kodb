<?php

namespace App\Http\Controllers;

use \App\Models\Speaker;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        //get all speakers
        $speakers = Speaker::all();

        //count speakers
        $count = Speaker::count();

        //get latest 5 speakers
        $latest = Speaker::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact('speakers', 'count', 'latest'));
    }
}
