<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'car' => [
                'id' => $this->id,
                'brand' => $this->brand,
                'model' => $this->model,
                'year' => $this->year,
                'color' => $this->color,
                'generation' => $this->generation,
            ],
            'edition' => $this->edition ? [
                'id' => $this->edition->id,
                'edition_name' => $this->edition->edition_name ?? null,
            ] : null,
            'engines' => $this->powers->map(function($power) {
                $engine = $power->engine;
                return $engine ? [
                    'id' => $engine->id,
                    'engine_name' => $engine->engine_name,
                    'architecture' => $engine->architecture,
                    'volume' => $engine->volume,
                    'induction' => $engine->induction,
                    'fuel_type' => $engine->fuel_type,
                    'motorizations' => $engine->motorizations->map(function($moto) {
                        return [
                            'id' => $moto->id,
                            'power' => $moto->power,
                            'torque' => $moto->torque,
                            'consumption' => $moto->consumption,
                            'engine_id' => $moto->engine_id,
                        ];
                    }),
                ] : null;
            })->filter()->values(),
        ];
    }
}
