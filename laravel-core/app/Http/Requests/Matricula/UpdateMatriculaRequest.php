<?php

namespace App\Http\Requests\Matricula;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id'       => ['required', 'exists:planes,id'],
            'fecha_inicio'  => ['required', 'date'],
            'tipo_pago'     => ['required', 'in:completo,mensual,cuotas'],
            'dias_cortesia' => ['nullable', 'integer', 'min:0', 'max:30'],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'estado'        => ['required', 'in:activa,vencida,suspendida,pendiente'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date'     => 'La fecha de inicio no es válida.',
            'estado.in'             => 'El estado no es válido.',
        ];
    }
}
