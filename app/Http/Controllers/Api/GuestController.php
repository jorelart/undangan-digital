<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Guest::query()
            ->with('rsvp:id,guest_id,presence,guest_count,message,owner_reply,created_at')
            ->orderByDesc('id');

        if (! empty($validated['q'])) {
            $term = $validated['q'];
            $phoneTerm = preg_replace('/\D+/', '', $term);
            $query->where(function ($builder) use ($term, $phoneTerm): void {
                $builder->where('name', 'like', '%'.$term.'%');

                if (strlen($phoneTerm) >= 8) {
                    $builder->orWhere('phone', 'like', '%'.$phoneTerm.'%');
                }
            });
        }

        if (! empty($validated['phone'])) {
            $phone = preg_replace('/\D+/', '', $validated['phone']);
            $query->where('phone', $phone);
        }

        $guests = $query->paginate($validated['per_page'] ?? 25)
            ->through(fn (Guest $guest): array => $this->guestData($guest));

        return response()->json($guests);
    }

    public function show(Guest $guest): JsonResponse
    {
        $guest->load('rsvp:id,guest_id,presence,guest_count,message,owner_reply,created_at');

        return response()->json([
            'data' => $this->guestData($guest),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $request->merge([
            'phone' => is_string($phone) ? preg_replace('/\D+/', '', $phone) : $phone,
        ]);

        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'digits_between:8,20', Rule::unique('guests', 'phone')],
            'max_guests' => ['sometimes', 'integer', 'min:1', 'max:20'],
        ])->validate();

        $guest = Guest::create([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'],
            'max_guests' => $validated['max_guests'] ?? 1,
            'invitation_token' => Str::random(64),
        ]);

        return response()->json([
            'message' => 'Tamu berhasil dibuat.',
            'data' => $this->guestData($guest),
            'invitation_token' => $guest->invitation_token,
            'invitation_url' => rtrim(config('app.url'), '/').'/#token='.$guest->invitation_token,
        ], 201);
    }

    public function storeRsvp(Request $request, Guest $guest): JsonResponse
    {
        $validated = $request->validate([
            'presence' => ['required', Rule::in(['hadir', 'tidak'])],
            'guest_count' => ['exclude_unless:presence,hadir', 'required', 'integer', 'min:1', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $presence = $validated['presence'];
        $guestCount = $presence === 'hadir' ? $validated['guest_count'] : 0;

        if ($guestCount > $guest->max_guests) {
            return response()->json([
                'message' => 'Jumlah tamu melebihi batas pada undangan ini.',
                'errors' => [
                    'guest_count' => ['Jumlah tamu melebihi batas pada undangan ini.'],
                ],
            ], 422);
        }

        $rsvp = DB::transaction(fn (): Rsvp => Rsvp::updateOrCreate(
            ['guest_id' => $guest->id],
            [
                'presence' => $presence,
                'guest_count' => $guestCount,
                'message' => $validated['message'] ?? '',
            ],
        ));

        return response()->json([
            'message' => 'RSVP tamu berhasil diperbarui.',
            'data' => [
                'guest_id' => $guest->id,
                'presence' => $rsvp->presence,
                'guest_count' => $rsvp->guest_count,
                'message' => $rsvp->message,
                'updated_at' => $rsvp->updated_at?->toIso8601String(),
            ],
        ]);
    }

    public function storeRsvpReply(Request $request, Guest $guest): JsonResponse
    {
        $validated = $request->validate([
            'owner_reply' => ['required', 'string', 'max:1000'],
        ]);

        $rsvp = $guest->rsvp;

        if (! $rsvp) {
            return response()->json([
                'message' => 'RSVP tamu belum tersedia.',
            ], 404);
        }

        $rsvp->update([
            'owner_reply' => trim($validated['owner_reply']),
        ]);

        return response()->json([
            'message' => 'Balasan berhasil disimpan.',
            'data' => [
                'guest_id' => $guest->id,
                'owner_reply' => $rsvp->owner_reply,
            ],
        ]);
    }

    private function guestData(Guest $guest): array
    {
        return [
            'id' => $guest->id,
            'name' => $guest->name,
            'phone' => $guest->phone,
            'max_guests' => $guest->max_guests,
            'rsvp' => $guest->rsvp ? [
                'presence' => $guest->rsvp->presence,
                'guest_count' => $guest->rsvp->guest_count,
                'message' => $guest->rsvp->message,
                'owner_reply' => $guest->rsvp->owner_reply,
                'updated_at' => $guest->rsvp->updated_at?->toIso8601String(),
            ] : null,
            'created_at' => $guest->created_at?->toIso8601String(),
        ];
    }
}
