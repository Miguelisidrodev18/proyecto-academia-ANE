<?php

namespace App\Http\Controllers\Alumnos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alumno\StoreAlumnoRequest;
use App\Http\Requests\Alumno\UpdateAlumnoRequest;
use App\Models\Alumno;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AlumnoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Alumno::with('user')->latest();

        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('tipo') && in_array($request->tipo, ['pollito', 'intermedio'])) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado === 'activo');
        }

        $alumnos = $query->paginate(15)->withQueryString();

        $stats = Alumno::selectRaw('
            COUNT(*) as total,
            SUM(estado = 1) as activos,
            SUM(estado = 0) as inactivos,
            SUM(tipo = "pollito") as pollitos,
            SUM(tipo = "intermedio") as intermedios
        ')->first();

        return view('alumnos.index', compact('alumnos', 'stats'));
    }

    public function create(): View
    {
        return view('alumnos.create');
    }

    public function store(StoreAlumnoRequest $request): RedirectResponse
    {
        $data            = $request->validated();
        $alumnoPassword  = $data['dni'];
        $repPassword     = null;
        $alumno          = null;

        DB::transaction(function () use ($data, $alumnoPassword, &$repPassword, &$alumno) {
            $userAlumno = User::create([
                'name'     => trim($data['nombres'] . ' ' . $data['apellidos']),
                'email'    => $data['email'],
                'password' => Hash::make($alumnoPassword),
                'role'     => 'alumno',
            ]);

            $representanteId = null;
            if (!empty($data['email_rep'])) {
                $repPassword = 'Acad' . rand(1000, 9999);
                $userRep = User::create([
                    'name'     => trim(($data['nombre_rep'] ?? '') . ' ' . ($data['apellidos_rep'] ?? '')),
                    'email'    => $data['email_rep'],
                    'password' => Hash::make($repPassword),
                    'role'     => 'representante',
                ]);
                $representanteId = $userRep->id;
            }

            $alumno = Alumno::create([
                'user_id'          => $userAlumno->id,
                'representante_id' => $representanteId,
                'dni'              => $data['dni'],
                'telefono'         => $data['telefono'] ?? null,
                'tipo'             => $data['tipo'],
                'estado'           => $data['estado'] === 'activo',
                'origen_registro'  => $data['origen_registro'] ?? 'manual',
                'acceso_activo'    => true,
                'racha_actual'     => 0,
            ]);
        });

        session()->flash('credenciales', [
            'alumno' => [
                'nombre'   => trim($data['nombres'] . ' ' . $data['apellidos']),
                'email'    => $data['email'],
                'password' => $alumnoPassword,
            ],
            'representante' => !empty($data['email_rep']) ? [
                'nombre'   => trim(($data['nombre_rep'] ?? '') . ' ' . ($data['apellidos_rep'] ?? '')),
                'email'    => $data['email_rep'],
                'password' => $repPassword,
            ] : null,
        ]);

        return redirect()->route('alumnos.credenciales', $alumno);
    }

    public function credenciales(Alumno $alumno): View
    {
        $alumno->load(['user', 'representante']);
        $credenciales = session('credenciales');

        if (!$credenciales) {
            return redirect()->route('alumnos.show', $alumno);
        }

        return view('alumnos.credenciales', compact('alumno', 'credenciales'));
    }

    public function show(Alumno $alumno): View
    {
        $alumno->load(['user', 'matriculas.plan', 'asistencias', 'cursos']);

        return view('alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno): View
    {
        $alumno->load('user');

        return view('alumnos.edit', compact('alumno'));
    }

    public function update(UpdateAlumnoRequest $request, Alumno $alumno): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $alumno) {
            $alumno->user->update([
                'name'  => trim($data['nombres'] . ' ' . $data['apellidos']),
                'email' => $data['email'],
            ]);

            $alumno->update([
                'dni'             => $data['dni'],
                'telefono'        => $data['telefono'] ?? null,
                'tipo'            => $data['tipo'],
                'estado'          => $data['estado'] === 'activo',
                'origen_registro' => $data['origen_registro'] ?? $alumno->origen_registro,
            ]);
        });

        return redirect()->route('alumnos.show', $alumno)
            ->with('success', 'Datos del alumno actualizados.');
    }

    public function destroy(Alumno $alumno): RedirectResponse
    {
        if ($alumno->matriculas()->exists()) {
            return redirect()->route('alumnos.show', $alumno)
                ->with('error', 'No se puede eliminar a ' . $alumno->nombreCompleto() . ' porque tiene matrículas registradas. Para desactivarlo, usa el botón "Desactivar".');
        }

        $alumno->delete();

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno eliminado correctamente.');
    }

    public function cambiarEstado(Alumno $alumno): RedirectResponse
    {
        $alumno->update(['estado' => !$alumno->estado]);

        $mensaje = $alumno->estado ? 'Alumno activado.' : 'Alumno desactivado.';

        return back()->with('success', $mensaje);
    }

    public function matriculasActivas(Alumno $alumno)
    {
        $matricula = $alumno->matriculas()
            ->with('plan')
            ->where('estado', 'activa')
            ->where('fecha_fin', '>=', now())
            ->latest()
            ->first();

        if ($matricula) {
            return response()->json([
                'tieneActiva' => true,
                'matricula'   => [
                    'plan_nombre' => $matricula->plan->nombre,
                    'fecha_inicio' => $matricula->fecha_inicio->format('d/m/Y'),
                    'fecha_fin'    => $matricula->fecha_fin->format('d/m/Y'),
                ],
            ]);
        }

        return response()->json(['tieneActiva' => false]);
    }

    public function buscar(Request $request)
    {
        $q = trim($request->get('q', ''));

        $alumnos = Alumno::with('user')
            ->when($q, fn ($query) => $query->buscar($q))
            ->orderBy('id')
            ->limit(30)
            ->get()
            ->map(fn ($a) => [
                'id'      => $a->id,
                'text'    => $a->nombreCompleto() . ' - ' . $a->dni,
                'nombre'  => $a->nombreCompleto(),
                'dni'     => $a->dni,
                'tipo'    => $a->tipo,
                'inicial' => $a->inicial(),
            ]);

        return response()->json($alumnos);
    }

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
            'nombres'         => $response->json('nombres'),
            'apellidoPaterno' => $response->json('apellidoPaterno'),
            'apellidoMaterno' => $response->json('apellidoMaterno'),
        ]);
    }

    private function actualizarRacha(Alumno $alumno): void
    {
        $hoy  = Carbon::today();
        $ayer = Carbon::yesterday();

        if ($alumno->ultimo_acceso === null) {
            $alumno->racha_actual = 1;
        } elseif ($alumno->ultimo_acceso->equalTo($ayer)) {
            $alumno->racha_actual += 1;
        } elseif ($alumno->ultimo_acceso->equalTo($hoy)) {
            return;
        } else {
            $alumno->racha_actual = 1;
        }

        $alumno->ultimo_acceso = $hoy;
        $alumno->save();
    }
}
