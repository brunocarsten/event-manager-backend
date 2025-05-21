<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    // public static $wrap = 'person';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allocations = $this->allocations->groupBy('stage');

        return [
            'id' => $this->id,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'stage_1' => [
                'room' => optional($allocations[1][0]->room ?? null)?->name,
                'coffee_space' => optional($allocations[1][0]->coffeeSpace ?? null)?->name,
            ],
            'stage_2' => [
                'room' => optional($allocations[2][0]->room ?? null)?->name,
                'coffee_space' => optional($allocations[2][0]->coffeeSpace ?? null)?->name,
            ],
        ];
    }
}
