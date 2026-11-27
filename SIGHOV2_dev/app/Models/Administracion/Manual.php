<?php

namespace App\Models\Administracion;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    protected $table = 'administracion.manuales';

    public const MODULOS = [
        'ADMINISTRACIÓN',
        'AFILIACIÓN',
        'ALMACÉN GENERAL',
        'APORTES',
        'ATENCIÓN MÉDICA',
        'BIOESTADÍSTICA',
        'CONSULTA EXTERNA',
        'ENFERMERÍA',
        'FARMACIA',
        'FISIOTERAPIA',
        'IMAGENOLOGÍA',
        'LABORATORIO',
        'LIQUIDACIONES',
        'PLATAFORMA',
        'RAYOS X',
        'REPORTES',
        'SOPORTE TÉCNICO',
        'VIGENCIA',
    ];

    protected $fillable = [
        'modulo',
        'titulo',
        'descripcion',
        'version',
        'nombre_archivo',
        'ruta_ftp',
        'tamano_bytes',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'estado'       => 'boolean',
        'tamano_bytes' => 'integer',
    ];

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

    public function getTamanoFormateadoAttribute(): string
    {
        $bytes = (int) ($this->tamano_bytes ?? 0);
        if ($bytes <= 0) {
            return 'S/D';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        return number_format($bytes / 1024, 1) . ' KB';
    }
}
