<?php

namespace App\Http\Controllers\Actions\WarpcastFrames;

use App\Http\Controllers\Controller;
use App\Models\Noun;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NounsCatalogue extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $noun = Noun::orderBy('token_id', 'asc')->firstOrFail();

        $nounPng = Storage::url('staging/nouns/pngs/' . $noun->token_id . '.png');

        $lastNoun = Noun::orderBy('token_id', 'desc')->firstOrFail();

        return view('warpcast-frames.nouns.catalogue', [
            'hasMore' => $noun->token_id < $lastNoun->token_id ? true : false,
            'noun' => $noun,
            'nounPng' => $nounPng
        ]);
    }
}
