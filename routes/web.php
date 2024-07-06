<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

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
    // dd($socialiteUser);
    $user = User::updateOrCreate([
        'provider_id' => $socialiteUser->id,
        'provider' => 'discord',
    ], [
        'name' => $socialiteUser->name,
        'email' => $socialiteUser->email,
        'img_url' => $socialiteUser->avatar,
        'password' => bcrypt('dummy_password'), // ダミーのパスワードを設定
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
