<?php

namespace App\Livewire\Vigencia\SeguroInterior;

use Livewire\Component;
use App\Models\Vigencia\SeguroInterior;
use App\Services\ValidarPersona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Persona;
use Livewire\Attributes\Validate;
use Carbon\Carbon;

class SeguroInteriorEdit extends Component
{
    public $idInstConvenio;

    public $nombre_seguro;
    #[Validate]
    public $telefono;
    #[Validate]
    public $fax;
    public $id_usuario_responsable;
    public $sigla_seguro;
    public $nit;
    public $id_residencia;
    #[Validate]
    public $id_zona;
    public $nombreZona;
    public $zonas = [];
    #[Validate]
    public $direccion;
    #[Validate]
    public $nro_vivienda;
    #[Validate]
    public $correo;
    public $nro_empleador;
    public $id_rep_legal;
    public $latitud;
    public $longitud;

    public $nombreCompletoRepLegal;
    #[Validate]
    public $nombresRepLegal;
    #[Validate]
    public $p_apellidoRepLegal;
    #[Validate]
    public $s_apellidoRepLegal;
    public $carnetRepLegal;
    #[Validate]
    public $ciRepLegal;
    public $complementoRepLegal;
    #[Validate]
    public $fecha_nacimientoRepLegal;
    
    public $nombreCompletoResponsable;
    public $carnetResponsable;
    public $usuarioResponsable;
    
    public $ciBusqueda;
    public $complementoBusqueda;
    public $estado_asuss;
    public $sinDatos = false;

    protected $listeners = ['actualizarSeguroInterior'];

    public function rules()
    {
        return [
        'direccion' => 'required|min:5',
        'id_zona' => 'required',
        'nro_vivienda' => 'required|min:1',
        'telefono' => 'required|min:6',
        'correo' => 'required|email',
        'fax' => 'required|min:1',
        'nombresRepLegal' => 'required',
        'p_apellidoRepLegal' => 'required',
        's_apellidoRepLegal' => 'required',
        'ciRepLegal' => 'required',
        'fecha_nacimientoRepLegal' => 'required',
        ];
    }
    
    public function render()
    {
        return view('livewire.Vigencia.seguro-interior.seguro-interior-edit');
    }

    public function mount(){
        $this->zonas = Zona::all();

        $datosSeguroInterior = SeguroInterior::where('id', $this->idInstConvenio)
            ->with(['residenciaSeguroInterior', 'residenciaSeguroInterior.zonaResidencia', 
                'personaSeguroInterior', 'usuarioSeguroInterior', 'usuarioSeguroInterior.personaUser'])
            ->first();
        
        //DATOS DE LA TABLA SEGURO INTERIOR
        $this->nombre_seguro = $datosSeguroInterior->nombre_seguro;
        $this->telefono = $datosSeguroInterior->telefono;
        $this->fax = $datosSeguroInterior->fax;
        $this->id_usuario_responsable = $datosSeguroInterior->id_usuario_responsable;
        $this->sigla_seguro = $datosSeguroInterior->sigla_seguro;
        $this->nit = $datosSeguroInterior->nit;
        $this->id_residencia = $datosSeguroInterior->id_residencia;
        $this->correo = $datosSeguroInterior->correo;
        $this->nro_empleador = $datosSeguroInterior->nro_empleador;
        $this->id_rep_legal = $datosSeguroInterior->id_rep_legal;

        //DATOS DE LA TABLA RESIDENCIA
        $this->direccion = $datosSeguroInterior->residenciaSeguroInterior->direccion;
        $this->nro_vivienda = $datosSeguroInterior->residenciaSeguroInterior->nro_vivienda;
        $this->latitud = $datosSeguroInterior->residenciaSeguroInterior->latitud;
        $this->longitud = $datosSeguroInterior->residenciaSeguroInterior->longitud;
        $this->id_zona = $datosSeguroInterior->residenciaSeguroInterior->id_zona;        

        //DATOS DE LA TABLA ZONA
        $this->nombreZona = $datosSeguroInterior->residenciaSeguroInterior->zonaResidencia->nombre;

        //DATOS DEL REPRESENTANTE LEGAL
        $this->nombresRepLegal = $datosSeguroInterior->personaSeguroInterior->nombres;
        $this->p_apellidoRepLegal = $datosSeguroInterior->personaSeguroInterior->p_apellido;
        $this->s_apellidoRepLegal = $datosSeguroInterior->personaSeguroInterior->s_apellido;
        $this->fecha_nacimientoRepLegal = $datosSeguroInterior->personaSeguroInterior->fecha_nacimiento;
        $this->ciRepLegal = $datosSeguroInterior->personaSeguroInterior->ci;
        $this->complementoRepLegal = $datosSeguroInterior->personaSeguroInterior->complemento;

        if($datosSeguroInterior->personaSeguroInterior->p_apellido == null){
            $this->nombreCompletoRepLegal = $datosSeguroInterior->personaSeguroInterior->s_apellido. ' ' . $datosSeguroInterior->personaSeguroInterior->nombres;
        } elseif ($datosSeguroInterior->personaSeguroInterior->s_apellido == null){
            $this->nombreCompletoRepLegal = $datosSeguroInterior->personaSeguroInterior->p_apellido. ' ' . $datosSeguroInterior->personaSeguroInterior->nombres;
        } else {
            $this->nombreCompletoRepLegal = $datosSeguroInterior->personaSeguroInterior->p_apellido. ' ' . 
                $datosSeguroInterior->personaSeguroInterior->s_apellido . ' ' . $datosSeguroInterior->personaSeguroInterior->nombres;
        }

        if($datosSeguroInterior->personaSeguroInterior->complemento == null){
            $this->carnetRepLegal = $datosSeguroInterior->personaSeguroInterior->ci;
        } else {
            $this->carnetRepLegal = $datosSeguroInterior->personaSeguroInterior->ci. '-' . $datosSeguroInterior->personaSeguroInterior->complemento;
        }

        //DATOS DEL USUARIO RESPONSABLE

        if($datosSeguroInterior->usuarioSeguroInterior->personaUser->p_apellido == null){
            $this->nombreCompletoResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->s_apellido. ' ' . 
                $datosSeguroInterior->usuarioSeguroInterior->personaUser->nombres;
        } elseif ($datosSeguroInterior->usuarioSeguroInterior->personaUser->s_apellido == null){
            $this->nombreCompletoResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->p_apellido. ' ' . 
                $datosSeguroInterior->usuarioSeguroInterior->personaUser->nombres;
        } else {
            $this->nombreCompletoResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->p_apellido. ' ' . 
                $datosSeguroInterior->usuarioSeguroInterior->personaUser->s_apellido . ' ' . $datosSeguroInterior->usuarioSeguroInterior->personaUser->nombres;
        }

        if($datosSeguroInterior->usuarioSeguroInterior->personaUser->complemento == null){
            $this->carnetResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->ci;
        } else {
            $this->carnetResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->ci. '-' . $datosSeguroInterior->usuarioSeguroInterior->personaUser->complemento;
        }

        $this->usuarioResponsable = $datosSeguroInterior->usuarioSeguroInterior->email;

    }

    public function actualizarSeguroInterior()
    {
        $this->validate(); 
        
        $existeResidencia = Residencia::where('direccion', $this->direccion)
            ->where('nro_vivienda', $this->nro_vivienda)
            ->where('id_zona', $this->id_zona)
            ->first();
        
        if($existeResidencia){
            $this->id_residencia = $existeResidencia->id;
        } else {
            $nuevaResidencia = Residencia::create([
                'direccion'=> $this->direccion,
                'nro_vivienda'=> $this->nro_vivienda,
                'id_zona'=> $this->id_zona,
                'latitud' => $this->latitud,
                'longitud' => $this->longitud,
                'id_user_created' => auth()->user()->id,
            ]);
            $this->id_residencia = $nuevaResidencia->id;
        }
         
        $existePersona = Persona::where('ci', $this->ciRepLegal)
            ->where('complemento', $this->complementoRepLegal)
            ->first();
        
        if($existePersona){
            $this->id_rep_legal = $existePersona->id;
        } else {
            if($this->complementoRepLegal == null){
                $clave_unica = $this->ciRepLegal;
            } else {
                $clave_unica = $this->ciRepLegal.$this->complementoRepLegal;
            }

            $nuevaPersona = Persona::create([
                'nombres' => $this->nombresRepLegal,
                'p_apellido' => $this->p_apellidoRepLegal,
                's_apellido' => $this->s_apellidoRepLegal,
                'ci' => $this->ciRepLegal,
                'complemento' => $this->complementoRepLegal,
                'fecha_nacimiento' => $this->fecha_nacimientoRepLegal,
                'estado_asuss' => $this->estado_asuss,
                'clave_unica' => $clave_unica,
                'id_user_created' => auth()->user()->id,
            ]);
            $this->id_rep_legal = $nuevaPersona->id;
        }

        $anteriorSeguro = SeguroInterior::find($this->idInstConvenio);
        if(($this->telefono != $anteriorSeguro->telefono) || ($this->fax != $anteriorSeguro->fax) || ($this->correo != $anteriorSeguro->correo) ||
            ($this->id_residencia != $anteriorSeguro->id_residencia) || ($this->id_rep_legal != $anteriorSeguro->id_rep_legal)){
            $anteriorSeguro->update([
                'telefono' => $this->telefono,
                'fax' => $this->fax,
                'id_residencia' => $this->id_residencia,
                'id_rep_legal' => $this->id_rep_legal,
                'correo' => $this->correo,
                'id_user_updated' => auth()->user()->id,
            ]);
        }
        $this->dispatch('seguroInteriorModificado');
        return redirect()->route('vigencia.segurosinterior.index');
    }

    public function search_persona()
    {
        try {
            if ($this->complementoBusqueda == '') {
                $this->complementoBusqueda = null;
            }
    
            $existePersona = Persona::where('ci', $this->ciBusqueda)->where('complemento', $this->complementoBusqueda)->first();
    
            if($existePersona){          
                $this->nombresRepLegal = $existePersona->nombres;
                $this->p_apellidoRepLegal = $existePersona->p_apellido;
                $this->s_apellidoRepLegal = $existePersona->s_apellido;
                $this->ciRepLegal = $existePersona->ci;
                $this->complementoRepLegal = $existePersona->complemento;
                $this->fecha_nacimientoRepLegal = $existePersona->fecha_nacimiento;
            } else {
                $respuesta = new ValidarPersona();
                $dato = $respuesta->buscarPersona($this->ciBusqueda, $this->complementoBusqueda);
    
                if (isset($dato['datosPersonaEnFormatoJson'])) {
                    $this->ciRepLegal = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->complementoRepLegal = $dato['datosPersonaEnFormatoJson']['Complemento'];
                    $this->p_apellidoRepLegal = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                    $this->s_apellidoRepLegal = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                    $this->nombresRepLegal = $dato['datosPersonaEnFormatoJson']['nombres'];
                    $this->fecha_nacimientoRepLegal = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                    $this->estado_asuss = true;
                        
                } else {
                    $this->sinDatos = true;
                    $this->ciRepLegal = $this->ciBusqueda;
                    $this->complementoRepLegal = $this->complementoBusqueda;
                    $this->p_apellidoRepLegal = null;
                    $this->s_apellidoRepLegal = null;
                    $this->nombresRepLegal = null;
                    $this->fecha_nacimientoRepLegal = null;
                    $this->fecha_nacimientoRepLegalMostrar = null;
                    $this->estado_asuss = false;
                }
            }
        } catch (\Throwable $th) {
            $this->sinDatos = true;
            $this->ciRepLegal = $this->ciBusqueda;
            $this->complementoRepLegal = $this->complementoBusqueda;
            $this->p_apellidoRepLegal = null;
            $this->s_apellidoRepLegal = null;
            $this->nombresRepLegal = null;
            $this->fecha_nacimientoRepLegal = null;
            $this->fecha_nacimientoRepLegalMostrar = null;
            $this->estado_asuss = false;
        }

        
    }
    
    public function atras(){
        return redirect()->route('vigencia.segurosinterior.index');
    }

    public function cancelar(){
        $this->dispatch('seguroInteriorNoModificado');
        return redirect()->route('vigencia.segurosinterior.index');
    }
}
