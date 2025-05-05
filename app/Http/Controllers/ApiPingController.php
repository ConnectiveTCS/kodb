<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiPingController extends Controller
{
    public function ping()
    {
        return response()->json(['status' => 'ok', 'message' => 'API is accessible']);
    }
}
