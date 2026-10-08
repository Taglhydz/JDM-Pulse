<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PowerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|integer|exists:cars,id',
			'engine_id' => 'required|integer|exists:engines,id',
        ];
    }

	public function messages(): array
	{
		return [
			'car_id.required' => 'Le champ voiture est requis.',
			'car_id.integer' => 'Le champ voiture doit être un entier.',
			'car_id.exists' => 'La voiture sélectionnée n\'existe pas.',
			'engine_id.required' => 'Le champ moteur est requis.',
			'engine_id.integer' => 'Le champ moteur doit être un entier.',
			'engine_id.exists' => 'Le moteur sélectionné n\'existe pas.',
		];
	}
}
