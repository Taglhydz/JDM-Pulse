<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotorizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'power' => $this->power,
            'torque' => $this->torque,
            'consumption' => $this->consumption,
            'engine_id' => $this->whenLoaded('engine', function () {
                return [
                    'id' => $this->engine->id,
                    'engine_name' => $this->engine->engine_name,
                    'architecture' => $this->engine->architecture,
                    'volume' => $this->engine->volume,
                    'induction' => $this->engine->induction,
                    'fuel_type' => $this->engine->fuel_type,
                ];
            }),
        ];
    }
}
