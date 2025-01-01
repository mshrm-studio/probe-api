<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Noun;
use Illuminate\Support\Facades\Cache;

class GetNounSettlers extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {        
        $settlers = Cache::remember('Noun-Settlers', now()->addDay(), function () {
            $addresses = Noun::whereNotNull('settled_by_address')
                ->pluck('settled_by_address')
                ->toArray();

            return array_values(array_unique(array_map('strtolower', $addresses)));
        });
    
        return response()->json($settlers);
    }
}
