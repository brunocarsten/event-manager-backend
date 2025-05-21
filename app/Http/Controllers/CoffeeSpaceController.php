<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoffeeSpace;
use App\Http\Resources\CoffeeSpaceResource;

class CoffeeSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CoffeeSpaceResource::collection(CoffeeSpace::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        return CoffeeSpace::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(CoffeeSpace $coffeeSpace)
    {
        return new CoffeeSpaceResource($coffeeSpace->load('allocations.person'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoffeeSpace $coffeeSpace)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'capacity' => 'sometimes|integer|min:1',
        ]);

        $coffeeSpace->update($validated);
        return $coffeeSpace;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoffeeSpace $coffeeSpace)
    {
        $coffeeSpace->delete();
        return response()->noContent();
    }
}
