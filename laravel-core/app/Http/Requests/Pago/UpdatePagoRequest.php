<?php

namespace App\Http\Requests\Pago;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePagoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'monto'        => ['required', 'numeric', 'min:1'],
            'metodo_pago'  => ['required', 'in:efectivo,transferencia,yape,plin,tarjeta,mixto'],
            'estado'       => ['required', 'in:confirmado,pendiente,anulado'],
            'fecha_pago'   => ['required', 'date'],
            'referencia'   => ['nullable', 'string', 'max:100'],
            'notas'        => ['nullable', 'string'],
            'comprobante'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'monto.required'       => 'El monto es obligatorio.',
            'monto.numeric'        => 'El monto debe ser un número.',
            'monto.min'            => 'El monto debe ser al menos S/. 1.',
            'metodo_pago.required' => 'Debes seleccionar un método de pago.',
            'metodo_pago.in'       => 'El método de pago no es válido.',
            'estado.required'      => 'El estado es obligatorio.',
            'estado.in'            => 'El estado no es válido.',
            'fecha_pago.required'  => 'La fecha de pago es obligatoria.',
            'fecha_pago.date'      => 'La fecha de pago no es válida.',
            'comprobante.mimes'    => 'El comprobante debe ser JPG, PNG o PDF.',
            'comprobante.max'      => 'El comprobante no puede superar 2 MB.',
        ];
    }
}
