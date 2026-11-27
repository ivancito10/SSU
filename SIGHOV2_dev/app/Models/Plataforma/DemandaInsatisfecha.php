<?php

namespace App\Models\Plataforma;

use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandaInsatisfecha extends Model
{
    use HasFactory;

    protected $table = "plataforma.demanda_insatisfecha";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha_demanda_insatisfecha',
        'id_persona',
        'id_especialista',
        'id_especialidad',
        'id_asignacion_horario',
        'ip_equipo',
        'nombre_equipo',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA PERSONA
    public function personaDemandaInsatisfecha(){
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //RELACIONES CON LA TABLA ESPECIALISTA
    public function especialistaDemandaInsatisfecha(){
        return $this->belongsTo(Especialista::class, 'id_especialista', 'id');
    }

    //RELACIONES CON LA TABLA ESPECIALIDAD
    public function especialidadDemandaInsatisfecha(){
        return $this->belongsTo(Especialidad::class, 'id_especialidad', 'id');
    }

    //RELACIONES CON LA TABLA USERS
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
