<?php

use Illuminate\Support\Facades\Route;

use App\Models\Noun;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/warpcast-frames/probe', function () {
    return view('warpcast-frames.probe');
});

Route::get('/warpcast-frames/random-noun', function () {
    $noun = Noun::where('token_id', '<', 150)->inRandomOrder()->first();

    $nounPng = Storage::temporaryUrl('staging/nouns/pngs/' . $noun->token_id . '.png', now()->addMinutes(60));

    return view('warpcast-frames.random-noun', [
        'nounPng' => $nounPng
    ]);
});

Route::post('/warpcast-frames/random-noun', function () {
    $noun = Noun::where('token_id', '<', 150)->inRandomOrder()->first();

    $nounPng = Storage::temporaryUrl('staging/nouns/pngs/' . $noun->token_id . '.png', now()->addMinutes(60));

    return view('warpcast-frames.random-noun', [
        'nounPng' => $nounPng
    ]);
});
