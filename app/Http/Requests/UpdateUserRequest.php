<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('usuario');

        return [
            'nombre' => 'sometimes|required|string|max:60',
            'correo' => 'sometimes|required|email|unique:usuarios,correo,' . $userId . ',id_usuario',
            'password' => 'sometimes|min:8|confirmed',
            'activo' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id_rol',
        ];
    }
}
