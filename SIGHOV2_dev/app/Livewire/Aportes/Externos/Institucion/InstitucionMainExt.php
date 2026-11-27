<?php

namespace App\Livewire\Aportes\Externos\Institucion;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Aportes\ClaseAportante;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\NotificacionUsuarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class InstitucionMainExt extends Component
{
    use WithFileUploads;
    #[Validate]
    public $file_digital_ci;
    public $instId;
    public $modal;
    public $institucion;
    public $institucionId;

    public $nombres;
    public $p_apellido;
    public $s_apellido;
    public $ci;
    public $fecha_nacimiento;
    public $complemento;

    public $complemento_b;
    public $ci_b;

    public $direccion;
    public $numero;
    public $zona;
    public $zonaId;
    public $correo;
    public $telefono;
    public $estado;
    public $tgn;
    public $publica;

    public $nombre;
    public $acronimo;
    public $num_empleador;
    public $nit;
    public $tipoclaseaportante;
    public $id_inst, $actividad_empleador, $tipo_comercio,
        $fecha_afiliacion, $tipo_institucion, $direccion_referencia, $telefono_dos,
        $fecha_nacimiento_m;

    public $latitud;
    public $longitud;
    public $latitudRegistered;
    public $longitudRegistered;
    public $ubicacion_select = 0;
    public  $expedido_ci, $tipo_doc, $ci_anterior;
    public $ubicacionTexto = 'NO SELECCIONADO';

    protected $listeners = ['actualizar', 'recibirLonLat'];


    public function redirectToForm()
    {
        return redirect()->route('aportes.institucionesExternos.create');
    }
    public function render()
    {
        $zonasSelect = Zona::all();

        return view('livewire.Aportes.Externos.institucion.institucion-main-ext', ['zonasSelect' => $zonasSelect]);
    }
    public $departamento;
    public function mount()
    {
        $this->departamento = Departamento::get();
    }
    public function rules()
    {
        return [
            'nombre' => 'required',
            'ci' => 'required|numeric',
            'fecha_nacimiento' => 'required',
            'tipo_doc' => 'required',

        ];
    }

    #[On('vistaExt')]
    public function verInstitucion($datos)
    {
        $this->instId = $datos['institucionId'];
        $this->modal = $datos['modalVerExt'];
        $this->dispatch('openModalVerExt');
    }



    #[On('editandoExt')]
    public function ActualizarInstitucion($datos)
    {
        $this->instId = $datos['institucionId'];
        $this->modal = $datos['modalEditarExt'];
        $this->dispatch('openModalEditExt');
    }

    #[On('editandoRepExt')]
    public function ActualizarRepInstitucion($datos)
    {
        $this->instId = $datos['institucionId'];
        $this->modal = $datos['modalEditarRepExt'];
        $this->dispatch('openModalEditRepExt');
    }

    #[On('BuscarPersonaRep')]
    public function NuevoRep()
    {
        $this->tipo_doc = null;
        $this->expedido_ci = null;
        if ($this->complemento_b == '') {
            $this->complemento_b = null;
        }
        $existePersona = Persona::where('ci', $this->ci_b)
            ->where('complemento', $this->complemento_b)
            ->orderBy('id', 'desc')
            ->first();

        if ($existePersona != null) {
            $this->ci = $existePersona->ci;
            $this->complemento = $existePersona->complemento;
            $this->p_apellido = $existePersona->p_apellido;
            $this->s_apellido = $existePersona->s_apellido;
            $this->nombres = $existePersona->nombres;
            $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
            $this->expedido_ci = $existePersona->id_dept_exp;
            $tipo_doc = $existePersona->es_extranjero;
            if ($tipo_doc == true) {
                $this->tipo_doc = 1;
            } else {
                $this->tipo_doc = 0;
            }
            //$this->fecha_nacimiento = Carbon::parse($existePersona->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y'); //Probar esto
            $this->estado_asuss = $existePersona->estado_asuss;
            $this->estado_resp = 1;
            $estadoBusqueda = 2;
        } else {
            $respuesta = new ValidarPersona();
            $dato = $respuesta->buscarPersona($this->ci_b, $this->complemento_b);
            if (isset($dato['datosPersonaEnFormatoJson'])) {

                $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
                $this->p_apellido = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                $this->s_apellido = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                $this->nombres = $dato['datosPersonaEnFormatoJson']['nombres'];
                $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                $this->estado_resp = 1;
            } else {
                $this->estado_resp = 0;
                $this->nombres = '';
                $this->p_apellido = '';
                $this->s_apellido = '';
                $this->fecha_nacimiento = '';
                $this->ci = $this->ci_b;
                $this->complemento = $this->complemento_b;
            }
        }



        $this->skipRender();
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


            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;

            $this->correo = $institucion->correo;
            $this->telefono = $institucion->telefono;
            $this->estado = $institucion->estado;
            $this->tgn = $institucion->tgn;
            $this->publica = $institucion->publica;

            $this->nombre = $institucion->nombre;
            $this->acronimo = $institucion->acronimo;
            $this->num_empleador = $institucion->nro_empleador;
            $this->nit = $institucion->nit;
            $this->actividad_empleador = $institucion->actividad_empleador;
            $this->tipo_comercio = $institucion->codigo_comercio;
            $this->fecha_afiliacion = Carbon::parse($institucion->fecha_afiliacion)->format('d/m/Y');

            $this->tipo_institucion = $institucion->tipo_empresa_institucion;
            $this->direccion_referencia = $institucion->referencia_direccion;
            $this->telefono_dos = $institucion->telefono_dos;

            $tipoAportante = ClaseAportante::find($institucion->id_clase_aportante);
            $this->tipoclaseaportante = $tipoAportante->tipo;
            $this->id_inst = $institucion->id;

            $representante = Persona::find($institucion->id_persona);
            $this->nombres = $representante->nombres;
            $this->p_apellido = $representante->p_apellido;
            $this->s_apellido = $representante->s_apellido;
            $this->ci = $representante->ci;
            $this->ci_anterior = $representante->ci;
            $this->fecha_nacimiento = $representante->fecha_nacimiento;
            $this->fecha_nacimiento_m = Carbon::parse($representante->fecha_nacimiento)->format('d/m/Y');
            $this->complemento = $representante->complemento;
            $this->expedido_ci = $representante->id_dept_exp;
            $tipo_doc = $representante->es_extranjero;
            if ($tipo_doc == true) {
                $this->tipo_doc = 1;
            } else {
                $this->tipo_doc = 0;
            }


            return $institucion;
        }
    }
    #[On('GuardarPerRep')]
    public function editar_rep()
    {
        DB::beginTransaction();
        try {

            $persona = Persona::where('ci', '=', $this->ci)->first();

            if ($this->ci_anterior != $this->ci) {
                $this->validate([
                    'file_digital_ci' => 'required|mimes:pdf', // Ejemplo de validación
                ]);
            }
            if ($persona == null) {
                if ($this->complemento == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento;
                }

                $persona = Persona::create([
                    'p_apellido' => $this->p_apellido,
                    's_apellido' => $this->s_apellido,
                    'nombres' => $this->nombres,
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'es_extranjero' => $this->tipo_doc,
                    'id_dept_exp' => $this->expedido_ci,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);
            }
            $edit_rep = Persona::find($persona->id);
            $institucion = Institucion::find($this->instId);
            if ($institucion) {
                $institucion->id_persona = $edit_rep->id;

                if ($this->file_digital_ci) {
                    //guardamos en el FT CI
                    $nombre_file_ci = $institucion->id . '_ci' . '.pdf';
                    $this->file_digital_ci->storeAs('Aportes/Institucion/RepresentanteLegal', $nombre_file_ci, 'ftp');
                }
                $institucion->save();

                // para envio de notificaciones
                $rol = Role::where('name', 'Encargado Aportes')->first();
                //dd($rol);
                $receptores = RolUsuario::where('role_id', $rol->id)->get();

                foreach ($receptores as $receptor) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id, // para que sea a los encargados de aportes
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'APORTES - EDICIÓN DE REPRESENTANTE LEGAL',
                        'mensaje' => ' Se editó el representante legal de la institución' . $institucion->nombre,
                    ]);
                }

                $this->dispatch('RepresenanteModificada');
                $this->dispatch('pg:eventRefresh-InstitucionesRegistroExterno');
            }

            DB::commit();

            $this->modal = false;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('RepresenanteModificadaError');
            $this->dispatch('pg:eventRefresh-InstitucionesRegistroExterno');
        }
    }
    // para la ubicacion
    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitudRegistered = $d_lat;
        $this->longitudRegistered = $d_lng;
        $this->ubicacion_select = 1;
        $this->ubicacionTexto = 'SELECCIONADO';
    }
    //#[On('actualizar')] 
    public function actualizar()
    {
        $this->validate([
            'correo' => 'email|required',
        ]);
        DB::beginTransaction();
        try {
            $institucion = Institucion::find($this->id_inst);
            if ($institucion) {
                $institucion->correo = $this->correo;
                $institucion->telefono = $this->telefono;
                $institucion->estado = $this->estado;
                $institucion->tgn = $this->tgn;

                $residenciaId = $institucion->id_residencia;
                $nueva_residencia = Residencia::find($residenciaId);
                if ($nueva_residencia) {
                    $nueva_residencia->direccion = $this->direccion;
                    $nueva_residencia->nro_vivienda = $this->numero;
                    $nueva_residencia->id_zona = $this->zonaId;
                    $nueva_residencia->latitud = $this->latitudRegistered;
                    $nueva_residencia->longitud = $this->longitudRegistered;
                    $nueva_residencia->id_user_updated = auth()->user()->id;

                    $nueva_residencia->save();
                }

                $institucion->save();

                // para envio de notificaciones
                $rol = Role::where('name', 'ENCARGADO APORTES')->first();
                $receptores = RolUsuario::where('role_id', $rol->id)->get();
                foreach ($receptores as $receptor) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id, // para que sea a los encargados de aportes
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'APORTES - EDICIÓN DE INSTITUCIÓN',
                        'mensaje' => ' Se editaron datos de la insitucion' . $institucion->nombre,

                    ]);
                }
                DB::commit();
                $this->dispatch('institucionModificada');
                $this->dispatch('pg:eventRefresh-InstitucionesRegistroExterno');
            }
            $this->modal = false;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->modal = false;
            $this->dispatch('InstitucionNoActualizado');
        }
    }

    // abrirInstitucion
    #[On('abrirInstitucionExt')]
    public function abrirInstitucionExt($data)
    {
        $this->instId = $data;
        $this->mostrarInstitucion($this->instId);

        $this->mostrar_pdf_nit($this->instId);
        $this->mostrar_pdf_ci($this->instId);

        $this->dispatch('modalAbrirInstitucionExt');
    }
    // editarInstitucionExt
    #[On('editarInstitucionExt')]
    public function editarInstitucionExt($data)
    {
        $this->instId = $data;
        $this->mostrarInstitucion($this->instId);
        $this->dispatch('modalEditarInsitucionExt');
    }

    // editarRepresentanteExt
    #[On('editarRepresentanteExt')]
    public function editarRepresentanteExt($data)
    {
        $this->instId = $data;
        $this->mostrarInstitucion($this->instId);
        $this->dispatch('modalEditarRepresentanteExt');
    }

    public function abrir_carnet()
    {
        $this->dispatch('modal_ver_carnet_ext');

        $this->skipRender();
    }

    public function abrir_nit()
    {
        $this->dispatch('modal_ver_nit_ext');

        $this->skipRender();
    }
    public $pdf_vista_ci;

    public function mostrar_pdf_ci($id)
    {

        if ($id) {

            $path = 'Aportes/Institucion/RepresentanteLegal/' . $id . '_ci' . '.pdf';

            $fileContents = Storage::disk('ftp')->get($path);
            $pdfBase64 = base64_encode($fileContents);
            $this->pdf_vista_ci = $pdfBase64;
        }
    }

    public $pdf_vista_nit;
    public function mostrar_pdf_nit($id)
    {

        if ($id) { 
            $path = 'Aportes/Institucion/Nit/' . $id . '_nit' . '.pdf';

            $fileContents = Storage::disk('ftp')->get($path);
            $pdfBase64 = base64_encode($fileContents); 
            $this->pdf_vista_nit = $pdfBase64; 

        }
    }
}
