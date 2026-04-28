<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\HasMany;



class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'rol_id',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre . ' ' . $this->apellido);
    }

    public function proyectos(): BelongsToMany
    {
        return $this->belongsToMany(
            Proyecto::class,
            'proyecto_miembros',
            'usuario_id',
            'proyecto_id'
        )->withPivot(['id', 'fecha_asignacion'])->withTimestamps();
    }

    public function bugsReportados(): HasMany
    {
        return $this->hasMany(Bug::class, 'reportado_por');
    }

    public function bugsAsignados(): HasMany
    {
        return $this->hasMany(Bug::class, 'asignado_a');
    }

    public function historialBugs(): HasMany
    {
        return $this->hasMany(HistorialBug::class, 'usuario_id');
    }

    public function casosPruebaCreados(): HasMany
    {
        return $this->hasMany(CasoPrueba::class, 'creado_por');
    }

    public function ejecucionesPrueba(): HasMany
    {
        return $this->hasMany(EjecucionPrueba::class, 'ejecutado_por');
    }

    public function evaluacionesCalidad(): HasMany
    {
        return $this->hasMany(EvaluacionCalidad::class, 'evaluado_por');
    }

    public function tareasAsignadas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'responsable_id');
    }

    public function tareasCreadas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'created_by');
    }
}
