<?php

namespace App\Livewire\Aportes\Institucion;

use Livewire\Component;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Aportes\ClaseAportante;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;

use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class InstitucionEdit extends Component
{

    use WithFileUploads;
    #[Validate]
    public $file_digital_nit;
    public $file_digital_ci;



    public $id_institucion;
    public $nombres, $ap_paterno, $ap_materno, $fecha_nacimiento, $ci, $complemento_m, $institucion,
        $nit, $nro_empleador, $clase_aportante, $acronimo, $actividad_empleador, $fecha_afiliacion, $zona,
        $zonaId, $correo, $telefono, $tipo_doc, $expedido_ci, $estado, $publica, $tgn, $latitudRegistered,
        $longitudRegistered, $latitud, $longitud;
    #[Validate]
    public $direccion, $numero, $clase_aportante_id, $tipo_comercio, $tipo_institucion, $direccion_referencia, $telefono_dos;
    public $ubicacion_select = 0;
    public $zonasSelect = [], $claseSelect = [];
    protected $listeners = ['actualizar', 'recibirLonLat', 'cancel'];

    public function render()
    {
        return view('livewire.Aportes.institucion.institucion-edit');
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
            'actividad_empleador' => 'required',
            'direccion' => 'required',
            'numero' => 'required',
            'zona' => 'required',
            'publica' => 'required',
            //'tipo_doc' => 'required',
            'tipo_comercio' => 'required',
            'tipo_institucion' => 'required',
             'direccion_referencia' => 'required',
             'file_digital_nit' => 'nullable|mimes:pdf|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'file_digital_nit.mimes' => 'El archivo debe estar en formato PDF.',
            'file_digital_nit.max' => 'El archivo NIT debe ser menor a 1MB (1024KB).',
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
           // dd( $this->tipo_institucion,$institucion);
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

            $this->fecha_nacimiento = $representante->fecha_nacimiento;

            $es_extranjero = $representante->es_extranjero;
            $id_dept_exp = $representante->id_dept_exp;
            if ($es_extranjero == true) {
                $this->tipo_doc = "EXTRANJERO";
            } else {
                $this->tipo_doc = "NACIONAL";
            }
            if ($id_dept_exp == null) {
                $this->expedido_ci = null;
            } else {
                $departamento = Departamento::find($id_dept_exp);
                $this->expedido_ci = $departamento->sigla;
            }

            return $institucion;
        }
    }
    public function atras()
    {

        return redirect()->route('aportes.instituciones.index');
    }

    // actualizar institucion
    public function actualizar()
    {
        // dd('aqui');
        $validated = $this->validate();

        try {
            $institucion = Institucion::find($this->id_institucion);
            if ($this->nit != $institucion->nit) {
                $this->validate([
                    'file_digital_nit' => 'required|mimes:pdf', // Ejemplo de validación
                ]);
            }
            if($this->telefono == "" || $this->telefono == null ){
                $this->telefono = 0;
            }
            if($this->telefono_dos == "" || $this->telefono_dos == null ){
                $this->telefono_dos = 0;
            }
            
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

                $residenciaId = $institucion->id_residencia;
                $nueva_residencia = Residencia::find($residenciaId);
                if ($nueva_residencia) {
                    $nueva_residencia->direccion = $this->direccion;
                    $nueva_residencia->nro_vivienda = $this->numero;
                    $nueva_residencia->id_zona = $this->zonaId;
                    if ($this->latitud != '') {
                        $nueva_residencia->latitud = $this->latitud;
                        $nueva_residencia->longitud = $this->longitud;
                    }
                    $nueva_residencia->id_user_updated = auth()->user()->id;

                    $nueva_residencia->save();
                }

                $institucion->id_user_updated = auth()->user()->id;
                //dd($institucion);
                $institucion->save();

                if ($this->file_digital_nit) {

                    // guardamos en el FTP NIT
                    $nombre_file_nit = $institucion->id . '_nit' . '.pdf';

                    $this->file_digital_nit->storeAs('Aportes/Institucion/Nit', $nombre_file_nit, 'ftp');
                }

                $this->dispatch('institucionModificada');
                $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
                return redirect()->route('aportes.instituciones.index');
            }
        } catch (\Throwable $th) {

            $this->dispatch('error');
        }
    }

    public function cancel()
    {

        // Redirigir a la ruta deseada
        return redirect()->route('aportes.instituciones.index')->with('evento', 'institucionCancelado');
    }
}
