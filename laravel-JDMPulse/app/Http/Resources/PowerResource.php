<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PowerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'car_id' => $this->car_id,
            'engine_id' => $this->engine_id,
            'car' => $this->whenLoaded('car', function () {
                return [
                    'id' => $this->car->id,
                    'name' => $this->car->name,
                    'brand' => $this->car->brand,
                    'model' => $this->car->model,
                    'edition' => $this->car->edition,
                ];
            }),
            'engine' => $this->whenLoaded('engine', function () {
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
