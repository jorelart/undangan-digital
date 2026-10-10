<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Http\Request;

class Home extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'title' => 'Undangan Website Premium 06',
            'guest' => Guest::where('invitation_token', request()->query('token'))->first(),
            'rsvpComments' => Rsvp::with('guest')->get()
        ];
    }

    public function index()
    {
        // dd($this->data['rsvpComments']);
        return view('home', $this->data);
    }
}
