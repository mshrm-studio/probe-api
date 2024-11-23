<?php

namespace App\Http\Controllers\Actions\WarpcastFrames;

use App\Http\Controllers\Controller;
use App\Models\Noun;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RandomNoun extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $noun = Noun::inRandomOrder()->first();

        $nounPng = Storage::url('staging/nouns/pngs/' . $noun->token_id . '.png');

        return view('warpcast-frames.nouns.random', [
            'noun' => $noun,
            'nounPng' => $nounPng
        ]);
    }
}
