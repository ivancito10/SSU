<?php

namespace App\Livewire\ArchivoClinico\ListaAgendas;

use App\Models\Administracion\Persona;
use App\Models\Plataforma\Agenda;
use Livewire\Component;

class ListaAgendasMain extends Component
{
    public $matriculaSearch;
    public $aseguradosEncontrados = [];
    public $encontrado=0;
    public $asegurados;
    public $aseguradosLista=0;
    public function render()
    {
        return view('livewire.archivo-clinico.lista-agendas.lista-agendas-main');
    }
    public function asegurado(){
        $asegurados=Persona::with('tipoAseguradoPersona')->where('matricula_seguro','like','%'.$this->matriculaSearch.'%')->get();
        // dd($asegurados);
        if(count($asegurados)===1)
        {
            // dd("asd");
            $this->aseguradosLista=0;
            $id_pesona=$asegurados[0]->id;
            $this->dispatch('PersonaEncontrada');

            // dd($id_pesona);
            $this->buscarAsegurado($id_pesona);

        }
        else{
            $this->encontrado=0;
            $this->asegurados=$asegurados;
            $this->aseguradosLista=1;
            // dd($this->asegurados);
        }
        //  dd(count($asegurados));
    }
    public function buscarAsegurado($id_pesona)
    {
        // dd($id_pesona);
        $this->encontrado=1;
        $this->aseguradosLista=0;
        $this->aseguradosEncontrados = Agenda::with(
            'personaAgenda',
            'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
            'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
            'servicioPlataformaAgenda'


        )
            ->where('anulacion_ficha', 'false')
            ->where('id_persona',$id_pesona)
            ->orderBy('id','desc')
            ->limit(15)
            ->get(); 
          

            if(count($this->aseguradosEncontrados)==0){
                // dd("no hay");
                $this->dispatch('noSeEncontro');
                $this->aseguradosLista=0;
                $this->reset('encontrado');
                return;
            }
            else
            {
                
                $this->dispatch('AgendasEncontradas');
                return;


            }
        // dd($this->matriculaSearch, $this->aseguradosEncontrados[0],$this->aseguradosEncontrados[0]->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad);
    }
}
