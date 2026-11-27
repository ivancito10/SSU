<?php

namespace App\Models\Plataforma;

use App\Models\Administracion\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnulacionAgendaEcografia extends Model
{
    use HasFactory;

    protected $table = "plataforma.anulacion_agenda_ecografia";

    protected $fillable = [
        'id',
        'id_persona',
        'fecha_agenda',
        'id_asignacion',
        'nombre_equipo',
        'ip',
        'id_agenda_ecografia',
        'estado',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at',
    ];

    
    //Relación con Persona     
    public function personaAnulacionAgendaEcografia()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    
    //Relación con Asignacion Horario
    public function asignacionHorarioAnulacionAgendaEcografia()
    {
        return $this->belongsTo(AsignacionHorario::class,'id_asignacion','id');
    }

    //Relación con Agenda Ecografía
    public function agendaEcografiaAnulacion()
    {
        return $this->belongsTo(AgendaEcografia::class,'id_agenda_ecografia','id');
    }
    
    //Usuario creador
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class,'id_user_created','id');
    }

    //Usuario editor
    public function usuarioEditor()
    {
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
