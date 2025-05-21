<?php

namespace App\Http\Controllers;

use  App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Resources\RoomResource;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RoomResource::collection(Room::all());
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

        return Room::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('allocations.person');
        return new RoomResource($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'capacity' => 'sometimes|integer|min:1',
        ]);

        $room->update($validated);
        return $room;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return response()->noContent();
    }
}
