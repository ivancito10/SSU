<?php

namespace App\Livewire\Aportes\Externos\AvisoBaja;

use Livewire\Component;
use App\Models\Administracion\Persona;
use Livewire\Attributes\On;
use App\Models\Aportes\AvisoBajaInstitucional;
use App\Models\Aportes\Institucion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use Illuminate\Support\Facades\Log;

class AvisoBajaRegister extends Component
{
    public  $id_institucion, $id_persona, $carnet, $complemento, $existePersona;
    public $nombre_completo, $ci;
    public $fecha_baja, $motivo_baja, $fecha_solicitud, $descripcion, $ultimo_salario, $matricula_seguro;
    public $nombre_insti;

    public function render()
    {
        return view('livewire.Aportes.Externos.aviso-baja.aviso-baja-register');
    }
    public function mount()
    {
        $this->fecha_solicitud = Carbon::now()->format('Y-m-d');
    }
    #[On('registrarBaja')]
    public function registrarBaja($data)
    {
        $institucion_user = Institucion::where('estado_validado', true)
            ->where('id_usuario_aviso_baja', auth()->user()->id)
            ->first();

        $this->nombre_insti = $institucion_user->nombre;

        $this->id_institucion = $institucion_user->id;
        $this->id_persona = $data;
        $this->mostrarPersona($this->id_persona);
        $this->fecha_solicitud = Carbon::now()->format('Y-m-d');
        $this->dispatch('modalAbrirRegistro');
        $this->skipRender();
    }
    public function mostrarPersona($id_per)
    {

        $persona = Persona::find($id_per);
        if ($persona) {
            $this->nombre_completo = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
            $this->ci = $persona->ci . ' ' . $persona->complemento ?? '';
            $this->matricula_seguro = $persona->matricula_seguro;

            $aporte_30_dias = AporteInstitucional::where('id_institucion', $this->id_institucion)
                ->where('id_persona', $id_per)
                ->where('reintegro_incr_salarial', false)
                ->where('beneficio_social', false)
                ->where('planilla_adicional', false)
                ->where('retroactivo', false)
                ->orderByRaw('dias_trabajados = 30 DESC')
                ->orderBy('id', 'desc')
                ->first();

            $this->ultimo_salario = $aporte_30_dias->total_ganado;


            // dd($this->ultimo_salario);
        }
    }



    #[On('guardarAvisoBaja')]
    public function guardarAvisoBaja()
    {
        // dd('sdasd');
        DB::beginTransaction();
        try {
            if ($this->fecha_baja && ($this->motivo_baja != 0) && $this->ultimo_salario && $this->fecha_solicitud) {
                if ($this->id_persona) {
                    $aviso = AvisoBajaInstitucional::create([
                        'id_persona' => $this->id_persona,
                        'fecha_baja' => $this->fecha_baja,
                        'motivo_baja' => $this->motivo_baja,
                        'ultimo_salario' => $this->ultimo_salario,
                        'fecha_solicitud' => $this->fecha_solicitud,
                        'id_usuario_solicitud' => auth()->user()->id,
                        'descripcion' => $this->descripcion,
                        'estado_valida' => false,
                        'id_institucion' => $this->id_institucion,
                        'id_user_created' =>  auth()->user()->id,

                    ]);
                    // dd($aviso);
                    DB::commit();
                    $this->enviarNotificacion('Encargado Aportes');
                    $this->enviarNotificacion('Encargado VVDD');

                    $this->js('window.open("/aportes/externos/pdf_aviso_baja/' . $aviso->id . '", "_blank")');
                    $this->dispatch('guardadoBaja');
                    $this->dispatch('pg:eventRefresh-AvisoBajaTable');
                    $this->reset();
                }
            } else {
                $this->dispatch('faltaDatos');
            }
            $this->skipRender();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }

    private function enviarNotificacion($rolNombre)
    {
        try {
            $rol = Role::where('name', $rolNombre)->first();
            if ($rol) {
                $receptores = RolUsuario::where('role_id', $rol->id)->get();
                foreach ($receptores as $receptor) {
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'AVISO DE BAJA EXTERNO',
                        'mensaje' => 'La institución ' . ($this->nombre_insti ?? 'Nombre de institución no disponible') . ' realizó el registro de baja del asegurado ' . $this->nombre_completo . ' con C.I: ' . $this->ci,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error al enviar notificación a ' . $rolNombre . ': ' . $th->getMessage());
        }
    }
}
