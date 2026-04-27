<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyectoMiembro extends Model
{
    use HasFactory;

    protected $table = "proyecto_miembros";

    protected $fillable = ["proyecto_id", "usuario_id", "fecha_asignacion"];

    protected $attributes = [
        'fecha_asignacion' => null,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->fecha_asignacion ??= now()->toDateString();
        });
    }

    protected $casts = [
        "fecha_asignacion" => "date",
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, "proyecto_id");
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, "usuario_id");
    }
}
