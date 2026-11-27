<?php

namespace App\Livewire\Afiliacion\AsignacionCodigo;

use App\Models\Afiliacion\CodigoInstitucionSsu;
use Livewire\Component;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\ClaseAportante;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Persona;
use Livewire\Attributes\On;
use Carbon\Carbon;
use App\Models\Migracion\AportesIntituciones;

//-------------
use App\Models\Afiliacion\Estudiante;


class CodigoInstitucionSsuMain extends Component
{
    public $codigo_libres = [];
    public $registro;
    public $id_insti;
    public $id_asignacion;
    public $instId;
    public $rowID;
    //----------
    public $nombre;
    public $actividad;
    public $nit;
    public $tipo;
    public $tipoclaseaportante, $acronimo, $nro_empleador;
    public $representante;
    public $direccion;
    public $puerta;
    public $zona;
    public $correo;
    public $telefono;
    public $fecha, $fecha_mostrar;
    public $codigo_ver;
    public $codigo_titular_ver;
    public $codigo_beneficiario_ver;
    public $titular;
    public $resultados;

    protected $listeners = ['GuardarAsignarCodigo'];

    public function render()
    {

        return view('livewire.Afiliacion.asignacion-codigo.codigo-institucion-ssu-main');
    }

    public function obtenerDatosInstitucion($idInstitucion)
    {
        $institucion = Institucion::find($idInstitucion);
        //dd($institucion);
        $this->nombre = $institucion->nombre;
        $this->actividad = $institucion->actividad_empleador;
        $this->nit = $institucion->nit;
        $this->correo = $institucion->correo;
        $this->telefono = $institucion->telefono;
        $this->fecha = $institucion->fecha_afiliacion;
        $this->fecha_mostrar = Carbon::parse($this->fecha)->Format('d /m/ Y ');
        $this->acronimo = $institucion->acronimo;
        $this->nro_empleador = $institucion->nro_empleador;


        $tipoAportante = ClaseAportante::find($institucion->id_clase_aportante);
        $this->tipo = $tipoAportante->tipo;

        $residencia = Residencia::find($institucion->id_residencia);
        $this->direccion = $residencia->direccion;
        $this->puerta = $residencia->nro_vivienda;

        $zonaActual = Zona::find($residencia->id_zona);
        $this->zona = $zonaActual->nombre;

        $personaActual = Persona::find($institucion->id_persona);
        $this->representante = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;

        $registro = CodigoInstitucionSsu::where('id_institucion', $idInstitucion)->first();
        if ($registro) {
            $this->codigo_titular_ver = $registro->codigo . $registro->tipo_titular;
            $this->codigo_beneficiario_ver = $registro->codigo . $registro->tipo_beneficiario;
        } else {
            $this->codigo_titular_ver = "";
            $this->codigo_beneficiario_ver = "";
        }
    }


    #[On('verDetalle')]
    public function verDetalle($data)
    {
        //dd($data);
        if ($data) {
            $this->obtenerDatosInstitucion($data);
        }
        $this->dispatch('abrirAsignacionCodigoInstitucionalModal');
    }

    #[On('asignarCodigoVista')]
    public function asignarCodigoVista($data)
    {
        //dd($data);
        $this->codigo_libres = CodigoInstitucionSsu::where('usado', false)->orderBy('id', 'asc')->get();

        //dd($this->codigo_libres);
        if ($data) {
            $this->obtenerDatosInstitucion($data);
            $this->instId = $data;
        }
        $this->dispatch('abrirModal');
    }

    public function mostrarCodigos()
    {
        //dd($this->codigo_ver);
        try {
            $codigoSeleccionado = CodigoInstitucionSsu::where('id', $this->codigo_ver)->where('usado', false)->orderBy('id', 'asc')->first();
            if ($codigoSeleccionado) {
                $this->codigo_titular_ver = $codigoSeleccionado->codigo . $codigoSeleccionado->tipo_titular;
                $this->codigo_beneficiario_ver = $codigoSeleccionado->codigo . $codigoSeleccionado->tipo_beneficiario;
            } else {
                $this->codigo_titular_ver = '';
                $this->codigo_beneficiario_ver = '';
            }
        } catch (\Throwable $th) {
            $this->codigo_titular_ver = '';
            $this->codigo_beneficiario_ver = '';
        }

        $this->skipRender();
    }

    public function GuardarAsignarCodigo()
    {
        try {
            $asignarCodigo = CodigoInstitucionSsu::find($this->codigo_ver);
            if ($asignarCodigo && $asignarCodigo->id_institucion == null && $asignarCodigo->usado == false) {
                $asignarCodigo->id_institucion = $this->instId;
                $asignarCodigo->usado = true;
                $asignarCodigo->id_user_created = auth()->user()->id;
                $asignarCodigo->save();
                $this->dispatch('pg:eventRefresh-AsignacionCodigoAfiliacion');
                $this->dispatch('AsignadoCorrectamente');
            } else {
                $this->dispatch('AsignadoIncorrectamente');
            }
        } catch (\Throwable $th) {
            $this->dispatch('AsignadoIncorrectamente');
        }
    }

    #[On('eliminarAsignacion')]
    public function eliminarAsignacion($data)
    {
        $this->id_insti = $data;
        $this->dispatch('ConfirmarEliminarCodigo');
    }

    #[On('eliminarCodFunction')]
    public function eliminarCodFunction()
    {
        // try {
        $codigoAsignado = CodigoInstitucionSsu::where('id_institucion',  $this->id_insti)->first();
        if ($codigoAsignado && $codigoAsignado->id_institucion != null && $codigoAsignado == true) {
            $codigoAsignado->id_institucion = null;
            $codigoAsignado->usado = false;
            $codigoAsignado->id_user_updated = auth()->user()->id;
            $codigoAsignado->save();
            $this->dispatch('pg:eventRefresh-AsignacionCodigoAfiliacion');
            $this->dispatch('SeDesasignadoCorrectamente');
        } else {
            $this->dispatch('SeDesasignadoError');
        }
        //   } catch (\Throwable $th) {
        //       $this->dispatch('SeDesasignadoError');
        //   }  
    }




    /* public function verificarDatosEstudiantes()
    {
        $idsDuplicados = Estudiante::select('id_persona')
            ->groupBy('id_persona')
            ->havingRaw('COUNT(*) = 2')
            ->pluck('id_persona');

        foreach ($idsDuplicados as $idPersona) {

            $ultimoRegistroActivoEstudiante = Estudiante::where('id_persona', $idPersona)
                ->where('estado', true)
                ->orderByDesc('id')
                ->first();

            $ultimoRegistroInactivoEstudiante = Estudiante::where('id_persona', $idPersona)
                ->where('estado', false)
                ->orderByDesc('id')
                ->first();


            if (!$ultimoRegistroActivoEstudiante || !$ultimoRegistroInactivoEstudiante) {
                continue;
            }

            $fecha_afiliacion_activo = $ultimoRegistroActivoEstudiante->fecha_afiliacion;

            $fecha_afiliacion_inactivo = $ultimoRegistroInactivoEstudiante->fecha_afiliacion;

            $ultimoRegistroActivoEstudiante->update([
                'fecha_afiliacion'   => $fecha_afiliacion_inactivo,
                'fecha_reafiliacion' => $fecha_afiliacion_activo,
                'afiliacion'         => false,
            ]);
        }
    } */

    /*   public function verificarDatosEstudiantes2()
    {
        $idsDuplicados = Estudiante::select('id_persona')
            ->groupBy('id_persona')
            ->havingRaw('COUNT(*) = 3')
            ->pluck('id_persona');
        // dd($idsDuplicados);

        foreach ($idsDuplicados as $idPersona) {
            //$idPersona = 16443;
            $primerRegistroInactivoEstudiante = Estudiante::where('id_persona', $idPersona)
                ->where('estado', false)
                ->orderBy('id')
                ->first();

            $ultimoRegistroInactivoEstudiante = Estudiante::where('id_persona', $idPersona)
                ->where('estado', false)
                ->orderByDesc('id')
                ->first();

            $ultimoRegistroActivoEstudiante = Estudiante::where('id_persona', $idPersona)
                ->where('estado', true)
                ->orderByDesc('id')
                ->first();

            // dd($primerRegistroInactivoEstudiante, $ultimoRegistroInactivoEstudiante, $ultimoRegistroActivoEstudiante);


            if (!$ultimoRegistroActivoEstudiante || !$ultimoRegistroInactivoEstudiante || !$primerRegistroInactivoEstudiante) {
                continue;
            }

            $fecha_ingreso  = $primerRegistroInactivoEstudiante->fecha_afiliacion;
            $fecha_afiliacion_inactivo = $ultimoRegistroInactivoEstudiante->fecha_afiliacion;
            $fecha_afiliacion_activo = $ultimoRegistroActivoEstudiante->fecha_afiliacion;


            $ultimoRegistroInactivoEstudiante->update([
                'fecha_afiliacion'   => $fecha_ingreso,
                'fecha_reafiliacion' => $fecha_afiliacion_inactivo,
                'afiliacion'         => false,
            ]);

            $ultimoRegistroActivoEstudiante->update([
                'fecha_afiliacion'   => $fecha_ingreso,
                'fecha_reafiliacion' => $fecha_afiliacion_activo,
                'afiliacion'         => false,
            ]);
        }
    } */
}
