<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Bug extends Model
{
    use HasFactory;

    protected $table = 'bugs';

    protected $fillable = [
        'proyecto_id',
        'modulo_id',
        'tarea_id',
        'titulo',
        'descripcion',
        'pasos_reproducir',
        'resultado_esperado',
        'resultado_actual',
        'severidad',
        'estado',
        'reportado_por',
        'asignado_a',
        'fecha_reporte',
        'fecha_resolucion',
        'tiempo_resolucion_horas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_reporte' => 'datetime',
            'fecha_resolucion' => 'datetime',
            'tiempo_resolucion_horas' => 'decimal:2',
        ];
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(ModuloProyecto::class, 'modulo_id');
    }

    public function reportero(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'reportado_por');
    }

    public function asignado(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'asignado_a');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialBug::class, 'bug_id');
    }

    public function ejecucionesPrueba(): BelongsToMany
    {
        return $this->belongsToMany(
            EjecucionPrueba::class,
            'bug_prueba',
            'bug_id',
            'ejecucion_prueba_id'
        )->withPivot('id')->withTimestamps();
    }

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }
}
