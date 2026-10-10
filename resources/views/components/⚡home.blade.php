<?php

use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $title = 'Undangan Website Premium 06';

    #[Locked]
    public ?Guest $guest = null;

    #[Locked]
    public Collection $rsvpComments;

    public function mount(): void
    {
        $this->guest = Guest::where('invitation_token', request()->query('token'))->first();
        $this->rsvpComments = Rsvp::with('guest')->get();
    }
};
?>

<div>
    <div data-elementor-type="wp-page" data-elementor-id="32120" class="elementor elementor-32120" data-elementor-post-type="page">
    @include('partials.invitation.cover')

		<div class="elementor-element elementor-element-258985b9 e-con-full e-flex e-con e-parent" data-id="258985b9" data-element_type="container" data-e-type="container">
        @include('partials.invitation.desktop-cover')

            <div class="elementor-element elementor-element-6626d23e e-con-full e-flex e-con e-child" data-id="6626d23e" data-element_type="container" data-e-type="container">
            @include('partials.invitation.opening')
            @include('partials.invitation.couple')
            @include('partials.invitation.save-the-date')
            @include('partials.invitation.events')
            @include('partials.invitation.love-story')
            @include('partials.invitation.gallery')

            <div class="elementor-element elementor-element-51cdfcef e-con-full e-flex e-con e-child" data-id="51cdfcef" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                @include('partials.invitation.digital-envelope')
                @include('partials.invitation.rsvp')
            </div>

            @include('partials.invitation.closing')
            @include('partials.invitation.footer')
        </div>
    </div>
</div>