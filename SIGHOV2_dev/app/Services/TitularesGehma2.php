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
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\ContratoVoluntario;
use Illuminate\Support\Facades\Http;
use App\Services\RespuestaApisService;
use Carbon\Carbon;

class TitularesGehma2
{
    public $respuestaInsercion;
    public function insercionTitularGehma2($idTitular)
    {
        $titular = Titular::with('titularPersona')->find($idTitular);

        $matricula = $titular->titularPersona->matricula_seguro;
        $nombres = $titular->titularPersona->nombres;
        $apellidoPaterno = $titular->titularPersona->p_apellido;
        $apellidoMaterno = $titular->titularPersona->s_apellido;
        $fechaNacimiento = Carbon::parse($titular->titularPersona->fecha_nacimiento)->format('Y-m-d');
        $id_estadocivil = $titular->titularPersona->id_estado_civil;


        $sexo = $titular->titularPersona->sexo;
        if ($titular->titularPersona->complemento == null) {
            $DocIdentificacion = $titular->titularPersona->ci;
        } else {
            $DocIdentificacion = $titular->titularPersona->ci . '-' . $titular->titularPersona->complemento;
        }

        $residenciaRes = Residencia::find($titular->id_residencia);
        $zonaRes = Zona::find($residenciaRes->id_zona);
        $municipioRes = Municipio::find($zonaRes->id_municipio);
        $provinciaRes = Provincia::find($municipioRes->id_provincia);
        $id_departamento = $provinciaRes->id_departamento ?? null;
        
        $es_extranjero=$titular->titularPersona->es_extranjero;
        if ($titular->titularPersona->es_extranjero === false) {
            $municipioNac = Municipio::find($titular->titularPersona->id_nacimiento_municipio);
            $provinciaNac = Provincia::find($municipioNac->id_provincia);
            $id_departamentonac = $provinciaNac->id_departamento;    
        } else {
            $municipioNac = null;
            $provinciaNac = null;
            $id_departamentonac = 1;
        }
        $domicilio = strtoupper($residenciaRes->direccion);

        $telefonoDomCel = $titular->telefono;
        $fechaRegistro = Carbon::parse($titular->fecha_afiliacion)->format('Y-m-d');

        if ($titular->alergias == null) {
            $alergias = '';
        } else {
            $alergias = mb_strtoupper($titular->alergias);
        }

        $telefonocontacto = $titular->telefono_referencia;

        if($titular->nombre_referencia == null){
            $detallecontacto = '';
        } else {
            $detallecontacto = mb_strtoupper($titular->nombre_referencia);
        }       

        if ($titular->observacion == null) {
            $observaciones = '';
        } else {
            $observaciones = mb_strtoupper($titular->observacion);
        }

        $fechaAfiliacion = Carbon::parse($titular->fecha_afiliacion)->format('Y-m-d');
        // ! logica para obtener el salario , cargo e insitiucion del titular
        $datoTrabajo = TitularInstitucion::where('id_titular', $idTitular)->where('estado', true)->first();
        // dd($datoTrabajo);
        $id_institucion = null;
        $fechaIngreso = Carbon::now()->format('Y-m-d');
        
        if($datoTrabajo){
            $cargoActual = mb_strtoupper($datoTrabajo->cargo_actual);
            $salario = mb_strtoupper($datoTrabajo->salario);
            $id_institucion = mb_strtoupper($datoTrabajo->id_institucion);
            // dd($id_institucion);
            $fechaIngreso = Carbon::parse($datoTrabajo->fecha_ingreso)->format('Y-m-d');
             // $id_dato_trabajo=$datoTrabajo->id_institucion;
        }else{
            $cargoActual = 'POR ACTUALIZAR';
            $salario = 0;
        }

        if($id_institucion == null){
            $id_institucion = 25;
            $contratoVoluntario = ContratoVoluntario::where('id_persona', $titular->titularPersona->id)
                ->where('estado', true)
                ->orderBy('id', 'desc')->first();
                if ($contratoVoluntario==null) {
                    $fechaIngreso = Carbon::now()->format('Y-m-d');
                }else{
                    $fechaIngreso = $contratoVoluntario->fecha_inicio_contrato;
                }
        }

        $data = [
            // 'id_tipoafiliado' => 2,
            // 'secuencial' => 1,
            'es_extranjero'=>$es_extranjero,
            'matricula' => $matricula,
            'nombres' => $nombres,
            'apellidoPaterno' => $apellidoPaterno,
            'apellidoMaterno' => $apellidoMaterno,
            'apellidoEsposo' => '',
            'fechaNacimiento' => $fechaNacimiento,
            'id_estadocivil' => $id_estadocivil,
            'sexo' => $sexo,
            // 'id_tipoidentificacion' => "1",
            'DocIdentificacion' => $DocIdentificacion,
            'id_departamento' => $id_departamento,
            'id_departamentonac' => $id_departamentonac ?? 1,
            // 'id_zona' => "16",
            'domicilio' => $domicilio,
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
            'nua' => null,
            'cargoActual' => $cargoActual ,
            'salario' => $salario ,
            'id_institucion' => $id_institucion,
            'fecha_ingreso' => $fechaIngreso ,
            // 'estadoRequisitos' => 0,
            // 'fechaVencimiento' => null,
            // 'estado_cambio' => 1,
            // 'estado_vigencia' => 1,
            // 'idUsuario' => 251
        ];
        //dd($data);

        $urlGehma2 = LinkServicio::where('nombre_servicio', 'INSERCION TITULAR GEHMA2')->first();
        $url_gehma2 = $urlGehma2->link_servicio;
        //dd($url_gehma2);
        // dd(Http::get('http://192.168.4.52:8080/APISGEHMA/RESTapiGehma2/public/api/departamentos')->json());
        $respuesta = Http::post($url_gehma2, $data);
        //dd($respuesta->json());
        //$response->successful();
        //dd($response->json());
        //dd($respuesta);
        $id_user_created=auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "ssu ude gehma2 afiliacion",
            "gehma2_Afiliacion",
            "SighoV2_Afiliacion titulares",
            $id_user_created
        );
        $this->respuestaInsercion = $respuesta->json();
        return $this->respuestaInsercion;
    }
}
