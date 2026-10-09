<?php

namespace App\Jobs;

use App\Models\Rsvp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendRsvpWhatsAppNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [15, 60, 180];

    public function __construct(public int $rsvpId)
    {
        $this->onQueue('whatsapp');
    }

    public function handle(): void
    {
        $rsvp = Rsvp::findOrFail($this->rsvpId);
        $whatsapp = config('services.whatsapp');
        $attendance = $rsvp->attendance === 'attending' ? 'Hadir' : 'Tidak hadir';
        $message = $rsvp->message ?: '-';
        $url = sprintf(
            'https://graph.facebook.com/%s/%s/messages',
            $whatsapp['api_version'],
            $whatsapp['phone_number_id'],
        );

        Http::withToken($whatsapp['token'])
            ->acceptJson()
            ->timeout(15)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $whatsapp['owner_phone'],
                'type' => 'template',
                'template' => [
                    'name' => $whatsapp['template_name'],
                    'language' => ['code' => $whatsapp['template_language']],
                    'components' => [[
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $rsvp->guest_name],
                            ['type' => 'text', 'text' => $attendance],
                            ['type' => 'text', 'text' => (string) $rsvp->guest_count],
                            ['type' => 'text', 'text' => $message],
                        ],
                    ]],
                ],
            ])
            ->throw();
    }
}
