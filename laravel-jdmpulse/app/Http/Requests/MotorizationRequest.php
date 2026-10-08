<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MotorizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'power' => 'required|numeric|min:0',
			'torque' => 'required|numeric|min:0',
			'consumption' => 'required|numeric|min:0',
			'engine_id' => 'required|integer|exists:engines,id',
        ];
    }

	public function messages(): array
	{
		return [
			'power.required' => 'La puissance est requise.',
			'power.numeric' => 'La puissance doit être un nombre.',
			'power.min' => 'La puissance doit être supérieure ou égale à 0.',
			'torque.required' => 'Le couple est requis.',
			'torque.numeric' => 'Le couple doit être un nombre.',
			'torque.min' => 'Le couple doit être supérieur ou égal à 0.',
			'consumption.required' => 'La consommation est requise.',
			'consumption.numeric' => 'La consommation doit être un nombre.',
			'consumption.min' => 'La consommation doit être supérieure ou égale à 0.',
			'engine_id.required' => 'Le champ moteur est requis.',
			'engine_id.integer' => 'Le champ moteur doit être un entier.',
			'engine_id.exists' => 'Le moteur sélectionné n\'existe pas.',
		];
	}
}
