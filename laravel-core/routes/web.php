<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Alumnos\AlumnoController;
use App\Http\Controllers\Matriculas\MatriculaController;
use App\Http\Controllers\Pagos\PagoController;
use App\Http\Controllers\Planes\PlanController;
use App\Http\Controllers\Cursos\CursoController;
use App\Http\Controllers\Alumno\AlumnoPanelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

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
        Route::get('/mis-cursos', [AlumnoPanelController::class, 'misCursos'])->name('alumno.mis-cursos');
    });

    // ── Módulos: solo Admin ─────────────────────────────────────────────────
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/alumnos/dni/{numero}',                [AlumnoController::class, 'buscarDni'])->name('alumnos.dni');
        Route::get('/alumnos/buscar',                      [AlumnoController::class, 'buscar'])->name('alumnos.buscar');
        Route::get('/alumnos/{alumno}/matriculas-activas', [AlumnoController::class, 'matriculasActivas'])->name('alumnos.matriculas-activas');
        Route::get('/alumnos/{alumno}/credenciales',       [AlumnoController::class, 'credenciales'])->name('alumnos.credenciales');
        Route::patch('/alumnos/{alumno}/estado',           [AlumnoController::class, 'cambiarEstado'])->name('alumnos.cambiarEstado');
        Route::resource('alumnos', AlumnoController::class);

        Route::resource('matriculas', MatriculaController::class);

        Route::resource('pagos', PagoController::class);

        Route::patch('/planes/{plan}/toggle', [PlanController::class, 'toggle'])->name('planes.toggle');
        Route::resource('planes', PlanController::class)->parameters(['planes' => 'plan']);
    });

    // ── Módulo Cursos: Admin y Docente ──────────────────────────────────────
    Route::middleware(['role:admin,docente'])->group(function () {
        Route::patch('/cursos/{curso}/toggle', [CursoController::class, 'toggle'])->name('cursos.toggle');
        Route::resource('cursos', CursoController::class);
    });

    // ── Perfil (todos los roles) ────────────────────────────────────────────
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
