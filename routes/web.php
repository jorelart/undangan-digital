<?php

use App\Http\Controllers\RsvpController;
use App\Models\Rsvp;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $guestName = request()->query('to', 'Tamu Undangan');
    $guestName = is_string($guestName) ? mb_substr(trim($guestName), 0, 120) : 'Tamu Undangan';

    return view('invitation', [
        'guestName' => $guestName !== '' ? $guestName : 'Tamu Undangan',
        'wishes' => Rsvp::query()
            ->whereNotNull('message')
            ->where('message', '<>', '')
            ->latest()
            ->limit(6)
            ->get(),
        'invitation' => config('invitation'),
    ]);
})->name('home');

Route::post('/rsvp', [RsvpController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('rsvps.store');
