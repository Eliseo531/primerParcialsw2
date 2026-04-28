<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetricaProyecto extends Model
{
    use HasFactory;

    protected $table = 'metricas_proyecto';

    protected $fillable = [
        'proyecto_id',
        'fecha_calculo',
        'total_bugs',
        'bugs_abiertos',
        'bugs_en_proceso',
        'bugs_cerrados',
        'total_pruebas',
        'pruebas_ok',
        'pruebas_fail',
        'tasa_exito_pruebas',
        'tiempo_promedio_resolucion',
        'densidad_defectos',
    ];

    protected function casts(): array
    {
        return [
            'fecha_calculo' => 'datetime',
            'tasa_exito_pruebas' => 'decimal:2',
            'tiempo_promedio_resolucion' => 'decimal:2',
            'densidad_defectos' => 'decimal:2',
        ];
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
