<?php

namespace App\Http\Controllers\Actions\WarpcastFrames;

use App\Http\Controllers\Controller;
use App\Models\LilNoun;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RandomLilNoun extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $lilNoun = LilNoun::where('token_id', '<', 7200)->inRandomOrder()->first();

        $lilNounPng = Storage::url('staging/lils/pngs/' . $lilNoun->token_id . '.png');

        return view('warpcast-frames.lil-nouns.random', [
            'lilNoun' => $lilNoun,
            'lilNounPng' => $lilNounPng
        ]);
    }
}
