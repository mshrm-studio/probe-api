<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Noun;
use Illuminate\Support\Facades\Cache;

class GetNounColors extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $colors = Cache::remember('Noun-Colors', now()->addWeek(), function () {
            $colorHistograms = Noun::pluck('color_histogram');

            $allColors = [];
    
            foreach ($colorHistograms as $histogram) {
                if (is_array($histogram)) {
                    $allColors = array_merge($allColors, array_keys($histogram)); // Directly merge the keys (colors) into allColors array
                }
            }
    
            $uniqueColors = array_unique($allColors); // Filter out duplicate colors
            return array_values($uniqueColors); // Re-index array to ensure JSON array format
        });
    
        return response()->json($colors); // Return unique colors as JSON
    }
}
