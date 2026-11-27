<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ControlHorario extends Model
{
    use HasFactory;
    protected $table = "plataforma.control_horario";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'posicion',
        'porcentaje',
        'hora_inicio',
        'hora_fin',
        'estado',
        'id_user_created',
        'id_user_updated',
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
