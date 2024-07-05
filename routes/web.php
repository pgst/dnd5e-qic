<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Auth\LoginController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

// Discordログイン用URL
Route::get('/auth/discord/redirect', function () {
    return Socialite::driver('discord')->redirect();
});

// Discordログイン用リダイレクト先
Route::get('/auth/discord/callback', function () {
    $socialiteUser = Socialite::driver('discord')->user();
    return dd($socialiteUser);
});