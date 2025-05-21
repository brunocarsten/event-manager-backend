<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allocations = $this->allocations()
            ->with('person')
            ->get()
            ->groupBy('stage');

        // Garantir coleções vazias para estágios ausentes
        $stage1 = $allocations->get(1, collect());
        $stage2 = $allocations->get(2, collect());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'stage_1_people' => $stage1
                ->pluck('person')
                ->map(fn($p) => "{$p->first_name} {$p->last_name}")
                ->values(),
            'stage_2_people' => $stage2
                ->pluck('person')
                ->map(fn($p) => "{$p->first_name} {$p->last_name}")
                ->values(),
        ];
    }
}
