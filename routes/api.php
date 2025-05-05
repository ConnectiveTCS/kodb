<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpeakerBioController;
use App\Http\Controllers\ApiPingController;

// API connectivity test endpoint
Route::get('/ping', [ApiPingController::class, 'ping']);

// Ensure the generate-bio route is correctly registered
Route::post('/generate-bio', [SpeakerBioController::class, 'generateBio']);

// ...existing routes...
