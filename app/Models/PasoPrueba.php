<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasoPrueba extends Model
{
    protected $table = 'pasos_prueba';

    protected $fillable = [
        'caso_prueba_id',
        'orden',
        'descripcion',
    ];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(CasoPrueba::class, 'caso_prueba_id');
    }
}
