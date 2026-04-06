<?php

namespace App\Http\Requests\Alumno;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $alumno = $this->route('alumno');
        $userId = $alumno?->user_id;

        return [
            'nombres'    => ['required', 'string', 'max:100'],
            'apellidos'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255', "unique:users,email,{$userId}"],
            'dni'        => ['required', 'digits:8', "unique:alumnos,dni,{$alumno?->id}"],
            'telefono'   => ['nullable', 'string', 'max:20'],
            'whatsapp'   => ['nullable', 'string', 'max:15'],
            'tipo'       => ['required', 'in:pollito,intermedio'],
            'estado'     => ['required', 'in:activo,inactivo'],
            'origen_registro' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required'   => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.unique'       => 'Este correo ya está registrado.',
            'dni.required'       => 'El DNI es obligatorio.',
            'dni.digits'         => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'         => 'Este DNI ya está registrado.',
            'tipo.in'            => 'El tipo debe ser Pollito o Intermedio.',
            'estado.in'          => 'El estado debe ser activo o inactivo.',
        ];
    }
}
