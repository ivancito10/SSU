<?php

namespace App\Livewire\Aportes\Externos\Institucion;

use Livewire\Component;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Aportes\ClaseAportante;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\Role;
use App\Models\Administracion\NotificacionUsuarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InstitucionEditExt extends Component
{
    use WithFileUploads;
    #[Validate]
    public $file_digital_nit;
    public $file_digital_ci;


    #[Validate]
    public $id_institucion;
    public $nombres, $ap_paterno, $ap_materno, $fecha_nacimiento, $ci, $complemento_m, $institucion,
        $nit, $nro_empleador, $clase_aportante, $acronimo;
    #[Validate]
    public  $actividad_empleador, $fecha_afiliacion, $zona,
        $zonaId, $correo, $telefono, $tipo_doc, $expedido_ci, $estado, $publica, $tgn, $latitudRegistered,
        $longitudRegistered, $latitud, $longitud;
    #[Validate]
    public $direccion, $numero, $clase_aportante_id, $tipo_comercio, $tipo_institucion, $direccion_referencia;
    public $telefono_dos;
    public $ubicacion_select = 0;
    public $zonasSelect = [], $claseSelect = [];
    protected $listeners = ['actualizar', 'recibirLonLat', 'cancel'];

    public function render()
    {
        return view('livewire.Aportes.Externos.institucion.institucion-edit-ext');
    }

    public function mount()
    {
        $this->claseSelect = ClaseAportante::orderBy('tipo', 'asc')->get();
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->mostrarInstitucion($this->id_institucion);
    }
    public function rules()
    {
        return [
            'institucion' => 'required',
            'nit' => 'required|numeric',
            'nro_empleador' => 'required',
            'clase_aportante_id' => 'required',
            'fecha_afiliacion' => 'required',
            'acronimo' => 'required',
            'actividad_empleador' => 'required',
            'direccion' => 'required',
            'numero' => 'required',
            'zona' => 'required',
            'telefono' => 'required',
            'publica' => 'required',
            'tipo_doc' => 'required',
            'tipo_comercio' => 'required',
            'direccion_referencia' => 'required',
            'file_digital_nit' => 'required|mimes:pdf|max:1024',
            'correo' => 'required',

        ];
    }
    //FUNCION PARA LO DE LA UBICACION
    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    //Logica para mostrar una sola institucion por ID
    public function mostrarInstitucion($instId)
    {
        if ($instId) {
            $institucion = Institucion::find($instId);


            $residenciaActual = Residencia::find($institucion->id_residencia);
            $this->direccion = $residenciaActual->direccion;
            $this->numero = $residenciaActual->nro_vivienda;
            $this->latitudRegistered = $residenciaActual->latitud;
            $this->longitudRegistered = $residenciaActual->longitud;
            /*  $zonaActual = Zona::find($institucion->id_zona);*/
            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;

            $this->correo = $institucion->correo;
            $this->telefono = $institucion->telefono;
            $this->estado = $institucion->estado;
            $this->tgn = $institucion->tgn;
            $this->publica = $institucion->publica;
            $this->tipo_institucion = $institucion->tipo_empresa_institucion;
            $this->direccion_referencia = $institucion->referencia_direccion;
            $this->telefono_dos = $institucion->telefono_dos;

            $this->institucion = $institucion->nombre;
            $this->acronimo = $institucion->acronimo;
            $this->nro_empleador = $institucion->nro_empleador;
            $this->actividad_empleador = $institucion->actividad_empleador;
            $this->fecha_afiliacion = $institucion->fecha_afiliacion;
            $this->clase_aportante_id = $institucion->id_clase_aportante;
            $this->nit = $institucion->nit;
            $this->tipo_comercio = $institucion->codigo_comercio;

            $tipoAportante = ClaseAportante::find($institucion->id_clase_aportante);
            $this->clase_aportante = $tipoAportante->tipo;


            $representante = Persona::find($institucion->id_persona);
            $this->nombres = $representante->nombres . " " . $representante->p_apellido . " " . $representante->s_apellido;

            if ($representante->complemento != null) {
                $this->ci = $representante->ci . "-" . $representante->complemento;
            } else {
                $this->ci = $representante->ci;
            }

            $this->fecha_nacimiento = Carbon::parse($representante->fecha_nacimiento)->format('d/m/Y') ;

            $es_extranjero = $representante->es_extranjero;
            $id_dept_exp = $representante->id_dept_exp;
            if ($es_extranjero == true) {
                $this->tipo_doc = "EXTRANJERO";
            } else {
                $this->tipo_doc = "NACIONAL";
            }

            $departamento = Departamento::find($id_dept_exp);
            if ($departamento) {
                $this->expedido_ci = $departamento->sigla;
            } else {
                $this->expedido_ci = null;
            }

            return $institucion;
        }
    }


    // actualizar institucion
    public function actualizar()
    {
        $this->validate();

        $institucion = Institucion::find($this->id_institucion);
        if ($this->nit !== $institucion->nit) {
            $this->dispatch('errorNnit');
            $this->validate([
                'file_digital_nit' => 'required|mimes:pdf', // Ejemplo de validación
            ]);
        }
        try {
            if ($institucion) {
                $institucion->nombre = $this->institucion;
                $institucion->nit = $this->nit;
                $institucion->nro_empleador = $this->nro_empleador;
                $institucion->acronimo = $this->acronimo;
                $institucion->actividad_empleador = $this->actividad_empleador;
                $institucion->correo = $this->correo;
                $institucion->telefono = $this->telefono;
                $institucion->tgn = $this->tgn;
                $institucion->tipo_empresa_institucion = $this->tipo_institucion;
                $institucion->publica = $this->publica;
                $institucion->id_clase_aportante = $this->clase_aportante_id;
                $institucion->codigo_comercio = $this->tipo_comercio;
                $institucion->referencia_direccion = $this->direccion_referencia;
                $institucion->telefono_dos = $this->telefono_dos;
                $institucion->fecha_afiliacion = $this->fecha_afiliacion;

                $existeResidencia = Residencia::where('direccion', $this->direccion)
                    ->where('nro_vivienda', $this->numero)
                    ->where('id_zona', $this->zonaId)->first();
                if ($existeResidencia) {
                    $id_residencia = $existeResidencia->id;
                } else {
                    $residenciaNueva = Residencia::create([
                        'direccion' => $this->direccion,
                        'nro_vivienda' => $this->numero,
                        'id_zona' => $this->zonaId,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $id_residencia = $residenciaNueva->id;
                }
                $institucion->update([
                    'id_residencia' => $id_residencia,
                ]);
                $institucion->save();

                if ($this->file_digital_nit) {
                    // guardamos en el FTP NIT
                    $nombre_file_nit = $institucion->id . '_nit' . '.pdf';
                    $this->file_digital_nit->storeAs('Aportes/Institucion/Nit', $nombre_file_nit, 'ftp');
                }
                // INICIO DE NOTIFICACION
                $rol = Role::where('name', 'Encargado Aportes')->first();
                if ($rol) {
                    $receptores = RolUsuario::where('role_id', $rol->id)->get();
                    foreach ($receptores as $receptor) {
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 1,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' => $receptor->model_id,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'APORTES - INSTITUCIÓN ACTUALIZADA',
                            'mensaje' => 'La institución ' . $institucion->nombre . ' fue actualizada.',
                        ]);
                    }
                }
                // FIN DE NOTIFICACION

                $this->dispatch('institucionModificada');
                $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
                return redirect()->route('aportes.institucionesExternos.index');
            }
        } catch (\Throwable $th) {
            $this->dispatch('InstitucionNoActualizado');
        }
    }



    public function cancel()
    {

        // Redirigir a la ruta deseada
        return redirect()->route('aportes.institucionesExternos.index')->with('evento', 'institucionCancelado');
    }
    public function atras()
    {

        return redirect()->route('aportes.institucionesExternos.index');
    }
}
