<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReunionRequest extends FormRequest
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
            'id_comite' => 'required|exists:comites,id_comite',
            'fecha' => 'required|date',
            'tema' => 'required|string',
            'acuerdos' => 'nullable|string',
            'archivo_acta' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }
}
