<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetLilNounsTraitsRequest;
use App\Services\LilNounsTraitService;
use Illuminate\Http\JsonResponse;

class GetLilNounsTraits extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetLilNounsTraitsRequest $request): JsonResponse
    {
        $traitService = new LilNounsTraitService();

        $traits = $traitService->getItems();

        if ($request->has('layer')) {
            $traits = $traits->where('layer', $request->layer);
        }

        return response()->json($traits->sortBy('name')->values());
    }
}
