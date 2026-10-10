<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'max_guests',
        'invitation_token',
    ];

    public function rsvp(): HasOne
    {
        return $this->hasOne(Rsvp::class);
    }
}
