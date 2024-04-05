<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LilNounController;
use App\Http\Controllers\Actions\GetLilNounsTraits;
use App\Http\Controllers\NounController;
use App\Http\Controllers\Actions\GetNounsTraits;

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
Route::get('lil-nouns-traits', GetLilNounsTraits::class);

Route::get('nouns', [NounController::class, 'index']);
Route::get('nouns-traits', GetNounsTraits::class);

/**
 * Warpcast Frames
 */

Route::get('/warpcast-frames/probe', function () {
    return view('warpcast-frames.probe');
});

Route::get('/warpcast-frames/random-noun', function () {
    \Log::info('*********');
    \Log::info(request()->headers->all());
    \Log::info(request()->all());
    \Log::info(request()->input('trustedData', 'no trusted data'));
    \Log::info(request()->input('untrustedData', 'no untrusted data'));

    $response = Http::post('https://hub.farcaster.xyz/validateMessage', [
        'messageBytes' => request()->input('trustedData.messageBytes'),
    ]);
    
    \Log::info($response->body());

    if ($response->successful()) {
        $validationResult = $response->json();
        
        if (isset($validationResult['isValid']) && $validationResult['isValid']) {
            $noun = Noun::where('token_id', '<', 150)->inRandomOrder()->first();

            $nounPng = Storage::temporaryUrl('staging/nouns/pngs/' . $noun->token_id . '.png', now()->addMinutes(60));

            return view('warpcast-frames.random-noun', [
                'nounPng' => $nounPng
            ]);
        } else {
            throw new \Exception('Message is not valid');
        }
    } else {
        // The API call failed; handle the error
        throw new \Exception('Validation request failed.');
    }    
});

Route::post('/warpcast-frames/random-noun', function () {
    \Log::info('*********');
    \Log::info(request()->headers->all());
    \Log::info(request()->all());
    \Log::info(request()->input('trustedData', 'no trusted data'));
    \Log::info(request()->input('untrustedData', 'no untrusted data'));

    $response = Http::post('https://hub.farcaster.xyz/validateMessage', [
        'messageBytes' => request()->input('trustedData.messageBytes'),
    ]);
    
    \Log::info($response->body());

    if ($response->successful()) {
        $validationResult = $response->json();
        
        if (isset($validationResult['isValid']) && $validationResult['isValid']) {
            $noun = Noun::where('token_id', '<', 150)->inRandomOrder()->first();

            $nounPng = Storage::temporaryUrl('staging/nouns/pngs/' . $noun->token_id . '.png', now()->addMinutes(60));

            return view('warpcast-frames.random-noun', [
                'nounPng' => $nounPng
            ]);
        } else {
            throw new \Exception('Message is not valid');
        }
    } else {
        // The API call failed; handle the error
        throw new \Exception('Validation request failed.');
    }
});