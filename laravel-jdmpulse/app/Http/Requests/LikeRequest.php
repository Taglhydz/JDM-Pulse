<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LikeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
			'car_id' => 'required|integer|exists:cars,id',
        ];
    }

	public function messages(): array
	{
		return [
			'user_id.required' => 'L\'ID de l\'utilisateur est requis.',
			'user_id.integer' => 'L\'ID de l\'utilisateur doit être un entier.',
			'user_id.exists' => 'L\'ID de l\'utilisateur n\'existe pas dans la base de données.',
			'car_id.required' => 'L\'ID de la voiture est requis.',
			'car_id.integer' => 'L\'ID de la voiture doit être un entier.',
			'car_id.exists' => 'L\'ID de la voiture n\'existe pas dans la base de données.',
		];
	}
}
