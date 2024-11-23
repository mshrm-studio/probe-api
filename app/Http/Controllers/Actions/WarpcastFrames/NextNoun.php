<?php

namespace App\Http\Controllers\Actions\WarpcastFrames;

use App\Http\Controllers\Controller;
use App\Models\Noun;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NextNoun extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $token): View
    {
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
    }
}
