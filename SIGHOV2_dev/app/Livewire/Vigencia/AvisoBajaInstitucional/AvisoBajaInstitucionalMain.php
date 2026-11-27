<?php

namespace App\Livewire\Vigencia\AvisoBajaInstitucional;

use App\Models\Administracion\Persona;
use App\Models\Aportes\AvisoBajaInstitucional;
use App\Models\Aportes\Institucion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;

class AvisoBajaInstitucionalMain extends Component
{
    public $idAviso;
    public $fecha_baja;
    public $fecha_solicitud;
    public $fecha_validacion;
    public $motivo_baja;
    public $ultimo_salario;
    public $descripcion;
    public $estado_validado;
    public $anulado;
    public $nombre_completo;
    public $ci;
    public $matricula_seguro;
    public $usuario_valida;

    //SEGUIMIENTOS
    public $validadoSwitch;
    public $anuladoSwitch;
    public $nombreValida;
    public $fechaValida;


    public $nombreRegistra;
    public $fechaRegistra;
    public $institucionRegistra;

    public $nombreAnula;
    public $fechaAnula;
    public $institucionAnula;

    public function render()
    {
        return view('livewire.vigencia.aviso-baja-institucional.aviso-baja-institucional-main');
    }


    #[On('abrirRegistro')]
    public function abrirRegistro($data)
    {
        $this->idAviso = $data;

        $this->mostrar_aviso($this->idAviso);
        $this->dispatch('modalVerAvisoBaja');
    }

    public function mostrar_aviso($id)
    {
        $this->validadoSwitch = false;
        $this->anuladoSwitch = false;
        $aviso = AvisoBajaInstitucional::find($id);
        $id_persona = $aviso->id_persona;
        $this->fecha_baja =  Carbon::parse($aviso->fecha_baja)->format('d/m/Y');
        $this->fecha_solicitud = Carbon::parse($aviso->fecha_solicitud)->format('d/m/Y');
        $this->fecha_validacion = Carbon::parse($aviso->fecha_validacion)->format('d/m/Y');
        $this->motivo_baja = $aviso->motivo_baja;
        $this->ultimo_salario = number_format($aviso->ultimo_salario, 2, ',', '.');
        $this->descripcion = $aviso->descripcion;
        $this->estado_validado = $aviso->estado_valida;
        $this->anulado = $aviso->anulado;

        $persona = Persona::find($id_persona);
        if ($persona) {
            $this->nombre_completo = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;

            if ($persona->complemento) {
                $this->ci = $persona->ci . '-' . $persona->complemento;
            } else {
                $this->ci = $persona->ci;
            }
            $this->matricula_seguro = $persona->matricula_seguro;
        }

        $quienRegistra = User::find($aviso->id_user_created);
        $this->nombreRegistra = $quienRegistra->name;
        $this->fechaRegistra = $aviso->fecha_solicitud;
        $institucion = Institucion::find($aviso->id_institucion);
        $this->institucionRegistra = $institucion->nombre;


        if($aviso->anulado === true){
            $this->anuladoSwitch = true;
            $anulacion = User::find($aviso->id_user_updated); 
            $this->nombreAnula = $anulacion->name;
            $this->fechaAnula = $aviso->updated_at;
            $institucionAnulacion = Institucion::find($aviso->id_institucion);
            $this->institucionAnula = $institucionAnulacion->nombre;
        }

        if($aviso->estado_valida === true){
            $this->validadoSwitch = true;
            $validacion = User::find($aviso->id_usuario_valida);
            $this->nombreValida = $validacion->name;
            $this->fechaValida = $aviso->fecha_validacion;
        }


    }

    #[On('validarAviso')]
    public function validarAviso($data){
        $this->idAviso = $data;
        $this->dispatch('validarAvisoBaja');
    }

    #[On('validarAvisoInstitucional')]
    public function validarAvisoInstitucional(){
        try {
            $aviso = AvisoBajaInstitucional::find($this->idAviso);
            if ($aviso) {
                $aviso->estado_valida = true;
                $aviso->fecha_validacion = Carbon::now();
                $aviso->id_usuario_valida = auth()->user()->id;
                $aviso->save();
            }
            $this->dispatch('pg:eventRefresh-AvisoBajaTable');
            $this->dispatch('guardarValidado');
            

        } catch (\Throwable $th) {

            $this->dispatch('error');
        }

    }

    #[On('abrirPdfBaja')]
    public function abrirPdfBaja($data){
        $this->idAviso = $data;
        $this->mostrarPdf($data);
        $this->dispatch('modalVerAvisoPdf');
    }

    public $pdf_vista;
    public function mostrarPdf($id)
    {
        try {
            if($id){
                $path = 'Aportes/AvisoBajaInstitucional/' .'AV_'.  $id . '.pdf';
                
                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
            }
            
        } catch (\Throwable $th) {
            return null;
        }
    }
}
