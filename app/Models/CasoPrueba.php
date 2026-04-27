<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CasoPrueba extends Model
{
    protected $table = 'casos_prueba';

    protected $fillable = [
        'nombre',
        'descripcion',
        'condiciones',
        'resultado_esperado',
        'proyecto_id',
        'creado_por',
    ];

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function pasos(): HasMany
    {
        return $this->hasMany(PasoPrueba::class, 'caso_prueba_id')->orderBy('orden');
    }

    public function ejecuciones(): HasMany
    {
        return $this->hasMany(EjecucionPrueba::class, 'caso_prueba_id')->orderBy('fecha_ejecucion', 'desc');
    }
}
