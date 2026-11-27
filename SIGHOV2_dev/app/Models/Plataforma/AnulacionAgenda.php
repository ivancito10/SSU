<?php

namespace App\Models\Plataforma;

use App\Models\Administracion\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnulacionAgenda extends Model
{
    use HasFactory;
    protected $table = "plataforma.anulacion_agenda";
    protected $fillable = [
        'id',
        'id_persona',
        'fecha_agenda',
        'id_asignacion',
        'nombre_equipo',
        'ip',
        'id_agenda',
        'estado',
        'id_user_created',
        'id_user_updated',
        'create_ad',
        'update_ad',
    ];
    public function personaAnulacionAgenda()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
    public function AsignacionHorarioAnulacionAgenda()
    {
        return $this->belongsTo(AsignacionHorario::class, 'id_asignacion', 'id');
    }
    public function AgendaAnulacionAgenda()
    {
        return $this->belongsTo(Agenda::class, 'id_agenda', 'id');
    }

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
