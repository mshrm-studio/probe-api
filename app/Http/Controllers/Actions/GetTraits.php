<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GetTraitsRequest;
use App\Services\TraitService;
use Illuminate\Http\JsonResponse;

class GetTraits extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetTraitsRequest $request): JsonResponse
    {
        $traitService = new TraitService();

        $traits = $traitService->getItems();

        if ($request->has('layer')) {
            $traits = $traits->where('layer', $request->layer);
        }

        return response()->json($traits->values());
    }
}
