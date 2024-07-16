<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\UserExaminationController;

Route::get('/', function () {
    // return view('welcome'); // Livewire実験用にトップページを表示
    return redirect(route('user-examination.start'));
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
        'password' => bcrypt(env('DISCORD_DUMMY_PASSWORD')), // ダミーのパスワードを設定
    ]);

    Auth::login($user);

    // return redirect('/dashboard');
    return redirect(route('user-examination.start'));
});

Route::get('/import-csv', [CsvImportController::class, 'show']);
Route::post('/import-csv', [CsvImportController::class, 'import']);

Route::resource('user-examination', UserExaminationController::class, ['only' => ['store', 'update']])
    ->middleware(['auth', 'verified']);
Route::get('user-examination/start', [UserExaminationController::class, 'start'])
    ->name('user-examination.start');
Route::get('user-examination/{user_examination}/select', [UserExaminationController::class, 'select'])
    ->name('user-examination.select');
Route::get('user-examination/confirm', [UserExaminationController::class, 'confirm'])
    ->name('user-examination.confirm');
Route::post('user-examination/result', [UserExaminationController::class, 'result'])
    ->name('user-examination.result');

Route::get('/dashboard', function () {
    // return view('dashboard');
    return redirect(route('user-examination.create'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
