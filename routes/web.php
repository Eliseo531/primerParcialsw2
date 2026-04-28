<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ModuloProyectoController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\CasoPruebaController;
use App\Http\Controllers\EjecucionPruebaController;
use App\Http\Controllers\MetricaProyectoController;
use App\Http\Controllers\EvaluacionCalidadController;
use App\Http\Controllers\RecomendacionController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTA INICIAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| SISTEMA (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRADOR
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Administrador')->group(function () {

        // Usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');

        // Proyectos
        Route::get('/proyectos/crear', [ProyectoController::class, 'create'])->name('proyectos.create');
        Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');

        // Miembros
        Route::get('/proyectos/{proyecto}/miembros', [ProyectoController::class, 'miembros'])->name('proyectos.miembros');
        Route::post('/proyectos/{proyecto}/miembros', [ProyectoController::class, 'asignarMiembro'])->name('proyectos.miembros.asignar');
        Route::delete('/proyectos/{proyecto}/miembros/{usuario}', [ProyectoController::class, 'quitarMiembro'])->name('proyectos.miembros.quitar');

        // Módulos
        Route::get('/proyectos/{proyecto}/modulos/crear', [ModuloProyectoController::class, 'create'])->name('proyectos.modulos.create');
        Route::post('/proyectos/{proyecto}/modulos', [ModuloProyectoController::class, 'store'])->name('proyectos.modulos.store');
        Route::get('/proyectos/{proyecto}/modulos/{modulo}/editar', [ModuloProyectoController::class, 'edit'])->name('proyectos.modulos.edit');
        Route::put('/proyectos/{proyecto}/modulos/{modulo}', [ModuloProyectoController::class, 'update'])->name('proyectos.modulos.update');

        // Métricas
        Route::get('/metricas', [MetricaProyectoController::class, 'index'])->name('metricas.index');
        Route::post('/metricas/{proyecto}/calcular', [MetricaProyectoController::class, 'calcular'])->name('metricas.calcular');

        // Calidad
        Route::get('/evaluaciones-calidad', [EvaluacionCalidadController::class, 'index'])->name('evaluaciones-calidad.index');
        Route::get('/evaluaciones-calidad/crear', [EvaluacionCalidadController::class, 'create'])->name('evaluaciones-calidad.create');
        Route::post('/evaluaciones-calidad', [EvaluacionCalidadController::class, 'store'])->name('evaluaciones-calidad.store');
        Route::post('/evaluaciones-calidad/preview', [EvaluacionCalidadController::class, 'calcularPreview'])
            ->name('evaluaciones-calidad.preview');
        Route::get('/evaluaciones-calidad/{evaluacion}', [EvaluacionCalidadController::class, 'show'])->name('evaluaciones-calidad.show');

        // Recomendaciones
        Route::get('/recomendaciones', [RecomendacionController::class, 'index'])->name('recomendaciones.index');
        Route::post('/recomendaciones/{proyecto}/generar', [RecomendacionController::class, 'generar'])->name('recomendaciones.generar');
        Route::patch('/recomendaciones/{recomendacion}/estado', [RecomendacionController::class, 'actualizarEstado'])->name('recomendaciones.estado');

        // Tareas (CRUD ADMIN)
        Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
        Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
        Route::get('/tareas/{tarea}/editar', [TareaController::class, 'edit'])->name('tareas.edit');
        Route::put('/tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN / TESTER
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Administrador,Tester')->group(function () {

        // Crear bugs
        Route::get('/bugs/crear', [BugController::class, 'create'])->name('bugs.create');
        Route::post('/bugs', [BugController::class, 'store'])->name('bugs.store');

        // Casos de prueba
        Route::get('/casos-prueba/crear', [CasoPruebaController::class, 'create'])->name('casos-prueba.create');
        Route::post('/casos-prueba', [CasoPruebaController::class, 'store'])->name('casos-prueba.store');

        // Ejecuciones
        Route::get('/casos-prueba/{casoPrueba}/ejecuciones/crear', [EjecucionPruebaController::class, 'create'])->name('casos-prueba.ejecuciones.create');
        Route::post('/casos-prueba/{casoPrueba}/ejecuciones', [EjecucionPruebaController::class, 'store'])->name('casos-prueba.ejecuciones.store');

        // Relación bug
        Route::get('/ejecuciones-prueba/{ejecucion}/relacionar-bug', [EjecucionPruebaController::class, 'relacionarBug'])->name('ejecuciones-prueba.relacionar-bug');
        Route::post('/ejecuciones-prueba/{ejecucion}/relacionar-bug', [EjecucionPruebaController::class, 'guardarRelacionBug'])->name('ejecuciones-prueba.guardar-relacion-bug');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN / TESTER / DESARROLLADOR
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Administrador,Tester,Desarrollador')->group(function () {

        // Proyectos
        Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
        Route::get('/proyectos/{proyecto}/modulos', [ModuloProyectoController::class, 'index'])->name('proyectos.modulos.index');

        // Bugs (IMPORTANTE: desarrollador puede ver)
        Route::get('/bugs', [BugController::class, 'index'])->name('bugs.index');
        Route::get('/bugs/{bug}', [BugController::class, 'show'])->name('bugs.show');

        // Pruebas
        Route::get('/casos-prueba', [CasoPruebaController::class, 'index'])->name('casos-prueba.index');
        Route::get('/casos-prueba/{casoPrueba}/ejecuciones', [EjecucionPruebaController::class, 'index'])->name('casos-prueba.ejecuciones.index');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN / DESARROLLADOR
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Administrador,Desarrollador')->group(function () {

        // Cambiar estado bug
        Route::patch('/bugs/{bug}/estado', [BugController::class, 'cambiarEstado'])->name('bugs.cambiarEstado');

        // Tareas
        Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
        Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
        Route::patch('/tareas/{tarea}/estado', [TareaController::class, 'cambiarEstado'])->name('tareas.estado');
    });
});
