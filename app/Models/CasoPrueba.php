<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CasoPrueba extends Model
{
    use HasFactory;

    protected $table = 'casos_prueba';

    protected $fillable = [
        'proyecto_id',
        'modulo_id',
        'titulo',
        'descripcion',
        'precondiciones',
        'pasos',
        'resultado_esperado',
        'creado_por',
        'estado',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(ModuloProyecto::class, 'modulo_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function ejecuciones(): HasMany
    {
        return $this->hasMany(EjecucionPrueba::class, 'caso_prueba_id');
    }
}
