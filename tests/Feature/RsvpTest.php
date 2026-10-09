<?php

namespace Tests\Feature;

use App\Jobs\SendRsvpWhatsAppNotification;
use App\Models\Rsvp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RsvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_invitation_page_displays_personalized_guest_name(): void
    {
        $this->get('/?to=Bapak%20Budi')
            ->assertOk()
            ->assertSee('Bapak Budi')
            ->assertSee('Konfirmasi Kehadiran');
    }

    public function test_rsvp_is_saved_without_whatsapp_configuration(): void
    {
        Queue::fake();

        $this->post('/rsvp', [
            'guest_name' => 'Dewi',
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Semoga lancar!',
        ])->assertRedirect('/#rsvp');

        $this->assertDatabaseHas('rsvps', [
            'guest_name' => 'Dewi',
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Semoga lancar!',
        ]);
        Queue::assertNothingPushed();
    }

    public function test_rsvp_queues_whatsapp_notification_when_configured(): void
    {
        Queue::fake();
        config()->set('services.whatsapp.token', 'test-token');
        config()->set('services.whatsapp.phone_number_id', '123456789');
        config()->set('services.whatsapp.owner_phone', '6281234567890');
        config()->set('services.whatsapp.template_name', 'rsvp_notification');

        $this->post('/rsvp', [
            'guest_name' => 'Dewi',
            'attendance' => 'not_attending',
            'message' => 'Mohon maaf belum bisa hadir.',
        ])->assertRedirect('/#rsvp');

        $this->assertDatabaseHas('rsvps', [
            'guest_name' => 'Dewi',
            'attendance' => 'not_attending',
            'guest_count' => 0,
        ]);
        Queue::assertPushed(SendRsvpWhatsAppNotification::class);
    }

    public function test_whatsapp_job_sends_the_owner_an_rsvp_template(): void
    {
        Http::fake();
        config()->set('services.whatsapp.token', 'test-token');
        config()->set('services.whatsapp.phone_number_id', '123456789');
        config()->set('services.whatsapp.owner_phone', '6281234567890');
        config()->set('services.whatsapp.template_name', 'rsvp_notification');

        $rsvp = Rsvp::create([
            'guest_name' => 'Dewi',
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Semoga lancar!',
        ]);

        (new SendRsvpWhatsAppNotification($rsvp->id))->handle();

        Http::assertSent(fn (ClientRequest $request): bool => $request->url() === 'https://graph.facebook.com/v23.0/123456789/messages'
            && $request->hasHeader('Authorization', 'Bearer test-token')
            && $request['to'] === '6281234567890'
            && $request['template']['name'] === 'rsvp_notification'
            && $request['template']['components'][0]['parameters'][1]['text'] === 'Hadir');
    }

    public function test_rsvp_rejects_invalid_attendance_and_excessive_guest_count(): void
    {
        $this->from('/#rsvp')->post('/rsvp', [
            'guest_name' => 'Dewi',
            'attendance' => 'maybe',
            'guest_count' => 20,
        ])->assertRedirect('/#rsvp')
            ->assertSessionHasErrors(['attendance']);

        $this->assertDatabaseCount('rsvps', 0);
    }
}
