<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand' => 'required|string|max:255',
			'model' => 'required|string|max:255',
            'year' => 'required|string|min:4|max:9',
			'color' => 'required|string|max:50',
			'generation' => 'nullable|string|max:255',
			'image_url' => 'nullable|string|min:1',
            'edition_id' => 'nullable|integer|exists:editions,id',
        ];
    }

	public function messages(): array
	{
		return [
			'brand.required' => 'Le champ marque est requis.',
			'brand.string' => 'Le champ marque doit être une chaîne de caractères.',
			'brand.max' => 'Le champ marque ne peut pas dépasser 255 caractères.',
			'model.required' => 'Le champ modèle est requis.',
			'model.string' => 'Le champ modèle doit être une chaîne de caractères.',
			'model.max' => 'Le champ modèle ne peut pas dépasser 255 caractères.',
			'year.required' => 'Le champ année est requis.',
			'color.required' => 'Le champ couleur est requis.',
			'color.string' => 'Le champ couleur doit être une chaîne de caractères.',
			'color.max' => 'Le champ couleur ne peut pas dépasser 50 caractères.',
			'generation.string' => 'Le champ génération doit être une chaîne de caractères.',
			'generation.max' => 'Le champ génération ne peut pas dépasser 255 caractères.',
			'image_url.string' => 'Le champ URL de l\'image doit être une chaîne de caractères.',
			'image_url.min' => 'Le champ URL de l\'image doit contenir au moins 1 caractère.',
		];
	}
}
