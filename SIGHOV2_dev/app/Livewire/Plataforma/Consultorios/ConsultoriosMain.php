<?php

namespace App\Livewire\Plataforma\Consultorios;

use Livewire\Component;
use App\Models\Plataforma\Sedes;
use App\Models\Plataforma\Consultorio;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use App\Models\Administracion\Persona;
use App\Models\User;
use App\Services\PoblacionAsegurada;
use Illuminate\Support\Facades\Log;

class ConsultoriosMain extends Component
{
    public $ubicaciones;
    public $ubicaciones_pisos = [];
    public $piso_de;
    public $pisos = [], $piso_actual, $id_piso_actual, $direccion, $id_consultorio;

    public $nro_consultorio, $estado, $id_sedes, $ubicacion, $piso;

    protected $listeners = ['guardarEditarConsultorio'];

    public function formularioRegistro()
    {
        $this->dispatch('ModalRegisterConsultorio');
    }

    public function render()
    {
       
        // $dato=new PoblacionAsegurada();
        // $grupo_familiar=$dato->encontrarUsuario(5);
        // dd($grupo_familiar);

        // $personas=Persona::all();
        //    $nombre_cache="DatosPersonas";
        //     if (Cache::has($nombre_cache)) {
        //           //dd("hay");
        //         $personas_datos=Cache::get($nombre_cache);

        //     }
        //     else{
        //          //dd("no hay");
        //          sleep(5);
        //         $personas_datos=User::where('id','>',1)->where('id','<','40000')->get();

        //         Cache::put($nombre_cache,$personas_datos,20);
        //     }
        //         //dd(Cache::has('DatosPersonas'));
        //     // $value = Cache::get('DatosPersonas', function () {
        //     //     Cache::put('DatosPersonas',$personas_datos,20);
        //     //     return $personas_datos=Persona::all();
        //     // });

        //     $user = $personas_datos->where('name', 'like','%YURI%');

        //      dd($user);
        return view('livewire.Plataforma.consultorios.consultorios-main');
    }


    #[On('cerrar-modal')]
    public function cerrar()
    {
        $this->dispatch('ModalRegisterConsultorioCerrar');
    }

    #[On('registro-correcto')]
    public function guardado()
    {
        $this->dispatch('pg:eventRefresh-ConsultoriosTable');
        $this->dispatch('RegistroCorrecto');
    }
    #[On('registro-incorrecto')]
    public function cancelado()
    {

        $this->dispatch('RegistroIncorrecto');
    }

    #[On('verConsultorio')]
    public function verConsultorio($data)
    {
        $this->id_consultorio = $data;
        $this->datosConsultorio($data);
        $this->dispatch('ModalVerConsultorio');
    }

    public function datosConsultorio($idConsultorio)
    {
        $consultorio = Consultorio::where('id', $idConsultorio)
            ->with(['consultorioSedes'])
            ->first();

        $this->ubicacion = $consultorio->consultorioSedes->ubicacion;
        $this->piso = $consultorio->consultorioSedes->piso;
        $this->nro_consultorio = $consultorio->numero_consultorio;
        $this->id_sedes = $consultorio->id_sedes;
        $this->estado = $consultorio->estado;
    }

    public function guardarEditarConsultorio()
    {
        try {

        if ($this->nro_consultorio != null || $this->nro_consultorio != '') {
            $existeConsultorio = Consultorio::where('numero_consultorio', $this->nro_consultorio)
                ->where('id_sedes', $this->id_sedes)
                ->where('estado', true)
                ->first();

            if (!$existeConsultorio) {
                $consultorio = Consultorio::find($this->id_consultorio);
                if ($consultorio) {
                    $consultorio->numero_consultorio = $this->nro_consultorio;
                    $consultorio->id_sedes = $this->id_sedes;
                    $consultorio->id_user_updated = auth()->user()->id;
                    $consultorio->save();
                    $this->dispatch('EdicionCorrectamente');
                    $this->dispatch('pg:eventRefresh-ConsultoriosTable');
                }
            } else {
                $this->dispatch('consultorioYaExiste');
            }
        } else {
            $this->dispatch('datosIncompletos');
        }

        } catch (\Throwable $e) {
           $this->dispatch('EdicionIncorrectamente');

           $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

           Log::error("Error al guardar editar consultorio", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    #[On('editarConsultorio')]
    public function editarConsultorio($data)
    {
        $this->id_consultorio = $data;
        $this->datosConsultorio($data);
        $this->pisos = Sedes::where('ubicacion', $this->ubicacion)->get();
        $this->dispatch('ModalEditarConsultorio');
    }

    #[On('cambiarEstado')]
    public function cambiarEstado($data)
    {
        $this->id_consultorio = $data;
        $consultorio = Consultorio::find($this->id_consultorio);
        if ($consultorio) {
            $this->estado = $consultorio->estado;
        }
        $this->dispatch('confirmarCambioEstado');
    }

    #[On('actualizarEstado')]
    public function actualizarEstado($confirmado = null)
    {
        if ($confirmado) {
            $consultorio = Consultorio::find($this->id_consultorio);
            if ($consultorio) {
                $estadoActual = $consultorio->estado;
                $consultorio->estado = !$estadoActual;
                $consultorio->save();
                $this->dispatch('pg:eventRefresh-ConsultoriosTable');
            }
        }
    }

    // #[On('habilitarConsultorio')]
    // public function habilitarConsultorio($data)
    // {
    //     try {
    //         $consultorio = Consultorio::find($data);
    //         if ($consultorio) {
    //             $consultorio->estado = 1;
    //             $consultorio->id_user_updated = auth()->user()->id;
    //             $consultorio->save();
    //             $this->dispatch('ConsultorioHabilitado');
    //             $this->dispatch('pg:eventRefresh-ConsultoriosTable');
    //         }
    //     } catch (\Throwable $th) {
    //     }
    // }

    // #[On('deshabilitarConsultorio')]
    // public function deshabilitarConsultorio($data)
    // {
    //     try {
    //         $consultorio = Consultorio::find($data);

    //         if ($consultorio) {
    //             $consultorio->estado = 0;
    //             $consultorio->id_user_updated = auth()->user()->id;
    //             $consultorio->save();
    //             $this->dispatch('ConsultorioInhabilitado');
    //             $this->dispatch('pg:eventRefresh-ConsultoriosTable');
    //         }
    //     } catch (\Throwable $th) {
    //     }
    // }
}
