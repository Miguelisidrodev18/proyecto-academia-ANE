<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Alumnos\AlumnoController;
use App\Http\Controllers\Matriculas\MatriculaController;
use App\Http\Controllers\Pagos\PagoController;
use App\Http\Controllers\Planes\PlanController;
use App\Http\Controllers\Cursos\CursoController;
use App\Http\Controllers\Clases\ClaseController;
use App\Http\Controllers\Materiales\MaterialController;
use App\Http\Controllers\Asistencias\AsistenciaController;
use App\Http\Controllers\Alumno\AlumnoPanelController;
use App\Http\Controllers\Leads\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contacto', [LeadController::class, 'store'])->name('leads.store-public');

Route::middleware(['auth'])->group(function () {

    // ── Dashboard (visible para todos los roles) ────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Dashboard stubs (próximamente) ─────────────────────────────────────
    Route::get('/dashboard/pagos',           [DashboardController::class, 'pagos'])->name('dashboard.pagos');
    Route::get('/dashboard/asistencia',      [DashboardController::class, 'asistencia'])->name('dashboard.asistencia');
    Route::get('/dashboard/aula-virtual',    [DashboardController::class, 'aulaVirtual'])->name('dashboard.aula-virtual');
    Route::get('/dashboard/bazar',           [DashboardController::class, 'bazar'])->name('dashboard.bazar');
    Route::get('/dashboard/reconocimientos', [DashboardController::class, 'reconocimientos'])->name('dashboard.reconocimientos');
    Route::get('/dashboard/reportes',        [DashboardController::class, 'reportes'])->name('dashboard.reportes');
    Route::get('/dashboard/configuracion',   [DashboardController::class, 'configuracion'])->name('dashboard.configuracion');

    // ── Panel del Alumno ────────────────────────────────────────────────────
    Route::middleware(['role:alumno'])->group(function () {
        Route::get('/mis-cursos',                [AlumnoPanelController::class, 'misCursos'])->name('alumno.mis-cursos');
        Route::get('/mis-cursos/{curso}/zoom',   [AlumnoPanelController::class, 'zoom'])->name('alumno.zoom');
        Route::get('/mis-cursos/{curso}',        [AlumnoPanelController::class, 'cursoDetalle'])->name('alumno.curso-detalle');
        Route::get('/mis-asistencias',           [AlumnoPanelController::class, 'asistencias'])->name('alumno.asistencias');
    });

    // ── Módulos: solo Admin ─────────────────────────────────────────────────
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/alumnos/dni/{numero}',                [AlumnoController::class, 'buscarDni'])->name('alumnos.dni');
        Route::get('/alumnos/buscar',                      [AlumnoController::class, 'buscar'])->name('alumnos.buscar');
        Route::get('/alumnos/{alumno}/matriculas-activas', [AlumnoController::class, 'matriculasActivas'])->name('alumnos.matriculas-activas');
        Route::get('/alumnos/{alumno}/credenciales',        [AlumnoController::class, 'credenciales'])->name('alumnos.credenciales');
        Route::post('/alumnos/{alumno}/reset-representante',  [AlumnoController::class, 'resetRepresentante'])->name('alumnos.reset-representante');
        Route::post('/alumnos/{alumno}/representante',         [AlumnoController::class, 'storeRepresentante'])->name('alumnos.store-representante');
        Route::patch('/alumnos/{alumno}/estado',           [AlumnoController::class, 'cambiarEstado'])->name('alumnos.cambiarEstado');
        Route::resource('alumnos', AlumnoController::class);

        Route::resource('matriculas', MatriculaController::class);

        Route::resource('pagos', PagoController::class);

        Route::resource('leads', LeadController::class)->except(['create', 'store']);

        Route::patch('/planes/{plan}/toggle',         [PlanController::class, 'toggle'])->name('planes.toggle');
        Route::patch('/planes/{plan}/toggle-landing', [PlanController::class, 'toggleLanding'])->name('planes.toggle-landing');
        Route::resource('planes', PlanController::class)->parameters(['planes' => 'plan']);
    });

    // ── Módulo Cursos + Aula Virtual: Admin y Docente ──────────────────────
    Route::middleware(['role:admin,docente'])->group(function () {
        Route::patch('/cursos/{curso}/toggle', [CursoController::class, 'toggle'])->name('cursos.toggle');
        Route::resource('cursos', CursoController::class);

        // Clases
        Route::resource('clases', ClaseController::class);
        Route::post('/cursos/{curso}/clases',      [ClaseController::class,  'storeFromCurso'])->name('cursos.clases.store');
        Route::patch('/clases/{clase}/grabacion',  [ClaseController::class,  'grabacion'])->name('clases.grabacion');

        // Asistencias (anidadas en una clase)
        Route::get('/clases/{clase}/asistencia',  [AsistenciaController::class, 'registrar'])->name('asistencias.registrar');
        Route::post('/clases/{clase}/asistencia', [AsistenciaController::class, 'guardar'])->name('asistencias.guardar');

        // Materiales
        Route::patch('/materiales/{material}/toggle', [MaterialController::class, 'toggle'])->name('materiales.toggle');
        Route::resource('materiales', MaterialController::class)->parameters(['materiales' => 'material']);
        Route::post('/cursos/{curso}/materiales', [MaterialController::class, 'storeFromCurso'])->name('cursos.materiales.store');
    });

    // ── Perfil (todos los roles) ────────────────────────────────────────────
    Route::get('/profile',           [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',         [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',        [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar',   [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
});

require __DIR__.'/auth.php';
