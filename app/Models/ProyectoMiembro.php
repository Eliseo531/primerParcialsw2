<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoMiembro extends Model
{
    use HasFactory;

    protected $table = 'proyecto_miembros';

    protected $fillable = [
        'proyecto_id',
        'usuario_id',
        'fecha_asignacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_asignacion' => 'date',
        ];
    }
}
