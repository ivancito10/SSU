<?php

namespace App\Livewire\Vigencia\SeguroInterior;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Persona;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use App\Models\Migracion\ConveniosInterior;
use App\Models\User;
use DB;
class SeguroInteriorMain extends Component
{
    public $idSeguroInterior;

    public $nombre_seguro;
    public $telefono;
    public $fax;
    public $id_usuario_responsable;
    public $sigla_seguro;
    public $nit;
    public $id_residencia;
    public $id_zona;
    public $nombreZona;
    public $zonas = [];
    public $municipio;
    public $provincia;
    public $departamento;
    public $direccion;
    public $nro_vivienda;
    public $correo;
    public $nro_empleador;
    public $id_rep_legal;
    public $latitud;
    public $longitud;

    public $nombreCompletoRepLegal;
    public $nombresRepLegal;
    public $p_apellidoRepLegal;
    public $s_apellidoRepLegal;
    public $carnetRepLegal;
    public $ciRepLegal;
    public $complementoRepLegal;
    public $fecha_nacimientoRepLegal;
    
    public $nombreCompletoResponsable;
    public $nombresResponsable;
    public $p_apellidoResponsable;
    public $s_apellidoResponsable;
    public $ciResponsable;
    public $complementoResponsable;
    public $carnetResponsable;
    public $usuarioResponsable;

    public $ciUsuarioBusqueda;
    public $complementoUsuarioBusqueda;
    public $idUsuarioSeleccionado;

    public $d_latitud, $d_longitud;

    protected $listeners = ['asignarUsuarioSeguroInterior'];

    public function render()
    {
        
        return view('livewire.Vigencia.seguro-interior.seguro-interior-main');
    }

    #[On('asignar_usuario_responsable')]
    public function asignar_usuario($rowId)
    {
        $this->obtenerDatosSeguro($rowId);
        $this->idSeguroInterior = $rowId;
        $this->dispatch('modalAsignarUsuarioResponsable');
    }

    
    public function obtenerDatosSeguro($idSeguro)
    {
        $datosSeguroInterior = SeguroInterior::where('id', $idSeguro)
        ->with(['residenciaSeguroInterior', 'residenciaSeguroInterior.zonaResidencia', 'residenciaSeguroInterior.zonaResidencia.municipioZona', 
            'residenciaSeguroInterior.zonaResidencia.municipioZona.provinciaMunicipio', 
            'residenciaSeguroInterior.zonaResidencia.municipioZona.provinciaMunicipio.departamentoProvincia',
            'personaSeguroInterior', 'usuarioSeguroInterior', 'usuarioSeguroInterior.personaUser'])
        ->first();
        //dd($datosSeguroInterior);
    
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
        $this->id_zona = $datosSeguroInterior->residenciaSeguroInterior->id_zona;  
        $this->d_latitud = $datosSeguroInterior->residenciaSeguroInterior->latitud;
        $this->d_longitud = $datosSeguroInterior->residenciaSeguroInterior->longitud;      

        //DATOS DE LA TABLA ZONA
        $this->nombreZona = $datosSeguroInterior->residenciaSeguroInterior->zonaResidencia->nombre;
        $this->municipio = $datosSeguroInterior->residenciaSeguroInterior->zonaResidencia->municipioZona->nombre;
        $this->provincia = $datosSeguroInterior->residenciaSeguroInterior->zonaResidencia->municipioZona->provinciaMunicipio->nombre;
        $this->departamento = $datosSeguroInterior->residenciaSeguroInterior->zonaResidencia->municipioZona->provinciaMunicipio->departamentoProvincia->nombre;

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
        $this->nombresResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->nombres;
        $this->p_apellidoResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->p_apellido;
        $this->s_apellidoResponsable = $datosSeguroInterior->usuarioSeguroInterior->personaUser->s_apellido;

        $this->usuarioResponsable = $datosSeguroInterior->usuarioSeguroInterior->email;
    }

    public function searchPersonaUsuario()
    {
        if($this->complementoUsuarioBusqueda == ''){
            $this->complementoUsuarioBusqueda = null;
        }
        try {
            $buscarUsuario = User::with(['personaUser'])
                ->whereHas('personaUser', function($personaUsuario) {
                    $personaUsuario->where('ci', $this->ciUsuarioBusqueda)
                        ->where('complemento', $this->complementoUsuarioBusqueda);
                })
                ->where('estado', true)
                ->first();

            if($buscarUsuario){
                $this->idUsuarioSeleccionado = $buscarUsuario->id;
                $this->usuarioResponsable = $buscarUsuario->email;
            
                if($buscarUsuario->personaUser->complemento == null){
                    $this->carnetResponsable = $buscarUsuario->personaUser->ci;
                } else {
                    $this->carnetResponsable = $buscarUsuario->personaUser->ci . ' - ' . $buscarUsuario->personaUser->complemento;
                }
        
                if($buscarUsuario->personaUser->p_apellido == null){
                    $this->nombreCompletoResponsable = $buscarUsuario->personaUser->s_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                } elseif ($buscarUsuario->personaUser->s_apellido == null){
                    $this->nombreCompletoResponsable = $buscarUsuario->personaUser->p_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                } else {
                    $this->nombreCompletoResponsable = $buscarUsuario->personaUser->p_apellido . ' ' . $buscarUsuario->personaUser->s_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                }
            } else {
                $this->usuarioResponsable = '';
                $this->carnetResponsable = '';
                $this->nombreCompletoResponsable = 'NO SE ENCONTRARON COINCIDENCIAS';
                $this->dispatch('noExisteElUsuario');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noExisteElUsuario');
        }
        

        $this->skipRender();
    }

    public function asignarUsuarioSeguroInterior()
    {
        DB::beginTransaction();
        try {
            if($this->idUsuarioSeleccionado){
                $asignar = SeguroInterior::find($this->idSeguroInterior);
                $asignar->update([
                    'id_usuario_responsable' => $this->idUsuarioSeleccionado,
                    'id_user_updated' => auth()->user()->id,
                ]);
    
                DB::commit();
                $this->ciUsuarioBusqueda = '';
                $this->complementoUsuarioBusqueda = '';
                $this->dispatch('asignacionDeUsuarioCorrecta');
            } else {
                DB::rollBack();
                $this->ciUsuarioBusqueda = '';
                $this->complementoUsuarioBusqueda = '';
                $this->dispatch('noExisteElUsuario');
            }            
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->ciUsuarioBusqueda = '';
            $this->complementoUsuarioBusqueda = '';
            $this->dispatch('asignacionDeUsuarioError');
        }
    }

    #[On('ver_seguro_interior')]
    public function ver_seguro_interior($rowId)
    {
        $this->obtenerDatosSeguro($rowId);
        // dd($this->latitud, $this->latitud);
        $this->idSeguroInterior = $rowId;
        $this->dispatch('modalVerSeguroInterior');
       // $this->skipRender();
    }

    public function AbrirModalUbicacionSeguroInterior()
    {
        //  dd($this->latitud, $this->latitud,$this->d_latitud, $this->d_longitud);
        $this->obtenerDatosSeguro( $this->idSeguroInterior);
         $this->d_latitud=$this->d_latitud;
         $this->d_longitud=$this->d_latitud;
       $this->dispatch('inputUpdated', [$this->d_latitud, $this->d_longitud]);
        $this->dispatch('ModalAbrirUbicacionSeguroInt');
        $this->skipRender();
    }
}
