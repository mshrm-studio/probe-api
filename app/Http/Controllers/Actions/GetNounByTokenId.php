<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\NounResource;
use App\Models\Noun;

class GetNounByTokenId extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $tokenId): JsonResponse|NounResource
    {
        $noun = Noun::where('token_id', $tokenId)->firstOrFail();

        return new NounResource($noun);
    }
}
