<?php

use Illuminate\Support\Facades\Route;

use App\Models\Noun;

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
    $noun = Noun::inRandomOrder()->first();

    return view('warpcast-frames.random-noun', [
        'noun' => $noun
    ]);
});

Route::post('/warpcast-frames/random-noun', function () {
    $noun = Noun::inRandomOrder()->first();

    return view('warpcast-frames.random-noun', [
        'noun' => $noun
    ]);
});
