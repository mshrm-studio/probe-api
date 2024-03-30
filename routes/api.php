<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Actions\GetLilNounByTokenId;
use App\Http\Controllers\Actions\GetNounByTokenId;
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
Route::get('lil-noun-traits', [LilNounTraitController::class, 'index']);

Route::get('nouns', [NounController::class, 'index']);
Route::get('nouns/{token_id}', GetNounByTokenId::class);
Route::get('noun-traits', [NounTraitController::class, 'index']);