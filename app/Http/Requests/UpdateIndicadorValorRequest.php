<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIndicadorValorRequest extends FormRequest
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
            'id_indicador' => 'sometimes|required|exists:indicadores,id_indicador',
            'valor' => 'sometimes|required|numeric',
            'fecha' => 'sometimes|required|date',
            'observaciones' => 'nullable|string',
        ];
    }
}
