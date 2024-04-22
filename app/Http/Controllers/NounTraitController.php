<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNounTraitRequest;
use App\Http\Requests\UpdateNounTraitRequest;
use App\Models\NounTrait;
use Illuminate\View\View;
use App\Http\Resources\NounTraitResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\GetNounTraitsRequest;

class NounTraitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetNounTraitsRequest $request): AnonymousResourceCollection|View
    {
        $layer = $request->input('layer', null);
        $perPage = $request->input('per_page', 25);
        $sortProperty = $request->input('sort_property', 'name');
        $sortMethod = $request->input('sort_method', 'asc');

        $traits = NounTrait::query()
            ->when($layer, fn($query, $layer) => $query->where('layer', $layer))
            ->orderBy($sortProperty, $sortMethod)
            ->paginate($perPage);

        if ($request->expectsJson()) {
            return NounTraitResource::collection($traits);
        }
        
        return view('welcome', ['traits' => $traits]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNounTraitRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NounTrait $nounTrait)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NounTrait $nounTrait)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNounTraitRequest $request, NounTrait $nounTrait)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NounTrait $nounTrait)
    {
        //
    }
}
