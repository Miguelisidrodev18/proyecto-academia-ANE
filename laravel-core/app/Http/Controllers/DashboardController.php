<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\Pago;
use App\Services\AccesoService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct(private AccesoService $accesoService) {}

    public function index()
    {
        $user = auth()->user();

        if ($user->isAlumno()) {
            return $this->alumnoIndex($user);
        }

        if ($user->isDocente()) {
            return $this->docenteIndex($user);
        }

        if ($user->isRepresentante()) {
            return view('dashboard.representante');
        }

        // Admin
        $totalAlumnos      = Alumno::count();
        $alumnosActivos    = Alumno::where('estado', 1)->count();
        $matriculasActivas = Matricula::where('estado', 'activa')->count();
        $ingresosMes       = Pago::where('estado', 'confirmado')
                                ->whereMonth('fecha_pago', Carbon::now()->month)
                                ->whereYear('fecha_pago', Carbon::now()->year)
                                ->sum('monto');
        $pagosPendientes   = Pago::where('estado', 'pendiente')->count();

        return view('dashboard.index', compact(
            'totalAlumnos', 'alumnosActivos',
            'matriculasActivas', 'ingresosMes', 'pagosPendientes'
        ));
    }

    private function alumnoIndex($user)
    {
        $alumno         = $user->alumno;
        $matricula      = null;
        $cursos         = collect();
        $rachaInfo      = null;
        $mostrarOverlay = false;

        if ($alumno) {
            $rachaInfo = $this->accesoService->registrarAcceso($alumno->id);

            if ($rachaInfo['si_subio_racha'] || $rachaInfo['si_perdio_racha']) {
                $sessionKey     = 'racha_overlay_' . $alumno->id;
                $ultimaMostrada = session($sessionKey);
                if ($ultimaMostrada !== Carbon::today()->toDateString()) {
                    $mostrarOverlay = true;
                    session([$sessionKey => Carbon::today()->toDateString()]);
                }
            }

            $matricula = $alumno->matriculaActiva();
            if ($matricula && $matricula->plan) {
                $cursos = $matricula->plan
                    ->cursos()
                    ->where('activo', true)
                    ->orderBy('nivel')
                    ->orderBy('grado')
                    ->orderBy('nombre')
                    ->get();
            }
        }

        return view('dashboard.alumno', compact(
            'alumno', 'matricula', 'cursos', 'rachaInfo', 'mostrarOverlay'
        ));
    }

    private function docenteIndex($user)
    {
        return view('dashboard.docente', compact('user'));
    }

    public function alumnos()
    {
        return view('dashboard.alumnos', [
            'modulo'      => 'Alumnos',
            'descripcion' => 'Gestiona el padrón de alumnos, historial académico y datos personales de cada estudiante.',
            'color'       => 'blue',
        ]);
    }

    public function matriculas()
    {
        return view('dashboard.matriculas', [
            'modulo'      => 'Matrículas',
            'descripcion' => 'Administra las inscripciones, procesos de matrícula y periodos académicos.',
            'color'       => 'indigo',
        ]);
    }

    public function pagos()
    {
        return view('dashboard.pagos', [
            'modulo'      => 'Pagos',
            'descripcion' => 'Controla los pagos de mensualidades, estados de cuenta y comprobantes.',
            'color'       => 'green',
        ]);
    }

    public function asistencia()
    {
        return view('dashboard.asistencia', [
            'modulo'      => 'Asistencia',
            'descripcion' => 'Registra y visualiza la asistencia diaria de alumnos y docentes.',
            'color'       => 'yellow',
        ]);
    }

    public function aulaVirtual()
    {
        return view('dashboard.aula-virtual', [
            'modulo'      => 'Aula Virtual',
            'descripcion' => 'Accede a clases grabadas, materiales de estudio y recursos académicos en línea.',
            'color'       => 'purple',
        ]);
    }

    public function bazar()
    {
        return view('dashboard.bazar', [
            'modulo'      => 'Bazar',
            'descripcion' => 'Gestiona la venta de materiales, libros y útiles escolares de la academia.',
            'color'       => 'orange',
        ]);
    }

    public function reconocimientos()
    {
        return view('dashboard.reconocimientos', [
            'modulo'      => 'Reconocimientos',
            'descripcion' => 'Premia y destaca a los alumnos con mejor desempeño académico y comportamiento.',
            'color'       => 'amber',
        ]);
    }

    public function reportes()
    {
        return view('dashboard.reportes', [
            'modulo'      => 'Reportes',
            'descripcion' => 'Genera estadísticas, informes académicos y reportes de rendimiento.',
            'color'       => 'teal',
        ]);
    }

    public function configuracion()
    {
        return view('dashboard.configuracion', [
            'modulo'      => 'Configuración',
            'descripcion' => 'Ajusta los parámetros del sistema, roles, permisos y preferencias generales.',
            'color'       => 'slate',
        ]);
    }
}
