<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuloProyecto extends Model
{
    use HasFactory;

    protected $table = 'modulos_proyecto';

    protected $fillable = [
        'proyecto_id',
        'nombre',
        'descripcion',
        'estado',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'modulo_id');
    }
    public function casosPrueba(): HasMany
    {
        return $this->hasMany(CasoPrueba::class, 'modulo_id');
    }

    public function recomendaciones(): HasMany
    {
        return $this->hasMany(Recomendacion::class, 'modulo_id');
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'modulo_id');
    }
}
