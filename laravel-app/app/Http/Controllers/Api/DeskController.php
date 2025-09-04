<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desk;

class DeskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Desk::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'max_num' => 'integer|nullable'
        ]);

        $desk = Desk::create([
            'name' => $request->name,
            'max_num' => $request->max_num,
        ]);

        return $desk;
    }

    /**
     * Display the specified resource.
     */
    public function show(Desk $desk)
    {
        return $desk;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Desk $desk)
    {
        $request->validate([
            'name' => 'required|string',
            'max_num' => 'integer|nullable',
        ]);

        $desk->update([
            'name' => $request['name'],
            'max_num' => $request->max_num ?? null,
        ]);

        return $desk;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Desk $desk)
    {
        $desk->delete();
        return response()->noContent();
    }
}
