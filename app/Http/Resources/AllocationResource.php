<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = $this->resource;
        $grouped = $items->groupBy(fn($alloc) => $alloc->person->id);

        return $grouped->map(fn($group) => [
            'person' => [
                'id'   => $group->first()->person->id,
                'name' => $group->first()->person->first_name . ' ' . $group->first()->person->last_name,
            ],
            'stages' => $group->map(fn($alloc) => [
                'stage'        => $alloc->stage,
                'room'         => $alloc->room?->name,
                'coffee_space' => $alloc->coffeeSpace?->name,
            ])->values(),
            'updated_at' => $group->max('updated_at')->toIso8601String(),
        ])->values()->all();
    }
}
