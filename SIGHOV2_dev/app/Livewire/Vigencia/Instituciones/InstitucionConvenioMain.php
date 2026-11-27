<?php

namespace App\Livewire\Vigencia\Instituciones;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Vigencia\InstitucionConvenio;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;

class InstitucionConvenioMain extends Component
{
    public $institucionId;
    
    //DATOS PARA MOSTRAR

    public $nombre_institucion;
    public $sigla;
    public $telefono;
    public $servicio;
    public $descripcion;
    public $correo;
    public $id_residencia;
    public $direccion;
    public $nro_vivienda;
    public $latitud;
    public $longitud;
    public $id_zona;
    public $nombreZona;  

    public $zonas = [];
    public $ubicacion_select;

    public $d_latitud;
    public $d_longitud;


    public function render()
    {
        
        return view('livewire.Vigencia.instituciones.institucion-convenio-main');
    }

    public function redirectToRegistro()
    {
        return redirect()->route('vigencia.institucion.create');
    }

    public function mostrarInstitucion($idInst){
        $instConvenio = InstitucionConvenio::find($idInst);
        $this->nombre_institucion = $instConvenio->nombre_institucion;
        $this->sigla = $instConvenio->sigla;
        $this->telefono = $instConvenio->telefono;
        $this->servicio = $instConvenio->servicio;
        $this->descripcion = $instConvenio->descripcion;
        $this->correo = $instConvenio->correo;
        $this->id_residencia = $instConvenio->id_residencia;
        $residencia = Residencia::find($instConvenio->id_residencia);
        $this->direccion = $residencia->direccion;
        $this->nro_vivienda = $residencia->nro_vivienda;
        $this->d_latitud = $residencia->latitud;
        $this->d_longitud = $residencia->longitud;
        $this->id_zona = $residencia->id_zona;
        $zona = Zona::find($residencia->id_zona);
        $this->nombreZona = $zona->nombre;
    }

    #[On('verInstitucion')]
    public function verInstitucion($data){
        $this->institucionId = $data;
        $this->zonas = Zona::all();
        $this->mostrarInstitucion($data);
        $this->dispatch('modalVerInstitucionConvenio');
    }

    #[On('cambiarEstado')]
    public function cambiarEstado($data)
    {
        try{
            $esp = InstitucionConvenio::find($data);
            if ($esp) {
                $estadoActual = $esp->estado;

                $esp->estado = !$estadoActual;
                $esp->id_user_updated = auth()->user()->id;
                $esp->save();

                $this->dispatch('pg:eventRefresh-instConvenioTable');
                if ($esp->estado == true) {
                    $this->dispatch('activo');
                } else {
                    $this->dispatch('inactivo');
                }
            }

        
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
        
    }

    public function AbrirModalUbicacionInstitucionConvenio()
    {

   
        // $this->d_latitud=$this->latitud;
        // $this->d_longitud=$this->longitud;
        //  $this->dispatch('inputUpdated', [$this->d_latitud, $this->d_longitud]);
        $this->dispatch('ModalAbrirUbicacionInstitucionConvenio');
        $this->skipRender();
    }
}
