<?php

namespace App\Livewire\Vigencia\Instituciones;

use Livewire\Component;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Residencia;
use App\Models\Vigencia\InstitucionConvenio;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use DB;

class InstitucionConvenioRegister extends Component
{
    public $zonas;

    //DATOS DE INSTITUCION DE CONVENIO
    #[Validate]
    public $nombre_institucion;
    #[Validate]
    public $sigla;
    #[Validate]
    public $telefono;
    #[Validate]
    public $correo;
    #[Validate]
    public $servicio;
    #[Validate]
    public $descripcion;
    public $id_residencia;
    #[Validate]
    public $direccion;
    #[Validate]
    public $id_zona;
    public $zonaNombre;
    #[Validate]
    public $nro_vivienda;

    protected $listeners = ['guardarInstitucionConvenio', 'recibirLonLat'];

    
    //VARIABLES PARA LA UBICACION
    public $ubicacion_select;
    public $latitud;
    public $longitud;

    public function rules()
    {
        return [
        'telefono'=>'required|min:8' ,
        'nombre_institucion' => 'required|min:5',
        'sigla' => 'required|min:1',
        'servicio' => 'required|min:1',
        'direccion' => 'required|min:6',
        'descripcion'=>'required|min:6',
        'id_zona'=>'required',
        //'nro_vivienda' => 'required|min:2',
        'correo' => 'required|email',
       
        ];
    }

    
    public function render()
    {
        return view('livewire.Vigencia.instituciones.institucion-convenio-register');
    }

    public function mount(){
        $this->zonas = Zona::orderBy('nombre', 'asc')->get();
    }

    public function atras(){
        return redirect()->route('vigencia.institucion.index');
    }

    public function guardarInstitucionConvenio(){
      $this->validate();
       
        DB::beginTransaction();
        try {
            $existeResidencia = Residencia::where('direccion', $this->direccion)
                ->where('nro_vivienda', $this->nro_vivienda)
                ->where('id_zona', $this->id_zona)
                ->first();
        
            if($existeResidencia){
                $residenciaId = $existeResidencia->id;
            } else {
                $nuevaResidencia = Residencia::create([
                    'direccion' => $this->direccion,
                    'nro_vivienda'=> $this->nro_vivienda,
                    'id_zona'=> $this->id_zona,
                    'latitud' => $this->latitud,
                    'longitud' => $this->longitud,
                    'id_user_created' => auth()->user()->id,
                ]);
                $residenciaId = $nuevaResidencia->id;
            }

            $existeInstitucionConvenio = InstitucionConvenio::where('nombre_institucion', $this->nombre_institucion)
                ->where('estado', true)
                ->first();

            if(!$existeInstitucionConvenio){
                $nuevaInstitucionConvenio = InstitucionConvenio::create([
                    'nombre_institucion' => $this->nombre_institucion,
                    'sigla' => $this->sigla,
                    'telefono' => $this->telefono,
                    'servicio' => $this->servicio,
                    'descripcion' => $this->descripcion,
                    'correo' => $this->correo,
                    'id_residencia' => $residenciaId,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);
                if($nuevaInstitucionConvenio){
                    DB::commit();
                    $this->dispatch('pg:eventRefresh-instConvenioTable');
                    $this->dispatch('institucionCreada');
                    return redirect()->route('vigencia.institucion.index');
                } else {
                    DB::rollBack();
                    $this->dispatch('institucionNoCreada');
                }
            } else {
                DB::rollBack();
                $this->dispatch('institucionNoCreada');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('institucionNoCreada');
        }  
    }

    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    public function cancelar(){
        $this->dispatch('institucionNoCreada');
        return redirect()->route('vigencia.institucion.index');
        
    }
}
