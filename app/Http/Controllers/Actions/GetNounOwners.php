<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Noun;
use Illuminate\Support\Facades\Cache;

class GetNounOwners extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {        
        $owners = Cache::remember('Noun-Owners', now()->addDay(), function () {
            $addresses = Noun::whereNotNull('owner_address')
                ->pluck('owner_address')
                ->toArray();

            return array_values(array_unique(array_map('strtolower', $addresses)));
        });
    
        return response()->json($owners);
    }
}
