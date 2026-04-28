<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionCalidad extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_calidad';

    protected $fillable = [
        'proyecto_id',
        'evaluado_por',
        'usabilidad',
        'rendimiento',
        'seguridad',
        'indice_calidad_global',
        'observaciones',
        'fecha_evaluacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_evaluacion' => 'datetime',
            'usabilidad' => 'decimal:2',
            'rendimiento' => 'decimal:2',
            'seguridad' => 'decimal:2',
            'indice_calidad_global' => 'decimal:2',
        ];
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function evaluador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'evaluado_por');
    }
}
