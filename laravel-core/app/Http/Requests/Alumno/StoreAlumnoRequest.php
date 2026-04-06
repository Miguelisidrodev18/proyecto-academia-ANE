<?php

namespace App\Http\Requests\Alumno;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombres'    => ['required', 'string', 'max:100'],
            'apellidos'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'dni'        => ['required', 'digits:8', 'unique:alumnos,dni'],
            'telefono'   => ['nullable', 'string', 'max:20'],
            'whatsapp'   => ['nullable', 'string', 'max:15'],
            'tipo'       => ['required', 'in:pollito,intermedio'],
            'estado'     => ['required', 'in:activo,inactivo'],
            'origen_registro' => ['nullable', 'string', 'max:100'],

            // Representante (opcional)
            'nombre_rep'    => ['nullable', 'string', 'max:100', 'required_with:email_rep'],
            'apellidos_rep' => ['nullable', 'string', 'max:100', 'required_with:email_rep'],
            'email_rep'     => ['nullable', 'email', 'max:255', 'different:email', 'unique:users,email'],
            'telefono_rep'  => ['nullable', 'string', 'max:20'],
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
            'estado.in'              => 'El estado debe ser activo o inactivo.',
            'nombre_rep.required_with'    => 'El nombre del representante es obligatorio si ingresa su correo.',
            'apellidos_rep.required_with' => 'Los apellidos del representante son obligatorios si ingresa su correo.',
            'email_rep.unique'            => 'Este correo de representante ya está registrado.',
            'email_rep.different'         => 'El correo del representante debe ser diferente al del alumno.',
        ];
    }
}
