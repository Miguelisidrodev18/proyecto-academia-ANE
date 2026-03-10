<?php

namespace App\Http\Requests\Matricula;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alumno_id'      => ['required', 'integer', 'exists:alumnos,id'],
            'plan_id'        => ['required', 'integer', 'exists:planes,id'],
            'fecha_inicio'   => ['required', 'date'],
            'tipo_pago'      => ['required', 'in:completo,mensual,cuotas'],
            'dias_cortesia'  => ['nullable', 'integer', 'min:0', 'max:30'],
            'observaciones'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'alumno_id.required' => 'Debes seleccionar un alumno.',
            'alumno_id.exists'   => 'El alumno seleccionado no existe.',
            'plan_id.required'   => 'Debes seleccionar un plan.',
            'plan_id.exists'     => 'El plan seleccionado no existe.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date'     => 'La fecha de inicio no es válida.',
            'dias_cortesia.min'     => 'Los días de cortesía no pueden ser negativos.',
            'dias_cortesia.max'     => 'Los días de cortesía no pueden superar 30.',
        ];
    }
}
