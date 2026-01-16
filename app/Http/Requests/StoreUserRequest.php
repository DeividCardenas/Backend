<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'nombre' => 'required|string|max:60',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:8|confirmed',
            'activo' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id_rol',
        ];
    }
}
