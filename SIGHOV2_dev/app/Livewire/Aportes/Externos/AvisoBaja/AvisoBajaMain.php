<?php

namespace App\Livewire\Aportes\Externos\AvisoBaja;

use App\Models\Aportes\AvisoBajaInstitucional;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Administracion\Persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\Administracion\Estado;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;


class AvisoBajaMain extends Component
{
    public $idAviso;
    public $nombre_completo, $ci, $fecha_baja,
        $fecha_solicitud, $motivo_baja, $ultimo_salario,
        $descripcion, $estado_validado, $fecha_validacion, $usuario_valida, $anulado, $matricula_seguro;

    public $fecha_inicio, $fecha_fin;
    public $id_institucion;

    public function render()
    {
        return view('livewire.Aportes.Externos.aviso-baja.aviso-baja-main');
    }
    public function mount()
    {
        // Comunicado ASUSS  a Encargados de Planillas Externos
        // $this->comunicado_asuss();
    }

    public function comunicado_asuss()
    {
        // INICIO DE NOTIFICACION A ENCARGADOS EXTERNOS DE PLANILLAS
        $rol = Role::where('name', 'Encargado Planillas Externas')->first();
        $receptores = RolUsuario::where('role_id', $rol->id)->get();

        if ($rol) {
            $receptores = RolUsuario::where('role_id', $rol->id)->get();
            foreach ($receptores as $receptor) {
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 1,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $receptor->model_id,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'ATENCIÓN - COMUNICADO ASUSS',
                    'mensaje' => 'Se informa que, conforme al Instructivo
                         INS/ASUSS/DGE N° 0014/2025 emitido por la ASUSS, en
                          cumplimiento del Decreto Supremo N° 5383, se debe
                           aplicar el nuevo Salario Mínimo Nacional para el
                            cálculo de aportes al Seguro Social de Corto Plazo 
                            a partir del periodo mayo 2025. Los reintegros deben
                             ser realizados hasta el 31 de julio de 2025. ',
                ]);
            }
        }
        // FIN DE NOTIFICACION 

        // INICIO DE NOTIFICACION A ENCARGADO DE APORTES
        $rol = Role::where('name', 'Encargado Aportes')->first();
        $receptores = RolUsuario::where('role_id', $rol->id)->get();

        if ($rol) {
            $receptores = RolUsuario::where('role_id', $rol->id)->get();
            foreach ($receptores as $receptor) {
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 1,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $receptor->model_id,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'ATENCIÓN - COMUNICADO ASUSS',
                    'mensaje' => 'Se informa que, conforme al Instructivo
                        INS/ASUSS/DGE N° 0014/2025 emitido por la ASUSS, en
                        cumplimiento del Decreto Supremo N° 5383, se debe
                        aplicar el nuevo Salario Mínimo Nacional para el
                        cálculo de aportes al Seguro Social de Corto Plazo 
                        a partir del periodo mayo 2025. Los reintegros deben
                            ser realizados hasta el 31 de julio de 2025. ',
                ]);
            }
        }
        // FIN DE NOTIFICACION 
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
        $id_user_valida = $aviso->id_usuario_valida;

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
        $user_val = User::find($id_user_valida);
        if ($user_val) {
            $this->usuario_valida = $user_val->name;
        }
    }

    #[On('anularRegistro')]
    public function anularRegistro($data)
    {
        $this->idAviso = $data;
        $this->dispatch('anularAvisoBaja');
    }
    #[On('anularAvisoInstitucional')]
    public function anularAvisoInstitucional()
    {
        try {
            $aviso = AvisoBajaInstitucional::find($this->idAviso);
            if ($aviso) {
                $aviso->anulado = true;
                $aviso->estado_valida = null;
                $aviso->id_user_updated = auth()->user()->id;
                $aviso->save();
            }
            $this->dispatch('pg:eventRefresh-AvisoBajaTable');
            $this->dispatch('guardarAnulado');
        } catch (\Throwable $th) {

            $this->dispatch('error');
        }
    }

    #[On('abrirPdfBaja')]
    public function abrirPdfBaja($data)
    {
        $this->idAviso = $data;
        $this->mostrarPdf($data);
        $this->dispatch('modalVerAvisoPdf');
    }

    public $pdf_vista;
    public function mostrarPdf($id)
    {
        try {
            if ($id) {
                $path = 'Aportes/AvisoBajaInstitucional/' . 'AV_' .  $id . '.pdf';

                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function redirigirExcelReporteAvisoBaja()
    {
        // dd('redirigir excel');
        $this->dispatch('abrirModalReporteRecursos');
    }

    protected function rules()
    {
        return [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|after_or_equal:fecha_inicio'
        ];
    }

    #[On('generarReporte')]
    public function generarReporte()
    {
        $this->validate();
        $url = route(
            'aportes.aviso_baja.excel',
            [
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]
        );
        // dd($url);
        $this->js("window.open('{$url}', '_blank');");
    }
}
