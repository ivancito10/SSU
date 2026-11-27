<?php

namespace App\Services;

use App\Models\Afiliacion\Titular;
use App\Models\Administracion\Persona;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Residencia;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\ContratoVoluntario;
use Illuminate\Support\Facades\Http;
use App\Services\RespuestaApisService;
use Carbon\Carbon;

class EstudiantesGehma2
{
    public $respuestaInsercion;
    public function insercionEstudiantesGehma2($idTitular)
    {

        $estudiante = Estudiante::with(['personaEstudiante', 'carreraEstudiante.facultadCarrera'])->find($idTitular);
        $matricula = $estudiante->personaEstudiante->matricula_seguro;
        $nombres = $estudiante->personaEstudiante->nombres;
        $apellidoPaterno = $estudiante->personaEstudiante->p_apellido;
        $apellidoMaterno = $estudiante->personaEstudiante->s_apellido;
        $fechaNacimiento = Carbon::parse($estudiante->personaEstudiante->fecha_nacimiento)->format('Y-m-d');
        $id_estadocivil = $estudiante->personaEstudiante->id_estado_civil;
        $sexo = $estudiante->personaEstudiante->sexo;

        if ($estudiante->personaEstudiante->complemento == null) {
            $DocIdentificacion = $estudiante->personaEstudiante->ci;
        } else {
            $DocIdentificacion = $estudiante->personaEstudiante->ci . '-' . $estudiante->personaEstudiante->complemento;
        }
        if ($estudiante->id_residencia != null) {
            $residenciaRes = Residencia::find($estudiante->id_residencia);
            $zonaRes = Zona::find($residenciaRes->id_zona);
            if ($residenciaRes->id_zona != null) {
                # code...
                $municipioRes = Municipio::find($zonaRes->id_municipio);
                $provinciaRes = Provincia::find($municipioRes->id_provincia);
                $id_departamento = $provinciaRes->id_departamento;
            }
        } 

        $id_departamentonac = 1;

        $idMunicipio = $estudiante->personaEstudiante->id_nacimiento_municipio;

        if ($idMunicipio!=null) {
            $municipio = Municipio::find($idMunicipio);

            $provincia = $municipio?->provincia;
            $id_departamentonac = $provincia?->id_departamento;
        }

        if ($residenciaRes->direccion != null) {
            $domicilio = strtoupper($residenciaRes->direccion);
        }
        $telefonoDomCel = $estudiante->telefono;
        $fechaRegistro = Carbon::parse($estudiante->fecha_afiliacion)->format('Y-m-d');

        if ($estudiante->personaEstudiante->alergias == null) {
            $alergias = '';
        } else {
            $alergias = mb_strtoupper($estudiante->personaEstudiante->alergias);
        }

        $telefonocontacto = $estudiante->telefono_referencia;

        if ($estudiante->nombre_referencia == null) {
            $detallecontacto = '';
        } else {
            $detallecontacto = mb_strtoupper($estudiante->nombre_referencia);
        }

        if ($estudiante->observacion == null) {
            $observaciones = '';
        } else {
            $observaciones = mb_strtoupper($estudiante->observacion);
        }

        $fechaAfiliacion = Carbon::parse($estudiante->fecha_afiliacion)->format('Y-m-d');
        $reg_univ = $estudiante->registro_universitario;
        $carrera = $estudiante->carreraEstudiante->nombre;
        $facultad = $estudiante->carreraEstudiante->facultadCarrera->nombre;

        $id_institucion = 510;
        $fechaIngreso = Carbon::now()->format('Y-m-d');

        $cargoActual = 'POR ACTUALIZAR';
        $salario = 0;

        $data = [
            // 'id_tipoafiliado' => 2,
            // 'secuencial' => 1,
            'matricula' => $matricula,
            'nombres' => $nombres,
            'apellidoPaterno' => $apellidoPaterno ?? null,
            'apellidoMaterno' => $apellidoMaterno ?? null,
            'apellidoEsposo' => '',
            'fechaNacimiento' => $fechaNacimiento,
            'id_estadocivil' => $id_estadocivil,
            'sexo' => $sexo,
            // 'id_tipoidentificacion' => "1",
            'DocIdentificacion' => $DocIdentificacion,
            'id_departamento' => $id_departamento ?? 1,
            'id_departamentonac' => $id_departamentonac ?? null,
            // 'id_zona' => "16",
            'domicilio' => $domicilio ?? 'sin especificar',
            'telefonoDomicilio' => $telefonoDomCel,
            'telefonoCelular' => $telefonoDomCel,
            'fechaRegistro' => $fechaRegistro,
            // 'id_gruposanguineo' => "9",
            'alergias' => $alergias,
            'telefonocontacto' => $telefonocontacto,
            'detallecontacto' => $detallecontacto,
            'observaciones' => $observaciones,
            // 'id_estadoAfiliado' => "2",
            'fechaAfiliacion' => $fechaAfiliacion,
            'nua' => $reg_univ,
            // 'cargoActual' => $facultad,
            'facultad' => $facultad,
            'salario' => $salario,
            'carrera' => $carrera,
            'id_institucion' => $id_institucion,
            'fecha_ingreso' => $fechaIngreso,
            // 'estadoRequisitos' => 0,
            // 'fechaVencimiento' => null,
            // 'estado_cambio' => 1,
            // 'estado_vigencia' => 1,
            // 'idUsuario' => 251
        ];
        // dd($data);

        $urlGehma2 = LinkServicio::where('nombre_servicio', 'INSERCION ESTUDIANTE GEHMA2')->first();
        $url_gehma2 = $urlGehma2->link_servicio;
        //dd($url_gehma2);
        // dd(Http::get('http://192.168.4.52:8080/APISGEHMA/RESTapiGehma2/public/api/departamentos')->json());
        $respuesta = Http::post($url_gehma2, $data);
        //dd($respuesta->json());
        //$response->successful();
        //dd($response->json());
        //dd($respuesta);
        $id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "ssu ude gehma2 afiliacion",
            "gehma2_Afiliacion",
            "SighoV2_Afiliacion Estudiantes",
            $id_user_created
        );
        $this->respuestaInsercion = $respuesta->json();
        return $this->respuestaInsercion;
    }
}
