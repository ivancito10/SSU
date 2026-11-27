<?php

namespace App\Models\Vigencia;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Aportes\Institucion;
use App\Models\User;
use App\Models\Vigencia\TipoConvenio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preafiliacion extends Model
{
    use HasFactory;

    protected $table = "vvdd.preafiliacion";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nro_preafiliacion',
        'fecha_inicio',
        'fecha_fin',
        'id_tipo_convenio',
        'id_persona',
        'observaciones',
        'tipo_preafiliacion',
        'id_titular',
        'id_institucion',
        'nro_doc_respaldo',
        'estado',
        'telefono',
        'id_user_created',
        'id_user_updated'
    ];

    //RELACIONES CON LA TABLA TIPO CONVENIO
    public function tipoConvenioPreafiliacion(){
        return $this->belongsTo(TipoConvenio::class, 'id_tipo_convenio', 'id');
    }

    //RELACIONES CON LA TABLA PERSONA
    public function personaPreafiliacion(){
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //RELACIONES CON LA TABLA TITULAR
    public function titularPreafiliacion(){
        return $this->belongsTo(Titular::class, 'id_titular', 'id');
    }

    //RELACIONES CON LA TABLA INSTITUCION
    public function institucionPreafiliacion(){
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }



}
