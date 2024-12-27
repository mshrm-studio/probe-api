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
            $settlers = Noun::pluck('settled_by_address')->toArray();    
            $uniqueSettlers = array_unique($settlers); // Filter out duplicate colors
            return array_values($uniqueSettlers); // Re-index array to ensure JSON array format
        });
    
        return response()->json($settlers); // Return unique colors as JSON
    }
}
