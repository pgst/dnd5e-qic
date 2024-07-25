<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\UserExaminationController;
use App\Http\Controllers\CompareController;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified']);

// Discordログイン用URL
Route::get('/auth/discord/redirect', function () {
    return Socialite::driver('discord')->redirect();
});

// Discordログイン用リダイレクト先
Route::get('/auth/discord/callback', function () {
    $socialiteUser = Socialite::driver('discord')->user();
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
    return redirect('/');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user-examination/start',
        [UserExaminationController::class, 'start'])->name('user-examination.start');
    Route::post('/user-examination/store',
        [UserExaminationController::class, 'store'])->name('user-examination.store');
    Route::get('/user-examination/{user_examination}/select',
        [UserExaminationController::class, 'select'])->name('user-examination.select');
    Route::patch('/user-examination/{user_examination}/update',
        [UserExaminationController::class, 'update'])->name('user-examination.update');
    Route::get('/user-examination/confirm',
        [UserExaminationController::class, 'confirm'])->name('user-examination.confirm');
    Route::post('/user-examination/result',
        [UserExaminationController::class, 'result'])->name('user-examination.result');
});

Route::resource('compare', CompareController::class)
    ->only('index')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/csv-import', [CsvImportController::class, 'show']);
    Route::post('/csv-import', [CsvImportController::class, 'import']);
});

require __DIR__.'/auth.php';
