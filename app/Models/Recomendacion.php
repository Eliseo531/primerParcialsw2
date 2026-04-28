<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recomendacion extends Model
{
    use HasFactory;

    protected $table = 'recomendaciones';

    protected $fillable = [
        'proyecto_id',
        'modulo_id',
        'tipo',
        'descripcion',
        'prioridad',
        'generado_por_sistema',
        'estado',
        'fecha_generacion',
    ];

    protected function casts(): array
    {
        return [
            'generado_por_sistema' => 'boolean',
            'fecha_generacion' => 'datetime',
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
}
