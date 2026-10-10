<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWhatsAppWebhookToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = (string) config('services.whatsapp.webhook_token');
        $providedToken = (string) $request->bearerToken();

        if ($configuredToken === '') {
            return new JsonResponse([
                'message' => 'API webhook belum dikonfigurasi.',
            ], 503);
        }

        if ($providedToken === '' || ! hash_equals($configuredToken, $providedToken)) {
            return new JsonResponse([
                'message' => 'Akses API tidak diizinkan.',
            ], 401);
        }

        return $next($request);
    }
}
