<?php

namespace App\Http\Controllers;

use App\Http\Requests\DreamNoun\GetDreamNounsRequest;
use App\Http\Requests\DreamNoun\StoreDreamNounRequest;
use App\Http\Requests\DreamNoun\UpdateDreamNounRequest;
use App\Models\DreamNoun;
use App\Http\Resources\DreamNounResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

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

        $search = $request->input('search', null);

        $perPage = $request->input('per_page', 25);
        $sortProperty = $request->input('sort_property', 'created_at');
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
            ->when($search, function ($query) use ($search) {
                $query
                    ->whereHas('accessory', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('background', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('body', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('glasses', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('head', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
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
        $dreamersDreamExists = DreamNoun::query()
            ->where('accessory_seed_id', $request->accessory_seed_id)
            ->where('background_seed_id', $request->background_seed_id)
            ->where('body_seed_id', $request->body_seed_id)
            ->where('dreamer', $request->dreamer)
            ->where('glasses_seed_id', $request->glasses_seed_id)
            ->where('head_seed_id', $request->head_seed_id)
            ->exists();

        if ($dreamersDreamExists) {
            throw ValidationException::withMessages([
                'dreamer' => 'This dreamer already has a dream with these traits.',
            ]);
        }

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
