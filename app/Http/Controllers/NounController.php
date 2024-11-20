<?php

namespace App\Http\Controllers;

use App\Http\Requests\Noun\GetNounsRequest;
use App\Http\Requests\Noun\StoreNounRequest;
use App\Http\Requests\Noun\UpdateNounRequest;
use App\Models\Noun;
use Illuminate\Http\Request;
use App\Http\Resources\NounResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NounController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetNounsRequest $request): AnonymousResourceCollection
    {
        $accessory = $request->input('accessory', null);
        $body = $request->input('body', null);
        $color = $request->input('color', null);
        $glasses = $request->input('glasses', null);
        $head = $request->input('head', null);
        $background = $request->input('background', null);

        $search = is_string($request->search) ? explode(',', $request->search) : null;
        $perPage = $request->input('per_page', 25);
        $sortProperty = $request->input('sort_property', 'token_id');
        $sortMethod = $request->input('sort_method', 'desc');

        $nouns = Noun::query()
            ->select([
                'accessory_index',
                'accessory_name',
                'area',
                'background_index',
                'background_name',
                'block_number',
                'body_index',
                'body_name',
                'color_histogram',
                'glasses_index',
                'glasses_name',
                'head_index',
                'head_name',
                'index',
                'minted_at',
                'png_path',
                'svg_path',
                'token_id',
                'token_id_last_synced_at',
                'token_uri',
                'weight',
            ])
            ->whereNotNull('accessory_name')
            ->whereNotNull('accessory_index')
            ->whereNotNull('block_number')
            ->whereNotNull('body_name')
            ->whereNotNull('body_index')
            ->whereNotNull('background_name')
            ->whereNotNull('background_index')
            ->whereNotNull('glasses_name')
            ->whereNotNull('glasses_index')
            ->whereNotNull('head_name')
            ->whereNotNull('head_index')
            ->whereNotNull('minted_at')
            ->whereNotNull('token_id')
            ->when(is_array($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    foreach ($search as $term) {
                        $query->orWhere(function ($subQuery) use ($term) {
                            $subQuery
                                ->where('background_name', 'like', '%' . $term . '%')
                                ->orWhere('head_name', 'like', '%' . $term . '%')
                                ->orWhere('accessory_name', 'like', '%' . $term . '%')
                                ->orWhere('body_name', 'like', '%' . $term . '%')
                                ->orWhere('glasses_name', 'like', '%' . $term . '%');
                        });
                    }
                });
            })
            ->when(is_string($accessory), function ($query) use ($accessory) {
                $query->where('accessory_name', $accessory);
            })
            ->when(is_string($body), function ($query) use ($body) {
                $query->where('body_name', $body);
            })
            ->when(is_string($color), function ($query) use ($color) {
                $query->whereRaw("JSON_CONTAINS_PATH(color_histogram, 'one', CONCAT('$.\"', ?, '\"')) = 1", [$color])->get();

            })
            ->when(is_string($glasses), function ($query) use ($glasses) {
                $query->where('glasses_name', $glasses);
            })
            ->when(is_string($head), function ($query) use ($head) {
                $query->where('head_name', $head);
            })
            ->when(is_string($background), function ($query) use ($background) {
                $query->where('background_name', $background);
            })
            ->orderBy($sortProperty, $sortMethod)
            ->paginate($perPage);

        return NounResource::collection($nouns);
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
    public function store(StoreNounRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Noun $noun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Noun $noun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNounRequest $request, Noun $noun)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Noun $noun)
    {
        //
    }
}
