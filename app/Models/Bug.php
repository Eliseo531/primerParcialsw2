<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bug extends Model
{
    use HasFactory;

    protected $table = 'bugs';

    protected $fillable = [
        'proyecto_id',
        'modulo_id',
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

    protected $casts = [
        'fecha_reporte'    => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function reportador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'reportado_por');
    }

    public function asignado(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'asignado_a');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialBug::class, 'bug_id')->orderBy('fecha_cambio', 'desc');
    }
}
