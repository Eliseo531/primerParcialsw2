<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EjecucionPrueba extends Model
{
    protected $table = 'ejecuciones_prueba';

    protected $fillable = [
        'caso_prueba_id',
        'ejecutado_por',
        'resultado',
        'observaciones',
        'fecha_ejecucion',
    ];

    protected $casts = [
        'fecha_ejecucion' => 'datetime',
    ];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(CasoPrueba::class, 'caso_prueba_id');
    }

    public function ejecutor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'ejecutado_por');
    }

    public function bugs(): BelongsToMany
    {
        return $this->belongsToMany(Bug::class, 'ejecucion_bug', 'ejecucion_id', 'bug_id');
    }
}
