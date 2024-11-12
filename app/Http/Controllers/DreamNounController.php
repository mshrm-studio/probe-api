<?php

namespace App\Http\Controllers;

use App\Http\Requests\DreamNoun\GetDreamNounsRequest;
use App\Http\Requests\DreamNoun\StoreDreamNounRequest;
use App\Http\Requests\DreamNoun\UpdateDreamNounRequest;
use App\Models\DreamNoun;
use App\Http\Resources\DreamNounResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DreamNounController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetDreamNounsRequest $request): AnonymousResourceCollection
    {
        $accessorySeedId = $request->input('accessory_seed_id', null);
        $backgroundSeedId = $request->input('background_seed_id', null);
        $bodySeedId = $request->input('body_seed_id', null);
        $dreamer = $request->input('dreamer', null);
        $glassesSeedId = $request->input('glasses_seed_id', null);
        $headSeedId = $request->input('head_seed_id', null);

        $perPage = $request->input('per_page', 25);
        $sortProperty = $request->input('sort_property', 'token_id');
        $sortMethod = $request->input('sort_method', 'desc');

        $dreamNouns = DreamNoun::query()
            ->when($accessorySeedId, function ($query) use ($accessorySeedId) {
                $query->where('accessory_seed_id', $accessorySeedId);
            })
            ->when($backgroundSeedId, function ($query) use ($backgroundSeedId) {
                $query->where('background_seed_id', $backgroundSeedId);
            })
            ->when($bodySeedId, function ($query) use ($bodySeedId) {
                $query->where('body_seed_id', $bodySeedId);
            })
            ->when($dreamer, function ($query) use ($dreamer) {
                $query->where('dreamer', $dreamer);
            })
            ->when($glassesSeedId, function ($query) use ($glassesSeedId) {
                $query->where('glasses_seed_id', $glassesSeedId);
            })
            ->when($headSeedId, function ($query) use ($headSeedId) {
                $query->where('head_seed_id', $headSeedId);
            })
            ->orderBy($sortProperty, $sortMethod)
            ->paginate($perPage);
        
        return DreamNounResource::collection($dreamNouns);
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
    public function store(StoreDreamNounRequest $request): DreamNounResource
    {
        $dreamNoun = DreamNoun::create($request->validated());

        return new DreamNounResource($dreamNoun);
    }

    /**
     * Display the specified resource.
     */
    public function show(DreamNoun $dreamNoun): DreamNounResource
    {
        $dreamNoun->load('accessory', 'background', 'body', 'glasses', 'head');

        return new DreamNounResource($dreamNoun);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DreamNoun $dreamNoun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDreamNounRequest $request, DreamNoun $dreamNoun): DreamNounResource
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DreamNoun $dreamNoun)
    {
        //
    }
}
