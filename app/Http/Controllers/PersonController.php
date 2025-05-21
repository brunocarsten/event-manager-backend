<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Http\Resources\PersonResource;
use App\Services\AllocationService;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PersonResource::collection(Person::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AllocationService $allocationService)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $person = Person::create($validated);

        $allocationService->allocatePerson($person);

        return new PersonResource($person->load('allocations.room', 'allocations.coffeeSpace'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        return new PersonResource($person->load('allocations.room', 'allocations.coffeeSpace'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person, AllocationService $service)
    {
        $validated = $request->validate([
            'stage_1_room'           => 'required|string|exists:rooms,name',
            'stage_1_coffee_space'   => 'required|string|exists:coffee_spaces,name',
            'stage_2_room'           => 'required|string|exists:rooms,name',
            'stage_2_coffee_space'   => 'required|string|exists:coffee_spaces,name',
        ]);

        // transforma em estrutura por estágio
        $stagesData = [
            1 => [
                'room'         => $validated['stage_1_room'],
                'coffee_space' => $validated['stage_1_coffee_space'],
            ],
            2 => [
                'room'         => $validated['stage_2_room'],
                'coffee_space' => $validated['stage_2_coffee_space'],
            ],
        ];

        $service->updatePersonAllocations($person, $stagesData);

        return response()->json(['message' => 'Alocações atualizadas com sucesso.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return response()->noContent();
    }
}
