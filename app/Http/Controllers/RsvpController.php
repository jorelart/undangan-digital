<?php

namespace App\Http\Controllers;

use App\Jobs\SendRsvpWhatsAppNotification;
use App\Models\Rsvp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RsvpController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:120'],
            'attendance' => ['required', Rule::in(['attending', 'not_attending'])],
            'guest_count' => ['required_if:attendance,attending', 'nullable', 'integer', 'min:1', 'max:10'],
            'message' => ['nullable', 'string', 'max:500'],
        ], [
            'guest_name.required' => 'Nama perlu diisi.',
            'attendance.required' => 'Silakan pilih konfirmasi kehadiran.',
            'guest_count.required_if' => 'Jumlah tamu perlu diisi.',
            'guest_count.max' => 'Jumlah tamu maksimal 10 orang.',
            'message.max' => 'Ucapan maksimal 500 karakter.',
        ]);

        $rsvp = Rsvp::create([
            'guest_name' => $validated['guest_name'],
            'attendance' => $validated['attendance'],
            'guest_count' => $validated['attendance'] === 'attending'
                ? (int) $validated['guest_count']
                : 0,
            'message' => $validated['message'] ?? null,
        ]);

        $whatsapp = config('services.whatsapp');
        $whatsappConfigured = filled($whatsapp['token'])
            && filled($whatsapp['phone_number_id'])
            && filled($whatsapp['owner_phone'])
            && filled($whatsapp['template_name']);

        if ($whatsappConfigured) {
            SendRsvpWhatsAppNotification::dispatch($rsvp->id)->afterCommit();
            $status = 'Terima kasih! Konfirmasi Anda berhasil disimpan dan notifikasi WhatsApp sedang diproses.';
        } else {
            $status = 'Terima kasih! Konfirmasi kehadiran Anda berhasil disimpan. Notifikasi WhatsApp belum aktif.';
        }

        return redirect('/#rsvp')->with('status', $status);
    }
}
