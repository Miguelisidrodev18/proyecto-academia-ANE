<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Configuracion;
use App\Models\Curso;
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
            return $this->representanteIndex($user);
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
                    ->with([
                        'clases' => fn ($q) => $q->where('fecha', '>', now())->orderBy('fecha'),
                    ])
                    ->orderBy('nivel')
                    ->orderBy('grado')
                    ->orderBy('nombre')
                    ->get();
            }
        }

        // Próximas clases de todos los cursos del plan (próximas 5)
        $proximasClases = collect();
        if ($matricula && $cursos->isNotEmpty()) {
            $cursoIds = $cursos->pluck('id');
            $proximasClases = \App\Models\Clase::whereIn('curso_id', $cursoIds)
                ->where('fecha', '>', now())
                ->orderBy('fecha')
                ->limit(5)
                ->with('curso')
                ->get();
        }

        return view('dashboard.alumno', compact(
            'alumno', 'matricula', 'cursos', 'rachaInfo', 'mostrarOverlay', 'proximasClases'
        ));
    }

    private function representanteIndex($user)
    {
        $alumno    = \App\Models\Alumno::with(['user', 'matriculas.plan', 'matriculas.pagos', 'matriculas.cuotas'])
                        ->where('representante_id', $user->id)
                        ->first();

        $matricula      = null;
        $pagos          = collect();
        $cuotas         = collect();
        $proximasClases = collect();

        if ($alumno) {
            $matricula = $alumno->matriculaActiva();

            if ($matricula) {
                $pagos  = $matricula->pagos()->orderByDesc('fecha_pago')->get();
                $cuotas = $matricula->cuotas()->orderBy('numero')->get();

                $cursoIds = $matricula->plan->cursos()->pluck('cursos.id');
                $proximasClases = \App\Models\Clase::whereIn('curso_id', $cursoIds)
                    ->where('fecha', '>', now())
                    ->orderBy('fecha')
                    ->limit(4)
                    ->with('curso')
                    ->get();
            }
        }

        return view('dashboard.representante', compact(
            'alumno', 'matricula', 'pagos', 'cuotas', 'proximasClases'
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
        $cursos = Curso::withCount('clases')
            ->with(['clases' => fn ($q) => $q->withCount('asistencias')->orderBy('fecha')])
            ->orderBy('nombre')
            ->get();

        $alumnos = Alumno::with('user')->activos()->orderBy('id')->get();

        return view('dashboard.reportes', compact('cursos', 'alumnos'));
    }

    public function configuracion()
    {
        $defaults = [
            'whatsapp_number'    => config('app.whatsapp_number', ''),
            'wa_msg_recordatorio'=> 'Hola {nombre}, te recordamos que tienes un pago pendiente en Academia Nueva Era por S/. {saldo}. Por favor regulariza tu pago para no perder el acceso a tus clases.',
            'wa_msg_renovacion'  => 'Hola {nombre}, tu membresía en Academia Nueva Era ha vencido. ¡Renueva tu plan y sigue disfrutando de todas tus clases! Contáctanos para coordinar.',
            'wa_msg_vencimiento' => 'Hola {nombre}, te informamos que solo te quedan {dias} días de acceso en Academia Nueva Era. ¡Renueva a tiempo para no perder el acceso!',
            'wa_alumno_caducada' => 'Hola, mi membresía de Academia Nueva Era ha caducado. Quisiera renovar mi pago para seguir gozando de todos los beneficios.',
            'wa_alumno_suspendida'=> 'Hola, mi acceso en Academia Nueva Era está suspendido. Quisiera regularizar mi pago para recuperar el acceso a mis clases.',
        ];

        $config = [];
        foreach ($defaults as $clave => $default) {
            $config[$clave] = Configuracion::get($clave, $default);
        }

        return view('dashboard.configuracion', compact('config'));
    }

    public function configuracionGuardar(Request $request)
    {
        $request->validate([
            'whatsapp_number'     => ['nullable', 'string', 'max:20'],
            'wa_msg_recordatorio' => ['nullable', 'string', 'max:1000'],
            'wa_msg_renovacion'   => ['nullable', 'string', 'max:1000'],
            'wa_msg_vencimiento'  => ['nullable', 'string', 'max:1000'],
            'wa_alumno_caducada'  => ['nullable', 'string', 'max:1000'],
            'wa_alumno_suspendida'=> ['nullable', 'string', 'max:1000'],
        ]);

        // Normalizar número: guardar solo los 9 dígitos
        $numero = preg_replace('/\D/', '', $request->whatsapp_number ?? '');
        if (str_starts_with($numero, '51') && strlen($numero) === 11) {
            $numero = substr($numero, 2);
        }
        Configuracion::set('whatsapp_number',     $numero ?: null);
        Configuracion::set('wa_msg_recordatorio', $request->wa_msg_recordatorio);
        Configuracion::set('wa_msg_renovacion',   $request->wa_msg_renovacion);
        Configuracion::set('wa_msg_vencimiento',  $request->wa_msg_vencimiento);
        Configuracion::set('wa_alumno_caducada',  $request->wa_alumno_caducada);
        Configuracion::set('wa_alumno_suspendida',$request->wa_alumno_suspendida);

        return redirect()->route('dashboard.configuracion')
            ->with('success', 'Configuración de WhatsApp guardada correctamente.');
    }
}
