<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;





class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'created_by',
    ];

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function miembros(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class,
            'proyecto_miembros',
            'proyecto_id',
            'usuario_id'
        )->withPivot(['id', 'fecha_asignacion'])->withTimestamps();
    }
    public function modulos(): HasMany
    {
        return $this->hasMany(ModuloProyecto::class, 'proyecto_id');
    }
    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'proyecto_id');
    }

    public function casosPrueba(): HasMany
    {
        return $this->hasMany(CasoPrueba::class, 'proyecto_id');
    }

    public function metricas(): HasMany
    {
        return $this->hasMany(MetricaProyecto::class, 'proyecto_id');
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(EvaluacionCalidad::class, 'proyecto_id');
    }

    public function recomendaciones(): HasMany
    {
        return $this->hasMany(Recomendacion::class, 'proyecto_id');
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'proyecto_id');
    }
}
