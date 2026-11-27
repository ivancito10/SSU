<?php

namespace App\Models\Administracion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlRegistroAsuss extends Model
{
    use HasFactory;

    protected $table = "administracion.control_registro_asuss";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'id_persona',
        'id_tipo_asegurado',
        'id_corresponde',
        'id_titular',
        'id_institucion',
        'respuesta_contrastacion_json',
        'respuesta_insercion_json',
        'registro_exitoso',
        'afiliacion',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'respuesta_contrastacion_json' => 'array',
        'respuesta_insercion_json' => 'array',
    ];

    //Relaciones con la Tabla Usuario
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
