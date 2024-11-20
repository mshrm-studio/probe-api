<?php

namespace App\Http\Controllers;

use App\Http\Requests\LilNoun\GetLilNounsRequest;
use App\Http\Requests\LilNoun\StoreLilNounRequest;
use App\Http\Requests\LilNoun\UpdateLilNounRequest;
use App\Models\LilNoun;
use Illuminate\Http\Request;
use App\Http\Resources\LilNounResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LilNounController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetLilNounsRequest $request): AnonymousResourceCollection
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

        $lilNouns = LilNoun::query()
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
                'id',
                'index',
                'minted_at',
                'png_path',
                'svg_path',
                'token_id',
                'token_id_last_synced_at',
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

        return LilNounResource::collection($lilNouns);
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
    public function store(StoreLilNounRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LilNoun $lilNoun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LilNoun $lilNoun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLilNounRequest $request, LilNoun $lilNoun)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LilNoun $lilNoun)
    {
        //
    }
}
