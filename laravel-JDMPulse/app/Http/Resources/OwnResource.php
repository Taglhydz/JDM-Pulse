<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnResource extends JsonResource
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
            'user_id' => $this->user_id,
            'car' => $this->whenLoaded('car', function () {
                return [
                    'id' => $this->car->id,
                    'name' => $this->car->name,
                    'brand' => $this->car->brand,
                    'model' => $this->car->model,
                    'year' => $this->car->year,
                    'edition' => $this->car->edition,
                ];
            }),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'first_name' => $this->user->first_name,
                    'last_name' => $this->user->last_name,
                    'email' => $this->user->email,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
