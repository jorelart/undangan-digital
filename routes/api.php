<?php

use App\Http\Controllers\Api\GuestController;
use App\Http\Middleware\VerifyWhatsAppWebhookToken;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/invitation', function (Request $request) {
    $token = $request->bearerToken();

    if (! $token || strlen($token) > 128) {
        return response()->json([
            'message' => 'Undangan umum.',
        ]);
    }

    $guest = Guest::query()
        ->where('invitation_token', $token)
        ->first();

    if (! $guest) {
        return response()->json([
            'message' => 'Undangan umum.',
        ]);
    }

    return response()->json([
        'name' => $guest->name,
        'max_guests' => $guest->max_guests,
    ]);
})->middleware('throttle:30,1');

Route::prefix('v1')->middleware(VerifyWhatsAppWebhookToken::class)->group(function (): void {
    Route::get('/guests', [GuestController::class, 'index']);
    Route::post('/guests', [GuestController::class, 'store']);
    Route::get('/guests/{guest}', [GuestController::class, 'show'])
        ->whereNumber('guest');
    Route::post('/guests/{guest}/rsvp', [GuestController::class, 'storeRsvp'])
        ->whereNumber('guest');
    Route::post('/guests/{guest}/rsvp/reply', [GuestController::class, 'storeRsvpReply'])
        ->whereNumber('guest');
});
