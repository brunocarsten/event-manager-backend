<?php

namespace App\Services;

use App\Models\Allocation;
use App\Models\Room;
use App\Models\CoffeeSpace;
use App\Models\Person;
use Illuminate\Support\Arr;

class AllocationService
{
    /**
     * Realiza a alocação automática de recursos ou entidades conforme a lógica já existente no sistema.
     *
     * Este método é responsável por distribuir automaticamente os itens necessários,
     * seguindo as regras e critérios previamente definidos na aplicação.
     *
     * @return void
     */
    public function allocatePerson(Person $person): void
    {
        foreach ([1, 2] as $stage) {
            $room = Room::withCount(['allocations' => function ($q) use ($stage) {
                $q->where('stage', $stage);
            }])
                ->get()
                ->filter(fn($room) => $room->allocations_count < $room->capacity)
                ->shuffle()
                ->first();

            $coffee = CoffeeSpace::withCount(['allocations' => function ($q) use ($stage) {
                $q->where('stage', $stage);
            }])
                ->get()
                ->filter(fn($c) => $c->allocations_count < $c->capacity)
                ->shuffle()
                ->first();

            if (!$room || !$coffee || !is_array([$room, $coffee]) || count([$room, $coffee]) === 0) {
                return;
            }

            Allocation::create([
                'person_id' => $person->id,
                'room_id' => $room?->id,
                'coffee_space_id' => $coffee?->id,
                'stage' => $stage,
            ]);
        }
    }

    /**
     * Atualiza as alocações de uma pessoa de acordo com os dados fornecidos.
     *
     * @param  Person  $person
     * @param  array   $data  Array no formato:
     *   [
     *     1 => ['room' => 'Sala A', 'coffee_space' => 'Café 1'],
     *     2 => ['room' => 'Sala B', 'coffee_space' => 'Café 2'],
     *   ]
     */
    public function updatePersonAllocations(Person $person, array $data): void
    {
        foreach ([1, 2] as $stage) {
            $stageData = Arr::get($data, $stage, []);

            // busca a sala e o coffee_space pelo nome
            $room = Room::where('name', Arr::get($stageData, 'room'))->first();
            $coffee = CoffeeSpace::where('name', Arr::get($stageData, 'coffee_space'))->first();

            // só prossegue se encontrou ambos
            if ($room && $coffee) {
                Allocation::updateOrCreate(
                    [
                        'person_id' => $person->id,
                        'stage'     => $stage,
                    ],
                    [
                        'room_id'         => $room->id,
                        'coffee_space_id' => $coffee->id,
                    ]
                );
            }
        }
    }
}
