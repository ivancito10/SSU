<?php

namespace App\Models\Publicos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoNoAfiliacion extends Model
{
    use HasFactory;
    protected $table = 'administracion.certificados_no_afiliacion';
    protected $fillable = [
        'nro_certificado',
        'nombres',
        'paterno',
        'materno',
        'fecha_emision',
        'fecha_validez',
        'codigo_seguridad',
        'ip',
        'dispositivo',
    ];
}
