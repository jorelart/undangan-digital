<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = [
        'guest_name',
        'attendance',
        'guest_count',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'guest_count' => 'integer',
        ];
    }
}
