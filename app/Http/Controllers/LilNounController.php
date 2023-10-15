<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLilNounRequest;
use App\Http\Requests\UpdateLilNounRequest;
use App\Models\LilNoun;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LilNounController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->search ?? null;
        $accessory = $request->accessory ?? null;
        $body = $request->body ?? null;
        $glasses = $request->glasses ?? null;
        $head = $request->head ?? null;
        $background = $request->background ?? null;

        $lilNouns = LilNoun::query()
            ->when(is_string($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('background_name', 'like', '%' . $search . '%')
                        ->orWhere('head_name', 'like', '%' . $search . '%')
                        ->orWhere('accessory_name', 'like', '%' . $search . '%')
                        ->orWhere('body_name', 'like', '%' . $search . '%')
                        ->orWhere('glasses_name', 'like', '%' . $search . '%');
                });
            })
            ->when(is_string($accessory), function ($query) use ($accessory) {
                $query->where('accessory_name', $accessory);
            })
            ->when(is_string($body), function ($query) use ($body) {
                $query->where('body_name', $body);
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
            ->paginate(100);
        
        return view('welcome', ['lilNouns' => $lilNouns]);
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
