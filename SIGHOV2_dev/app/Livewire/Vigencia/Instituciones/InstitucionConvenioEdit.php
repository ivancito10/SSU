<?php

namespace App\Livewire\Vigencia\Instituciones;

use Livewire\Component;
use App\Models\Vigencia\InstitucionConvenio;
use App\Models\Administracion\Residencia;
use Livewire\Attributes\Validate;
use App\Models\Administracion\Zona;
use Illuminate\Support\Facades\DB;

class InstitucionConvenioEdit extends Component
{
    public $idInstConvenio;

    //DATOS DE LA INSTITUCION DE CONVENIO

    public $nombre_institucion;
    public $sigla;
    #[Validate]
    public $telefono;
    #[Validate]
    public $servicio;
    #[Validate]
    public $descripcion;
    #[Validate]
    public $correo;
    public $id_residencia;
    #[Validate]
    public $direccion;
    #[Validate]
    public $nro_vivienda;
    public $latitud;
    public $longitud;
    #[Validate]
    public $id_zona;
    public $nombreZona;

    public $zonas = [];

    //PARA LA UBICACION
    public $ubicacion_select;

    protected $listeners = ['editarInstitucionConvenio','recibirLonLat'];

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
        return view('livewire.Vigencia.instituciones.institucion-convenio-edit');
    }

    public function mount(){
        $this->zonas = Zona::all();
        $this->mostrarInstitucionConvenio($this->idInstConvenio);
    }

    public function editarInstitucionConvenio()
    {
        //dd($this->latitud,$this->longitud);
        $this->validate();
        $instEditada = InstitucionConvenio::find($this->idInstConvenio);
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
                    'nro_vivienda' => $this->nro_vivienda,
                    'id_zona' => $this->id_zona,
                    'latitud' => $this->latitud,
                    'longitud' => $this->longitud,
                    'id_user_created' => auth()->user()->id,
                ]);
                $residenciaId = $nuevaResidencia->id;
            }

            $instEditada->update([
                'telefono' => $this->telefono,
                'servicio' => $this->servicio,
                'descripcion' => $this->descripcion,
                'correo' => $this->correo,
                'id_residencia' => $residenciaId,
                'id_user_updated' => auth()->user()->id,
            ]);
            if($instEditada){
                DB::commit();
                $this->dispatch('pg:eventRefresh-instConvenioTable');
                $this->dispatch('instiEditada');
                return redirect()->route('vigencia.institucion.index');
            } else {
                DB::rollBack();
                $this->dispatch('instiNoEditada');
            }              
        } catch (\Throwable $th) {
            DB::rollBack();
        }     
    }

    public function atras(){
        return redirect()->route('vigencia.institucion.index');
    }


    public function mostrarInstitucionConvenio($idInstConvenio){
        $instConvenio = InstitucionConvenio::find($idInstConvenio);
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
        $this->latitud = $residencia->latitud;
        $this->longitud = $residencia->longitud;
        $this->id_zona = $residencia->id_zona;
        $zona = Zona::find($residencia->id_zona);
        $this->nombreZona = $zona->nombre;
    }

    public function cancelar(){
        $this->dispatch('instiNoEditada');
        return redirect()->route('vigencia.institucion.index');
        
    }

         //FUNCION PARA LO DE LA UBICACION
         public function recibirLonLat($d_lat, $d_lng)
         {
            //dd($d_lat, $d_lng);
             $this->latitud = $d_lat;
             $this->longitud = $d_lng;
             $this->ubicacion_select = 1;
         }
}
