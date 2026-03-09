<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumnoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Módulo Alumnos
    Route::get('/dashboard/alumnos', [AlumnoController::class, 'index'])->name('dashboard.alumnos');
    Route::resource('alumnos', AlumnoController::class);
    Route::get('/alumnos/dni/{numero}',                  [AlumnoController::class, 'buscarDni'])->name('alumnos.dni');
    Route::get('/alumnos/{alumno}/documentos',           [AlumnoController::class, 'documentos'])->name('alumnos.documentos');
    Route::post('/alumnos/{alumno}/documentos',          [AlumnoController::class, 'subirDocumento'])->name('alumnos.documentos.subir');
    Route::delete('/alumnos/{alumno}/documentos/{indice}',[AlumnoController::class, 'eliminarDocumento'])->name('alumnos.documentos.eliminar');
    Route::get('/dashboard/matriculas',         [DashboardController::class, 'matriculas'])->name('dashboard.matriculas');
    Route::get('/dashboard/pagos',              [DashboardController::class, 'pagos'])->name('dashboard.pagos');
    Route::get('/dashboard/asistencia',         [DashboardController::class, 'asistencia'])->name('dashboard.asistencia');
    Route::get('/dashboard/aula-virtual',       [DashboardController::class, 'aulaVirtual'])->name('dashboard.aula-virtual');
    Route::get('/dashboard/bazar',              [DashboardController::class, 'bazar'])->name('dashboard.bazar');
    Route::get('/dashboard/reconocimientos',    [DashboardController::class, 'reconocimientos'])->name('dashboard.reconocimientos');
    Route::get('/dashboard/reportes',           [DashboardController::class, 'reportes'])->name('dashboard.reportes');
    Route::get('/dashboard/configuracion',      [DashboardController::class, 'configuracion'])->name('dashboard.configuracion');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
