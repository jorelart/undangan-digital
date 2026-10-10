<?php

use Illuminate\Support\Facades\Route;


Route::livewire('/', 'home')->name('home');
Route::livewire('/livewire', 'home')->name('livewire');