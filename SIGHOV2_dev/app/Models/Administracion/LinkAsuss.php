<?php

namespace App\Models\Administracion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkAsuss extends Model
{
    use HasFactory;

    protected $table = "administracion.link_asuss";

    protected $fillable = [
        'id',
        'link',
        'descripcion',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
