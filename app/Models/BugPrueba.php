<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugPrueba extends Model
{
    use HasFactory;

    protected $table = 'bug_prueba';

    protected $fillable = [
        'bug_id',
        'ejecucion_prueba_id',
    ];

    public function bug(): BelongsTo
    {
        return $this->belongsTo(Bug::class, 'bug_id');
    }

    public function ejecucionPrueba(): BelongsTo
    {
        return $this->belongsTo(EjecucionPrueba::class, 'ejecucion_prueba_id');
    }
}
