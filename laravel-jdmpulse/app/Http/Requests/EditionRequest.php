<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

	public function rules(): array
    {
        return [
            'edition_name' => 'required|string|max:255|unique:editions,edition_name' . ($this->isMethod('post') ? '' : ',' . $this->route('edition')->id),
        ];
    }

	public function messages(): array
	{
		return [
			'edition_name.required' => 'Le nom de l\'édition est requis.',
			'edition_name.string' => 'Le nom de l\'édition doit être une chaîne de caractères.',
			'edition_name.max' => 'Le nom de l\'édition ne peut pas dépasser 255 caractères.',
		];
	}
}
