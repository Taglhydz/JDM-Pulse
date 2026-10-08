<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EngineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'engine_name' => 'required|string|max:255',
            'architecture' => 'required|string|max:100',
			'volume' => 'required|string|max:100',
			'induction' => 'required|string|max:100',
			'fuel_type' => 'required|string|max:100',
        ];
    }

	public function messages(): array
	{
		return [
			'engine_name.required' => 'Le nom du moteur est requis.',
			'engine_name.string' => 'Le nom du moteur doit être une chaîne de caractères.',
			'engine_name.max' => 'Le nom du moteur ne peut pas dépasser 255 caractères.',
			'architecture.required' => 'L\'architecture est requise.',
			'architecture.string' => 'L\'architecture doit être une chaîne de caractères.',
			'architecture.max' => 'L\'architecture ne peut pas dépasser 100 caractères.',
			'volume.required' => 'Le volume est requis.',
			'volume.numeric' => 'Le volume doit être un nombre.',
			'volume.min' => 'Le volume doit être supérieur ou égal à 0.',
			'volume.max' => 'Le volume ne peut pas dépasser 20000.',
			'induction.required' => 'L\'induction est requise.',
			'induction.string' => 'L\'induction doit être une chaîne de caractères.',
			'induction.max' => 'L\'induction ne peut pas dépasser 100 caractères.',
			'fuel_type.required' => 'Le type de carburant est requis.',
			'fuel_type.string' => 'Le type de carburant doit être une chaîne de caractères.',
			'fuel_type.max' => 'Le type de carburant ne peut pas dépasser 100 caractères.',
		];
	}
}
