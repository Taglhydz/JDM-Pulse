<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|integer|exists:cars,id',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

	public function messages(): array
	{
		return [
			'car_id.required' => 'Le champ voiture est requis.',
			'car_id.integer' => 'Le champ voiture doit être un entier.',
			'car_id.exists' => 'La voiture sélectionnée n\'existe pas.',
			'user_id.required' => 'Le champ utilisateur est requis.',
			'user_id.integer' => 'Le champ utilisateur doit être un entier.',
			'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
		];
	}
}
