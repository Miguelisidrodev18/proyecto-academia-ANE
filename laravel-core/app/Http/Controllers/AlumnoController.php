<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    // ── LISTADO ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Alumno::query();

        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('tipo') && in_array($request->tipo, ['vip', 'premium'])) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado') && in_array($request->estado, ['0', '1'])) {
            $query->where('estado', (bool) $request->estado);
        }

        $alumnos = $query->latest()->paginate(15)->withQueryString();
        $total   = Alumno::count();
        $premium = Alumno::where('tipo', 'premium')->count();

        return view('alumnos.index', compact('alumnos', 'total', 'premium'));
    }

    // ── CREAR ─────────────────────────────────────────────────────────────────

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni'              => ['required', 'digits:8', 'unique:alumnos,dni'],
            'nombres'          => ['required', 'string', 'max:100'],
            'apellidos'        => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:180', 'unique:alumnos,email'],
            'telefono'         => ['nullable', 'digits:9'],
            'tipo'             => ['required', 'in:vip,premium'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'estado'           => ['nullable', 'boolean'],
        ]);

        $validated['estado'] = $request->boolean('estado', true);

        Alumno::create($validated);

        return redirect()->route('alumnos.index')
            ->with('success', '¡Alumno registrado correctamente!');
    }

    // ── VER ───────────────────────────────────────────────────────────────────

    public function show(Alumno $alumno)
    {
        return view('alumnos.show', compact('alumno'));
    }

    // ── EDITAR ────────────────────────────────────────────────────────────────

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $validated = $request->validate([
            'dni'              => ['required', 'digits:8', Rule::unique('alumnos', 'dni')->ignore($alumno->id)],
            'nombres'          => ['required', 'string', 'max:100'],
            'apellidos'        => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:180', Rule::unique('alumnos', 'email')->ignore($alumno->id)],
            'telefono'         => ['nullable', 'digits:9'],
            'tipo'             => ['required', 'in:vip,premium'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'estado'           => ['nullable', 'boolean'],
        ]);

        $validated['estado'] = $request->boolean('estado', true);

        $alumno->update($validated);

        return redirect()->route('alumnos.show', $alumno)
            ->with('success', '¡Alumno actualizado correctamente!');
    }

    // ── ELIMINAR ──────────────────────────────────────────────────────────────

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno eliminado correctamente.');
    }

    // ── RENIEC ────────────────────────────────────────────────────────────────

    public function buscarDni(string $numero)
    {
        if (!preg_match('/^\d{8}$/', $numero)) {
            return response()->json(['error' => 'DNI inválido'], 422);
        }

        $response = Http::timeout(5)->get('https://api.apis.net.pe/v1/dni', [
            'numero' => $numero,
        ]);

        if ($response->failed() || !$response->json('nombres')) {
            return response()->json(['error' => 'No se encontró el DNI en RENIEC'], 404);
        }

        return response()->json([
            'nombres'          => $response->json('nombres'),
            'apellidoPaterno'  => $response->json('apellidoPaterno'),
            'apellidoMaterno'  => $response->json('apellidoMaterno'),
        ]);
    }

    // ── DOCUMENTOS ────────────────────────────────────────────────────────────

    public function documentos(Alumno $alumno)
    {
        return view('alumnos.documentos', compact('alumno'));
    }

    public function subirDocumento(Request $request, Alumno $alumno)
    {
        $request->validate([
            'nombre'    => ['required', 'string', 'max:100'],
            'documento' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $extension = $request->file('documento')->getClientOriginalExtension();
        $ruta      = $request->file('documento')->store("alumnos/{$alumno->id}/documentos", 'public');

        $documentos   = $alumno->documentos ?? [];
        $documentos[] = [
            'nombre'    => $request->nombre,
            'ruta'      => $ruta,
            'extension' => strtolower($extension),
            'fecha'     => now()->toDateString(),
        ];

        $alumno->update(['documentos' => $documentos]);

        return back()->with('success', 'Documento subido correctamente.');
    }

    public function eliminarDocumento(Request $request, Alumno $alumno, int $indice)
    {
        $documentos = $alumno->documentos ?? [];

        if (isset($documentos[$indice])) {
            Storage::disk('public')->delete($documentos[$indice]['ruta']);
            array_splice($documentos, $indice, 1);
            $alumno->update(['documentos' => array_values($documentos)]);
        }

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}
