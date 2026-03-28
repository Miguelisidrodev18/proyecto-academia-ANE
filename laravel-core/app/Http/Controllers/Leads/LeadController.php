<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    // ── Público: recibir interesado desde landing ─────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'apellidos'=> ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'nivel'    => ['nullable', 'in:pollito,intermedio,no_sabe'],
            'plan_id'  => ['nullable', 'integer', 'exists:planes,id'],
            'mensaje'  => ['nullable', 'string', 'max:1000'],
        ], [
            'nombre.required'    => 'Tu nombre es obligatorio.',
            'apellidos.required' => 'Tus apellidos son obligatorios.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'Ingresa un correo electrónico válido.',
        ]);

        Lead::create([
            'nombre'    => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'email'     => $data['email'],
            'telefono'  => $data['telefono'] ?? null,
            'nivel'     => $data['nivel'] ?? 'no_sabe',
            'plan_id'   => $data['plan_id'] ?? null,
            'mensaje'   => $data['mensaje'] ?? null,
            'origen'    => 'landing',
        ]);

        return redirect()->route('home', ['#contacto'])
            ->with('lead_success', true);
    }

    // ── Admin: gestión de leads ───────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Lead::with('plan')->latest();

        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel', $request->nivel);
        }

        $leads = $query->paginate(20)->withQueryString();

        $stats = [
            'total'       => Lead::count(),
            'nuevos'      => Lead::where('estado', 'nuevo')->count(),
            'contactados' => Lead::where('estado', 'contactado')->count(),
            'matriculados'=> Lead::where('estado', 'matriculado')->count(),
            'descartados' => Lead::where('estado', 'descartado')->count(),
        ];

        return view('leads.index', compact('leads', 'stats'));
    }

    public function show(Lead $lead): View
    {
        $lead->load('plan');
        $planes = Plan::where('activo', true)->orderBy('precio')->get();

        return view('leads.show', compact('lead', 'planes'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'estado'      => ['required', 'in:nuevo,contactado,matriculado,descartado'],
            'notas_admin' => ['nullable', 'string', 'max:2000'],
            'plan_id'     => ['nullable', 'integer', 'exists:planes,id'],
            'telefono'    => ['nullable', 'string', 'max:20'],
        ]);

        $cambioEstado = $lead->estado !== $data['estado'];

        $lead->update([
            'estado'       => $data['estado'],
            'notas_admin'  => $data['notas_admin'] ?? null,
            'plan_id'      => $data['plan_id'] ?? null,
            'telefono'     => $data['telefono'] ?? $lead->telefono,
            'contactado_en'=> ($cambioEstado && $data['estado'] === 'contactado')
                                ? now()
                                : $lead->contactado_en,
        ]);

        return back()->with('success', 'Lead actualizado correctamente.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $nombre = $lead->nombreCompleto();
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead "' . $nombre . '" eliminado.');
    }
}
