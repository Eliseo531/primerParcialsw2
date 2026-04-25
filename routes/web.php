<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProyectoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');

    // Gestión de bugs
    Route::get('/bugs', [BugController::class, 'index'])->name('bugs.index');
    Route::get('/bugs/crear', [BugController::class, 'create'])->name('bugs.create');
    Route::post('/bugs', [BugController::class, 'store'])->name('bugs.store');
    Route::get('/bugs/{bug}', [BugController::class, 'show'])->name('bugs.show');
    Route::get('/bugs/{bug}/editar', [BugController::class, 'edit'])->name('bugs.edit');
    Route::put('/bugs/{bug}', [BugController::class, 'update'])->name('bugs.update');
    Route::post('/bugs/{bug}/asignar', [BugController::class, 'asignar'])->name('bugs.asignar');
    Route::post('/bugs/{bug}/estado', [BugController::class, 'cambiarEstado'])->name('bugs.cambiarEstado');
    // Gestión de proyectos
    Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index'); 
    Route::get('/proyectos/crear', [ProyectoController::class, 'create'])->name('proyectos.create');
    Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('/proyectos/{proyecto}/editar', [ProyectoController::class, 'edit'])->name('proyectos.edit');
    Route::put('/proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{proyecto}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy');

    // Asignar miembros
    Route::post('/proyectos/{proyecto}/miembros', [ProyectoController::class, 'asignarMiembros'])->name('proyectos.miembros');

});
