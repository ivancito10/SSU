<?php

namespace App\Models\Fisioterapia;

use Illuminate\Database\Eloquent\Model;

class Ordenes extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'consulta_externa.sol_fisioterapia';
    protected $guarded = ['*'];
    protected $keyType = 'string';
    protected $casts = [
        'id' => 'string', 'id_user_created' => 'string',
        'id_persona' => 'integer', 'nro_solicitud' => 'integer',
        'nro_sesion' => 'integer', 'created_at' => 'datetime',
    ];
}
