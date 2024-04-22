<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\LilNounResource;
use App\Models\LilNoun;

class GetLilNounByTokenId extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $tokenId): JsonResponse|LilNounResource
    {
        $noun = LilNoun::where('token_id', $tokenId)->firstOrFail();

        return new LilNounResource($noun);
    }
}
