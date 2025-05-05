<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpeakerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Webhook route from SpeakerController
Route::post('/webhook', [SpeakerController::class, 'webhook'])->name('webhook');

// auth middleware
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD routes for speakers
    Route::resource('speakers', SpeakerController::class);
    // Import Speakers route
    Route::post('/speakers/import', [App\Http\Controllers\SpeakerController::class, 'import'])->name('speakers.import');

    // Add batch delete route
    Route::post('speakers/batch-delete', [SpeakerController::class, 'batchDelete'])->name('speakers.batch-delete');

    // Add export route
    Route::get('/speakers/export', [App\Http\Controllers\SpeakerController::class, 'export'])->name('speakers.export');
});


require __DIR__ . '/auth.php';
