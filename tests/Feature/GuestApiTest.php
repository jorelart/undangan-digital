<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.whatsapp.webhook_token', 'test-webhook-secret');
        $this->withToken('test-webhook-secret');
    }

    public function test_the_invitation_page_loads_without_a_guest_token(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('wire:id=', false)
            ->assertSee('id="invitation-guest-name"', false)
            ->assertSee('Tamu Undangan')
            ->assertSee('assets/js/invitation.js', false)
            ->assertSee('livewire.js', false)
            ->assertSee('class="elementor-element elementor-element-6fb9756', false)
            ->assertSee('class="rsvp-card"', false)
            ->assertSee('The Wedding of');

        $localOverrides = file_get_contents(public_path('assets/css/local-overrides.css'));

        $this->assertIsString($localOverrides);
        $this->assertStringNotContainsString('.elementor-element-6fb9756', $localOverrides);
    }

    public function test_the_invitation_page_displays_guest_messages_and_owner_replies(): void
    {
        $repliedGuest = Guest::create([
            'name' => 'Tamu Dibalas',
            'phone' => '6281111111111',
            'max_guests' => 1,
        ]);
        Rsvp::create([
            'guest_id' => $repliedGuest->id,
            'presence' => 'hadir',
            'guest_count' => 1,
            'message' => 'Semoga menjadi keluarga yang sakinah.',
            'owner_reply' => 'Terima kasih atas doa dan ucapannya.',
        ]);

        foreach (['Tamu Tanpa Balasan A', 'Tamu Tanpa Balasan B'] as $index => $name) {
            $guest = Guest::create([
                'name' => $name,
                'phone' => '628222222222'.($index + 1),
                'max_guests' => 1,
            ]);
            Rsvp::create([
                'guest_id' => $guest->id,
                'presence' => 'hadir',
                'guest_count' => 1,
                'message' => 'Selamat berbahagia.',
            ]);
        }

        $response = $this->get('/')
            ->assertOk()
            ->assertSee('class="rsvp-headline"', false)
            ->assertSee('class="rsvp-status-label rsvp-status-hadir"', false)
            ->assertSee('Tamu Dibalas')
            ->assertSee('Semoga menjadi keluarga yang sakinah.')
            ->assertSee('Terima kasih atas doa dan ucapannya.')
            ->assertSee('class="rsvp-replies"', false)
            ->assertSee('class="rsvp-reply-author">Rapita &amp; Lingga</div>', false)
            ->assertSee('Selamat berbahagia.')
            ->assertSee('Tamu Tanpa Balasan A')
            ->assertSee('Tamu Tanpa Balasan B');

        $html = $response->getContent();
        $this->assertSame(3, substr_count($html, '<li class="rsvp-item">'));
    }

    public function test_a_valid_invitation_token_resolves_to_the_guest_name(): void
    {
        Guest::create([
            'name' => 'Aris Budiono',
            'phone' => null,
            'max_guests' => 2,
            'invitation_token' => 'guest-invitation-token',
        ]);

        $this->withToken('guest-invitation-token')
            ->getJson('/api/invitation')
            ->assertOk()
            ->assertJsonPath('name', 'Aris Budiono')
            ->assertJsonPath('max_guests', 2);
    }

    public function test_an_invalid_invitation_token_does_not_block_the_generic_page(): void
    {
        $this->withToken('invalid-token')
            ->getJson('/api/invitation')
            ->assertOk()
            ->assertJsonPath('message', 'Undangan umum.');
    }

    public function test_api_requires_the_configured_webhook_secret(): void
    {
        $this->withToken('wrong-secret')
            ->getJson('/api/v1/guests')
            ->assertUnauthorized();

        config()->set('services.whatsapp.webhook_token', '');

        $this->getJson('/api/v1/guests')
            ->assertServiceUnavailable();
    }

    public function test_webhook_can_create_a_guest_with_a_normalized_phone_number(): void
    {
        $response = $this->postJson('/api/v1/guests', [
            'name' => 'Ayu Putri',
            'phone' => '+62 812-3456-7890',
            'max_guests' => 2,
        ])->assertCreated()
            ->assertJsonPath('data.name', 'Ayu Putri')
            ->assertJsonPath('data.phone', '6281234567890')
            ->assertJsonPath('data.max_guests', 2)
            ->assertJsonPath('rsvp', null);

        $token = $response->json('invitation_token');
        $this->assertIsString($token);
        $this->assertSame(64, strlen($token));
        $this->assertSame(config('app.url').'/#token='.$token, $response->json('invitation_url'));

        $guestId = $response->json('data.id');

        $this->getJson('/api/v1/guests/'.$guestId)
            ->assertOk()
            ->assertJsonPath('data.phone', '6281234567890')
            ->assertJsonMissingPath('data.invitation_token');

        $this->assertDatabaseHas('guests', [
            'name' => 'Ayu Putri',
            'phone' => '6281234567890',
            'invitation_token' => $token,
        ]);
    }

    public function test_duplicate_phone_numbers_are_rejected(): void
    {
        Guest::create([
            'name' => 'Ayu Putri',
            'phone' => '6281234567890',
            'max_guests' => 1,
        ]);

        $this->postJson('/api/v1/guests', [
            'name' => 'Ayu Lain',
            'phone' => '+62 812-3456-7890',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_webhook_can_list_and_search_guests_by_name_or_phone(): void
    {
        Guest::create([
            'name' => 'Ayu Putri',
            'phone' => '6281234567890',
            'max_guests' => 2,
        ]);
        Guest::create([
            'name' => 'Budi Santoso',
            'phone' => '6289876543210',
            'max_guests' => 1,
        ]);

        $this->getJson('/api/v1/guests?q=Ayu')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.name', 'Ayu Putri');

        $this->getJson('/api/v1/guests?phone=%2B62%20812-3456-7890')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.phone', '6281234567890');

        $this->getJson('/api/v1/guests?q=%2B62%20812-3456-7890')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.phone', '6281234567890');

        $this->getJson('/api/v1/guests')
            ->assertOk()
            ->assertJsonPath('total', 2);
    }

    public function test_webhook_can_store_and_update_a_guest_rsvp(): void
    {
        $guest = Guest::create([
            'name' => 'Ayu Putri',
            'phone' => '6281234567890',
            'max_guests' => 2,
        ]);

        $this->postJson('/api/v1/guests/'.$guest->id.'/rsvp', [
            'presence' => 'hadir',
            'guest_count' => 2,
            'message' => 'Insyaallah hadir.',
        ])->assertOk()
            ->assertJsonPath('data.guest_count', 2);

        $this->postJson('/api/v1/guests/'.$guest->id.'/rsvp', [
            'presence' => 'tidak',
            'message' => 'Mohon maaf, berhalangan.',
        ])->assertOk()
            ->assertJsonPath('data.presence', 'tidak')
            ->assertJsonPath('data.guest_count', 0);

        $this->assertDatabaseCount('rsvps', 1);
    }

    public function test_webhook_can_save_an_owner_reply_to_a_guest_rsvp(): void
    {
        $guest = Guest::create([
            'name' => 'Ayu Putri',
            'phone' => '6281234567890',
            'max_guests' => 2,
        ]);
        Rsvp::create([
            'guest_id' => $guest->id,
            'presence' => 'hadir',
            'guest_count' => 1,
            'message' => 'Selamat berbahagia.',
        ]);

        $this->postJson('/api/v1/guests/'.$guest->id.'/rsvp/reply', [
            'owner_reply' => '  Terima kasih atas doa dan ucapannya.  ',
        ])->assertOk()
            ->assertJsonPath('data.owner_reply', 'Terima kasih atas doa dan ucapannya.');

        $this->assertDatabaseHas('rsvps', [
            'guest_id' => $guest->id,
            'owner_reply' => 'Terima kasih atas doa dan ucapannya.',
        ]);

        $this->getJson('/api/v1/guests/'.$guest->id)
            ->assertOk()
            ->assertJsonPath('data.rsvp.owner_reply', 'Terima kasih atas doa dan ucapannya.');
    }

    public function test_webhook_cannot_register_more_people_than_the_guest_limit(): void
    {
        $guest = Guest::create([
            'name' => 'Ayu Putri',
            'phone' => '6281234567890',
            'max_guests' => 2,
        ]);

        $this->postJson('/api/v1/guests/'.$guest->id.'/rsvp', [
            'presence' => 'hadir',
            'guest_count' => 3,
        ])->assertUnprocessable()
            ->assertJsonPath('message', 'Jumlah tamu melebihi batas pada undangan ini.');

        $this->assertDatabaseCount('rsvps', 0);
    }
}
