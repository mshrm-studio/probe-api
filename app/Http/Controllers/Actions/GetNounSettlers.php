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
            return Noun::whereNotNull('settled_by_address') // Ignore null values
                ->pluck('settled_by_address')
                ->map(fn($address) => strtolower($address))
                ->unqiue()
                ->values();
        });
    
        return response()->json($settlers);
    }
}
