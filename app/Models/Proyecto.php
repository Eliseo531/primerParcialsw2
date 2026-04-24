<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Modelo stub creado como dependencia del módulo de Bugs.
// TODO: el responsable de Gestión de Proyectos debe expandir este modelo.
class Proyecto extends Model
{
    protected $table = 'proyectos';

    protected $fillable = ['nombre'];

    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'proyecto_id');
    }
}
