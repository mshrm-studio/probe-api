<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Actions\GetLilNounByTokenId;
use App\Http\Controllers\Actions\GetLilNounColors;
use App\Http\Controllers\Actions\GetNounByTokenId;
use App\Http\Controllers\Actions\GetNounColors;
use App\Http\Controllers\LilNounController;
use App\Http\Controllers\LilNounTraitController;
use App\Http\Controllers\NounController;
use App\Http\Controllers\NounTraitController;

use App\Models\LilNoun;
use App\Models\Noun;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('lil-nouns', [LilNounController::class, 'index']);
Route::get('lil-nouns/{token_id}', GetLilNounByTokenId::class);
Route::get('lil-noun-colors', GetLilNounColors::class);
Route::get('lil-noun-traits', [LilNounTraitController::class, 'index']);

Route::get('nouns', [NounController::class, 'index']);
Route::get('nouns/{token_id}', GetNounByTokenId::class);
Route::get('noun-colors', GetNounColors::class);
Route::get('noun-traits', [NounTraitController::class, 'index']);

/**
 * Warpcast Frames
 */

Route::get('/warpcast-frames/probe', function () {
    return view('warpcast-frames.probe');
});

Route::get('/warpcast-frames/lil-nouns/gifs/get-funding-2day', function () {
    return view('warpcast-frames.lil-nouns.gifs.get-funding-2day');
});

Route::get('/warpcast-frames/lil-nouns/random', function () {  
    $lilNoun = LilNoun::where('token_id', '<', 7200)->inRandomOrder()->first();

    $lilNounPng = Storage::url('staging/lils/pngs/' . $lilNoun->token_id . '.png');

    return view('warpcast-frames.lil-nouns.random', [
        'lilNoun' => $lilNoun,
        'lilNounPng' => $lilNounPng
    ]);
});

Route::post('/warpcast-frames/lil-nouns/random', function () {
    $lilNoun = LilNoun::where('token_id', '<', 7200)->inRandomOrder()->first();

    $lilNounPng = Storage::url('staging/lils/pngs/' . $lilNoun->token_id . '.png');

    return view('warpcast-frames.lil-nouns.random', [
        'lilNoun' => $lilNoun,
        'lilNounPng' => $lilNounPng
    ]);
});

Route::get('/warpcast-frames/nouns/random', function () {
    // \Log::info('*********');
    // \Log::info(request()->headers->all());
    // \Log::info(request()->all());
    // \Log::info(request()->input('trustedData', 'no trusted data'));
    // \Log::info(request()->input('untrustedData', 'no untrusted data'));
   
    $noun = Noun::inRandomOrder()->first();

    $nounPng = Storage::url('staging/nouns/pngs/' . $noun->token_id . '.png');

    return view('warpcast-frames.nouns.random', [
        'noun' => $noun,
        'nounPng' => $nounPng
    ]);
});

Route::post('/warpcast-frames/nouns/random', function () {
    $noun = Noun::inRandomOrder()->first();

    $nounPng = Storage::url('staging/nouns/pngs/' . $noun->token_id . '.png');

    return view('warpcast-frames.nouns.random', [
        'noun' => $noun,
        'nounPng' => $nounPng
    ]);
});

Route::get('/warpcast-frames/nouns/catalogue', function () {  
    $noun = Noun::orderBy('token_id', 'asc')->firstOrFail();

    $nounPng = Storage::url('staging/nouns/pngs/' . $noun->token_id . '.png');

    $lastNoun = Noun::orderBy('token_id', 'desc')->firstOrFail();

    return view('warpcast-frames.nouns.catalogue', [
        'hasMore' => $noun->token_id < $lastNoun->token_id ? true : false,
        'noun' => $noun,
        'nounPng' => $nounPng
    ]);
});

Route::post('/warpcast-frames/nouns/next/{token}', function (string $token) {       
    $currentNounTokenId = intval($token);

    if ($currentNounTokenId < 0) {
        throw new \Exception('Invalid Noun');
    }

    $lastNoun = Noun::orderBy('token_id', 'desc')->firstOrFail();

    if ($currentNounTokenId > $lastNoun->token_id) {
        throw new \Exception('Invalid Noun');
    }

    if ($currentNounTokenId == $lastNoun->token_id) {
        throw new \Exception('No next Noun');
    }
    
    $nextNoun = Noun::where('token_id', $currentNounTokenId + 1)->firstOrFail();

    $nextNounPng = Storage::url('staging/nouns/pngs/' . $nextNoun->token_id . '.png');

    return view('warpcast-frames.nouns.catalogue', [
        'hasMore' => $nextNoun->token_id < $lastNoun->token_id ? true : false,
        'noun' => $nextNoun,
        'nounPng' => $nextNounPng
    ]);
});
