<?php

namespace App\Http\Controllers;

use App\Http\Requests\LilNounTrait\GetLilNounTraitsRequest;
use App\Http\Requests\LilNounTrait\StoreLilNounTraitRequest;
use App\Http\Requests\LilNounTrait\UpdateLilNounTraitRequest;
use App\Models\LilNounTrait;
use App\Http\Resources\LilNounTraitResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LilNounTraitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetLilNounTraitsRequest $request): AnonymousResourceCollection
    {
        $layer = $request->input('layer', null);
        $perPage = $request->input('per_page', 25);
        $sortProperty = $request->input('sort_property', 'name');
        $sortMethod = $request->input('sort_method', 'asc');

        $traits = LilNounTrait::query()
            ->when($layer, fn($query, $layer) => $query->where('layer', $layer))
            ->orderBy($sortProperty, $sortMethod)
            ->paginate($perPage);

        return LilNounTraitResource::collection($traits);
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
    public function store(StoreLilNounTraitRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LilNounTrait $lilNounTrait)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LilNounTrait $lilNounTrait)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLilNounTraitRequest $request, LilNounTrait $lilNounTrait)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LilNounTrait $lilNounTrait)
    {
        //
    }
}
