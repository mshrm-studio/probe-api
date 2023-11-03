<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetNounsTraitsRequest;
use App\Services\NounsTraitService;
use Illuminate\Http\JsonResponse;

class GetNounsTraits extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetNounsTraitsRequest $request): JsonResponse
    {
        $traitService = new NounsTraitService();

        $traits = $traitService->getItems();

        if ($request->has('layer')) {
            $traits = $traits->where('layer', $request->layer);
        }

        return response()->json($traits->values());
    }
}
