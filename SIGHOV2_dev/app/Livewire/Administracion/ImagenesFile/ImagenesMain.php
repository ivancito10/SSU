<?php

namespace App\Livewire\Administracion\ImagenesFile;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Administracion\Imagen;
use Illuminate\Support\Facades\Storage;

class ImagenesMain extends Component
{
    public function render()
    {
        return view('livewire.Administracion.imagenes-file.imagenes-main');
    }

    public function registrar_imagen(){ 
        $this->dispatch('modalRegistrarImagen');
    }

    public $id_imagen, $descripcion, $estado_inicial, $nombre_imagen, $estado, $foto;
    #[On('cambiar_estado_imagen')]
    public function  cambiar_estado_imagen($data){
        $imagen = Imagen::where('id', $data)->first();
        $this->id_imagen = $imagen->id;
        $this->estado_inicial = $imagen->estado;

        if($imagen->estado == true){
            $this->descripcion = $imagen->descripcion;
            $this->dispatch('deshabilitar_imagen');

        }elseif($imagen->estado == false){
            $this->dispatch('habilitar_imagen');
        }

    }

    #[On('confirmar_estado')]
    public function guardar_cambios(){ 

        $imagen = Imagen::find($this->id_imagen);
        if ($imagen) {
            $estadoActual = $imagen->estado;
            $imagen->estado = !$estadoActual;
            $imagen->save();
            $this->dispatch('pg:eventRefresh-ImagenesTable');

            if($this->estado_inicial == true){ 
                $this->dispatch('estado_deshabilitado');
            }elseif($this->estado_inicial == false){
                $this->dispatch('estado_habilitado');
            } 
        }
    }
    #[On('ver_imagen')]
    public function ver_imagen($data){
        $imagen = Imagen::find($data);
        $this->nombre_imagen = $imagen->nombre_imagen ?? 'sin dato';
        $this->descripcion = $imagen->descripcion ?? 'sin dato';
        $this->estado = $imagen->estado ?? 0;

        // FTP obtener imagen
        $rutaFTP = 'Administracion/Login/Imagenes/' . $this->nombre_imagen; 
        $contenido = Storage::disk('ftp')->get($rutaFTP); 
        $mime = 'image/' . pathinfo($this->nombre_imagen, PATHINFO_EXTENSION); 
        $this->foto = 'data:' . $mime . ';base64,' . base64_encode($contenido);

        $this->dispatch('ver_modal_imagen');
    }


}
