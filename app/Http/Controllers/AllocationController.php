<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Person;
use App\Models\Room;
use App\Models\CoffeeSpace;

use App\Http\Resources\AllocationResource;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new AllocationResource(Allocation::with(['person', 'room', 'coffeeSpace'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function assignAutomatically()
    {
        // Zera as alocações anteriores
        Allocation::truncate();

        $people = Person::all();
        $rooms = Room::all();
        $coffeeSpaces = CoffeeSpace::all();

        foreach ([1, 2] as $stage) {
            $shuffledPeople = $people->shuffle();

            // Alocar salas
            $roomIndex = 0;
            $roomSlots = [];

            foreach ($rooms as $room) {
                $roomSlots[$room->id] = $room->capacity;
            }

            foreach ($shuffledPeople as $person) {
                while ($roomIndex < count($rooms)) {
                    $room = $rooms[$roomIndex];
                    if ($roomSlots[$room->id] > 0) {
                        Allocation::create([
                            'person_id' => $person->id,
                            'room_id' => $room->id,
                            'coffee_space_id' => null,
                            'stage' => $stage
                        ]);
                        $roomSlots[$room->id]--;
                        break;
                    } else {
                        $roomIndex++;
                    }
                }
            }

            // Alocar cafés
            $shuffledPeople = $people->shuffle();
            $coffeeSlots = [];

            foreach ($coffeeSpaces as $coffee) {
                $coffeeSlots[$coffee->id] = $coffee->capacity;
            }

            $coffeeIndex = 0;
            foreach ($shuffledPeople as $person) {
                while ($coffeeIndex < count($coffeeSpaces)) {
                    $coffee = $coffeeSpaces[$coffeeIndex];
                    if ($coffeeSlots[$coffee->id] > 0) {
                        // Atualiza a alocação existente da etapa com coffee_space
                        $allocation = Allocation::where('person_id', $person->id)
                            ->where('stage', $stage)
                            ->first();

                        if ($allocation) {
                            $allocation->coffee_space_id = $coffee->id;
                            $allocation->save();
                        }

                        $coffeeSlots[$coffee->id]--;
                        break;
                    } else {
                        $coffeeIndex++;
                    }
                }
            }
        }

        return response()->json(['message' => 'Alocações geradas com sucesso']);
    }
}
