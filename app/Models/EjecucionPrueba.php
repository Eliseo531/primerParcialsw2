<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EjecucionPrueba extends Model
{
    use HasFactory;

    protected $table = 'ejecuciones_prueba';

    protected $fillable = [
        'caso_prueba_id',
        'ejecutado_por',
        'fecha_ejecucion',
        'resultado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ejecucion' => 'datetime',
        ];
    }

    public function casoPrueba(): BelongsTo
    {
        return $this->belongsTo(CasoPrueba::class, 'caso_prueba_id');
    }

    public function ejecutor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'ejecutado_por');
    }

    public function bugs(): BelongsToMany
    {
        return $this->belongsToMany(
            Bug::class,
            'bug_prueba',
            'ejecucion_prueba_id',
            'bug_id'
        )->withPivot('id')->withTimestamps();
    }
}
