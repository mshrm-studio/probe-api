<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Actions\GetLilNounByTokenId;
use App\Http\Controllers\Actions\GetLilNounColors;
use App\Http\Controllers\Actions\GetNounByTokenId;
use App\Http\Controllers\Actions\GetNounColors;
use App\Http\Controllers\Actions\WarpcastFrames\NextNoun;
use App\Http\Controllers\Actions\WarpcastFrames\NounsCatalogue;
use App\Http\Controllers\Actions\WarpcastFrames\RandomLilNoun;
use App\Http\Controllers\Actions\WarpcastFrames\RandomNoun;
use App\Http\Controllers\DreamNounController;
use App\Http\Controllers\LilNounController;
use App\Http\Controllers\LilNounTraitController;
use App\Http\Controllers\NounController;
use App\Http\Controllers\NounTraitController;

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

Route::get('dream-nouns', [DreamNounController::class, 'index']);
Route::post('dream-nouns', [DreamNounController::class, 'store']);

/**
 * Warpcast Frames
 */

Route::get('/warpcast-frames/probe', function () {
    return view('warpcast-frames.probe');
});

Route::get('/warpcast-frames/lil-nouns/gifs/get-funding-2day', function () {
    return view('warpcast-frames.lil-nouns.gifs.get-funding-2day');
});

Route::get('/warpcast-frames/lil-nouns/random', RandomLilNoun::class);
Route::post('/warpcast-frames/lil-nouns/random', RandomLilNoun::class);
Route::get('/warpcast-frames/nouns/random', RandomNoun::class);
Route::post('/warpcast-frames/nouns/random', RandomNoun::class);
Route::get('/warpcast-frames/nouns/catalogue', NounsCatalogue::class);
Route::post('/warpcast-frames/nouns/next/{token}', NextNoun::class);
