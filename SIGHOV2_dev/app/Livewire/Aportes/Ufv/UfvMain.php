<?php

namespace App\Livewire\Aportes\Ufv;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Aportes\Ufv;
use Livewire\Attributes\On;
use App\Services\DatosEstudiantes;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Aportes\UfvImport;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Services\Fotos\ImagenService;
use App\Services\Fotos\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use OneSignal;

class UfvMain extends Component
{
    use WithFileUploads;
    public $idUfv;
    public $planilla;
    public $esperandoDatos;
    public $url_imagen_usuario;
    protected $listeners = ['create', 'SubirPlanillaUfv', 'actualizar'];

    public function render()
    {
        // $imagenService = new ImagenService();
        // $userImagenService = new UserService($imagenService);

        // $this->url_imagen_usuario = $userImagenService->url_imagen_usuario(3);
        $ufv = $this->mostrarUfv($this->idUfv);
        return view('livewire.Aportes.Ufv.ufv-main', ['ufv' => $ufv]);
    }

    public $valor, $fecha;
    public function mostrarUfv($id)
    {
        if ($id) {
            $esp = Ufv::find($id);
            $this->valor = $esp->valor;
            $this->fecha = $esp->fecha;

            return $esp;
        }
    }

    // ACCION REGISTRAR MANUALMENTE
    public function registrar()
    {
        $this->dispatch('modalRegistrar');
    }
    public function rules()
    {
        return [
            'valor' => 'required|numeric',
            'fecha' => 'required|date',
            //'planilla' => 'file|mimes:xls,xlsx',
        ];
    }

    public function create()
    {
        $this->validate();
         
        DB::beginTransaction();
        try { 
            $comprobarUfv = Ufv::where('fecha', '=', $this->fecha)
                ->where('estado', '=', true)
                ->first();

            if ($comprobarUfv == null) {
                try {
                    $ufv = Ufv::create([
                        'valor' => $this->valor,
                        'fecha' => $this->fecha,
                        'estado' => 1,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    // // Enviar notificación a todos
                    // try {
                    //    $url = route('aportes.ufv.index');
                    //    OneSignal::sendNotificationToAll(
                    //     "Se registró un nuevo valor UFV.",
                    //     null, 
                    //     [
                    //         "customkey" => "valor_ufv" 
                    //     ],
                    //     [
                    //         [ 
                    //             "id" => "ir-ufv",
                    //             "text" => "Ir",
                    //             "url" => $url
                    //         ]
                    //     ],
                    //     null,
                    //     "Nuevo mensaje SIGHO V2"
                    // );
                    // } catch (\Exception $e) {
                    //     \Log::debug('Error al enviar notificación:', [
                    //         'mensaje' => $e->getMessage()
                    //     ]);
                    // }

                    if ($ufv) {
                        $this->dispatch('ufvCreada');
                        $this->dispatch('pg:eventRefresh-UfvTable');
                    }
                    DB::commit();
                    $this->reset(['valor', 'fecha']);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    $this->reset(['valor', 'fecha']);
                    $this->dispatch('ufvCancelado');
                }
            } else {
                DB::rollBack();
                $this->dispatch('ufvExiste');
                $this->reset(['valor', 'fecha']);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }
    // ACCIONES PARA SUBIR UN EXCEL
    public function subir_excel()
    {
        $this->dispatch('modalSubirExcel');
    }

    public function SubirPlanillaUfv()
    {
        DB::beginTransaction();
        try {
            //dd($this->planilla);
            $this->esperandoDatos = true;
            $file = $this->planilla;
            Excel::import(new UfvImport(), $file);
            $this->dispatch('SubirPlanillaExitoso');

            //Para las notificaciones
            //$receptores = RolUsuario::where('model_id', 27)->first();
            //dd($receptores);
            $rol = Role::where('name', 'Encargado Aportes')->first();
            //dd($rol);
            if ($rol) {
                $receptores = RolUsuario::where('role_id', $rol->id)->get();
                //dd($receptores);
                $ultimoUfv = Ufv::orderBy('id', 'desc')->first();

                foreach ($receptores as $receptor) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'APORTES - UFV ACTUALIZADAS EXITOSAMENTE',
                        'mensaje' =>
                        'La tabla de valores de la UFV fueron registradas existosamente hasta el ' . Carbon::parse($ultimoUfv->fecha)->translatedFormat('d \d\e F \d\e Y'),
                    ]);
                }
            }
            //Fin de las notificaciones

            $this->dispatch('pg:eventRefresh-UfvTable');
            DB::commit();
            $this->esperandoDatos = false;
            $this->skipRender();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('ErrorSubirPlanilla');
        }
    }

    //ACCIONES DEL POWERGRID
    // editarTasa
    #[On('editarUfv')]
    public function editarUfv($data)
    {
        $this->idUfv = $data;
        $this->dispatch('pg:eventRefresh-UfvTable');
        $this->dispatch('modalEditarUfv');
    }

    // Actualizar registro
    public function actualizar()
    {
        DB::beginTransaction();
        try {
            if ($this->idUfv) {

                $esp = Ufv::find($this->idUfv);
                if ($esp) {
                    $esp->valor = $this->valor;
                    $esp->fecha = $this->fecha;
                    $esp->id_user_updated = auth()->user()->id;
                    $esp->save();
                }
                DB::commit();
                $this->dispatch('pg:eventRefresh-UfvTable');
                $this->dispatch('ufvModificado');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }

    // Cambiar estado
    #[On('cambiarEstado')]
    public function cambiarEstado($data)
    {
        try {
            $esp = Ufv::find($data);
            if ($esp) {

                $comprobarUfv = Ufv::where('valor', $esp->valor)
                ->where('fecha', '=', $esp->fecha)
                ->where('estado', '=', true)
                ->whereNot('id', $data)
                ->first();
            
                if(!$comprobarUfv){
                    $estadoActual = $esp->estado;

                    $esp->estado = !$estadoActual;
                    $esp->save();
    
                    $this->dispatch('pg:eventRefresh-UfvTable');
                    if ($esp->estado == true) {
                        $this->dispatch('activo');
                    } else {
                        $this->dispatch('inactivo');
                    }
                } else {
                    $this->dispatch('ufvExiste');
                }                
            }
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }
    
}
