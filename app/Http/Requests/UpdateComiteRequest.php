<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|required|string|max:255',
            'objetivo' => 'sometimes|required|string',
            'responsable_id' => 'nullable|exists:usuarios,id_usuario',
            'miembros' => 'array',
            'miembros.*' => 'exists:usuarios,id_usuario',
        ];
    }
}
