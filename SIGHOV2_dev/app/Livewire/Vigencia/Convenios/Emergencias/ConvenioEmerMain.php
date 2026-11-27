<?php

namespace App\Livewire\Vigencia\Convenios\Emergencias;

use App\Models\User;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\TipoConvenio;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Administracion\Servicio;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\PersonalSsu;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\HistorialAsegurado;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ConvenioEmerMain extends Component
{
    // datos de persona del convenio
    public $idConvenio;
    public $nombreCompleto, $ci, $ciComp, $matricula, $fecha_nacimiento, $sexo, $complemento, $edadPersona, $tipo_asegurado;
    public $foto, $fotito;
    public $cod_convenio, $fecha_inicio, $fecha_fin, $dias, $today;
    public $medicamentos_liname, $medicamentos_extraliname, $observacion, $otras_limitaciones;
    public $estado_validez;
    public $nro_hoja_de_ruta, $fecha_solicitud_hr, $id_tipo_convenio;
    public $tipo_convenio;
    // datos para persona que autoriza
    public $nombreAutoriza, $ciAutoriza, $matriculaAutoriza;
    //datos del personal ssu   
    public $nombrePersonal, $ciPersonal;
    //DERTALLES DE ANUALCION
    public $fecha_anulacion, $motivo_anulacion, $nombreAnular, $anuladoPor;
    // para obtener la especialidad y el servicio 
    public $especialidades = [];
    public $servicios = [];
    public $cargo_personal;
    public $id_user_anulado;
    public $nro_documento_respaldo;
    public $created_at;
    public $nombre_institucion;
    public $fechaInicio, $fechaFin;

    protected $listeners = ['guardarAnulacionConvenioEmer'];


    public function render()
    {
        return view('livewire.Vigencia.convenios.emergencias.convenio-emer-main');
    }
    
    public function mount()
    {
        $this->today = Carbon::now()->format('d-m-Y');
    }

    public function mostrarConvenioEmer($idConvenio)
    {
        if ($this->idConvenio) {
            $convenio = Convenio::find($idConvenio);
            $this->cod_convenio = $convenio->cod_convenio;
            $this->nro_documento_respaldo = $convenio->nro_documento_respaldo;
            $this->fecha_inicio = $convenio->fecha_inicio;
            $this->fecha_fin = $convenio->fecha_fin;
            $this->dias = (Carbon::parse($this->fecha_fin)->diffInDays(Carbon::parse($this->fecha_inicio))) + 1;
            $this->medicamentos_liname = $convenio->medicamentos_liname;
            $this->medicamentos_extraliname = $convenio->medicamentos_extraliname;
            $this->observacion = $convenio->observacion;
            $this->otras_limitaciones = $convenio->otras_limitaciones;
            $this->estado_validez = $convenio->estado_validez;
            $this->nro_hoja_de_ruta = $convenio->nro_hoja_de_ruta;
            $this->fecha_solicitud_hr = $convenio->fecha_solicitud_hr;
            $this->id_tipo_convenio = $convenio->id_tipo_convenio;
            $this->created_at = Carbon::parse($convenio->created_at)->format('Y');

            $tipoConvenio = TipoConvenio::find($this->id_tipo_convenio);
            $this->tipo_convenio = $tipoConvenio->tipo_convenio;
            // datos de persona para el convenio
            $id_persona = $convenio->id_persona;
            $persona = Persona::find($id_persona);
            if ($persona->p_apellido == null) {
                $this->nombreCompleto = $persona->s_apellido . ' ' . $persona->nombres;
            } elseif ($persona->s_apellido == null) {
                $this->nombreCompleto = $persona->p_apellido . ' ' . $persona->nombres;
            } else {
                $this->nombreCompleto = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
            }

            if ($persona->complemento == null) {
                $this->ci = $persona->ci;
            } else {
                $this->ci = $persona->ci . ' - ' . $persona->complemento;
            }
            $this->complemento = $persona->complemento;

            $this->matricula = $persona->matricula_seguro;
            $this->fecha_nacimiento = $persona->fecha_nacimiento;
            $this->sexo = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
            $this->edadPersona = Carbon::parse($persona->fecha_nacimiento)->age;
            $this->tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado)->tipo_asegurado;

            // datos para persona que autotiza
            $id_titularAutoriza = $convenio->id_titular_autoriza;
            $titularAutoriza = Titular::find($id_titularAutoriza);
            $id_perTitular = $titularAutoriza->id_persona;
            $personaAutoriza = Persona::find($id_perTitular);

            if ($personaAutoriza->p_apellido == null) {
                $this->nombreAutoriza = $personaAutoriza->s_apellido . ' ' . $personaAutoriza->nombres;
            } elseif ($personaAutoriza->s_apellido == null) {
                $this->nombreAutoriza = $personaAutoriza->p_apellido . ' ' . $personaAutoriza->nombres;
            } else {
                $this->nombreAutoriza = $personaAutoriza->p_apellido . ' ' . $personaAutoriza->s_apellido . ' ' . $personaAutoriza->nombres;
            }

            $this->matriculaAutoriza = $personaAutoriza->matricula_seguro;

            if ($personaAutoriza->complemento == null) {
                $this->ciAutoriza = $personaAutoriza->ci;
            } else {
                $this->ciAutoriza = $personaAutoriza->ci . ' - ' . $personaAutoriza->complemento;
            }


            $titularInstitucion = TitularInstitucion::where('id_titular', $id_titularAutoriza)->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                ->orderBy('id', 'asc')->first();
            if ($titularInstitucion) {
                $this->nombre_institucion = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                if (!empty($titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo)) {
                    $this->matriculaAutoriza = $personaAutoriza->matricula_seguro . ' - ' . $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo
                        .  $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                }
            } else {
                $this->nombre_institucion = 'APORTANTE VOLUNTARIO';
            }


            //datos para el personal ssu
            $id_personalSsu = $convenio->id_personal_ssu;
            $personal = PersonalSsu::find($id_personalSsu);
            $idpersonaPersonal = $personal->id_persona;
            $this->cargo_personal = $personal->cargo;
            $personaPersonal = Persona::find($idpersonaPersonal);
            if ($personaPersonal->p_apellido == null) {
                $this->nombrePersonal = $personaPersonal->s_apellido . ' ' . $personaPersonal->nombres;
            } elseif ($personaPersonal->s_apellido == null) {
                $this->nombrePersonal = $personaPersonal->p_apellido . ' ' . $personaPersonal->nombres;
            } else {
                $this->nombrePersonal = $personaPersonal->p_apellido . ' ' . $personaPersonal->s_apellido . ' ' . $personaPersonal->nombres;
            }


            if ($personaPersonal->complemento == null) {
                $this->ciPersonal = $personaPersonal->ci;
            } else {
                $this->ciPersonal = $personaPersonal->ci . ' - ' . $personaPersonal->complemento;
            }
            //dd($convenio->foto_nombre);
            $this->fotito = $this->obtenerImagenBase64($convenio->foto_nombre);
            //dd($this->fotito);

            //-- PARA LA ANULACION--
            $this->id_user_anulado = $convenio->id_user_anulado;
            $this->fecha_anulacion = Carbon::parse($convenio->fecha_anulacion)->format('d/m/Y H:i:s');
            $this->motivo_anulacion = $convenio->motivo_anulacion;
            $this->anuladoPor = User::find($this->id_user_anulado);
            if ($this->anuladoPor) {
                $idPersonaAnulado = $this->anuladoPor->id_persona;
                $personaAnular = Persona::find($idPersonaAnulado);
                if ($personaAnular->p_apellido == null) {
                    $this->nombreAnular = $personaAnular->s_apellido . ' ' . $personaAnular->nombres;
                } elseif ($personaAnular->s_apellido == null) {
                    $this->nombreAnular = $personaAnular->p_apellido . ' ' . $personaAnular->nombres;
                } else {
                    $this->nombreAnular = $personaAnular->p_apellido . ' ' . $personaAnular->s_apellido . ' ' . $personaAnular->nombres;
                }
            } else {
                $this->nombreAnular = '';
            }
            // para mostras las especialidades y servicios
            $espeConvenio = ConvenioEspecialidad::join('vvdd.convenio', 'convenio.id', '=', 'vvdd.convenio_especialidad.id_convenio')
                ->join('administracion.especialidad', 'especialidad.id', '=', 'vvdd.convenio_especialidad.id_especialidad')
                ->where('convenio.id', $idConvenio)
                ->select('vvdd.convenio_especialidad.*', 'administracion.especialidad.especialidad as especialidad')
                ->get();
            //dd($espeConvenio);

            if ($espeConvenio) {
                $this->especialidades = $espeConvenio;
            } else {
                $this->especialidades = [];
            }

            $servConvenio = ConvenioServicio::join('vvdd.convenio', 'convenio.id', '=', 'vvdd.convenio_servicio.id_convenio')
                ->join('administracion.servicio', 'servicio.id', '=', 'vvdd.convenio_servicio.id_servicio')
                ->where('convenio.id', $idConvenio)
                ->select('vvdd.convenio_servicio.*', 'administracion.servicio.servicio as servicio')
                ->get();
            if ($servConvenio) {
                $this->servicios = $servConvenio;
            } else {
                $this->servicios = [];
            }
            // dd($this->especialidades, $this->servicios );
        }
    }

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }


    public function convenioRegister()
    {
        return redirect()->route('vigencia.convenios.emergencias.create');
    }


    //para MODAL VER
    #[On('verConvenioEmer')]
    public function verConvenioEmer($data)
    {
        $this->idConvenio = $data;
        $this->mostrarConvenioEmer($this->idConvenio);
        $this->dispatch('modalVerConvenioEmer');
    }

    // para anularBajaMedica
    #[On('anularConvenioEmer')]
    public function anularConvenioEmer($data)
    {
        $this->idConvenio = $data;
        $this->dispatch('alertaAnularConvenioEmer');
    }

    public function guardarAnulacionConvenioEmer($data)
    {
        //dd($data);
        // try {
        $convenio = Convenio::find($this->idConvenio);
        if ($convenio) {
            $convenio->estado_validez = 'ANULADO';
            $convenio->estado = false;
            $convenio->fecha_anulacion = Carbon::now();
            $convenio->motivo_anulacion = $data;
            $convenio->id_user_anulado = auth()->user()->id;
            $convenio->save();

            $idPersona = $convenio->id_persona;

            $historialAsegurado = HistorialAsegurado::where('id_persona', $idPersona)
                ->where('estado', true)
                ->where('id_corresponde', $convenio->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($historialAsegurado) {
                $historialAsegurado->estado_vigencia = 'NO VIGENTE';
                if ($historialAsegurado->fecha_baja == null) {
                    $historialAsegurado->fecha_baja = $convenio->fecha_fin;
                }
                $historialAsegurado->id_user_updated = auth()->user()->id;
                $historialAsegurado->save();
            }

            $aunTieneConvenio = AutorizacionInterior::where('id_persona_interior', $idPersona)
                ->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))
                ->where('estado', true)
                ->first();

            $aunTieneConvenioEmergencia = Convenio::where('id_persona', $idPersona)
                ->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))
                ->where('estado', true)
                ->first();

            if ($aunTieneConvenio == null && $aunTieneConvenioEmergencia == null) {
                $personaInterior = Persona::find($idPersona);
                $personaInterior->afiliado = false;
                $personaInterior->id_tipo_asegurado = 0;
                $personaInterior->id_user_updated = auth()->user()->id;
                $personaInterior->save();
            }

            $this->dispatch('pg:eventRefresh-ConvenioEmerTable');
            $this->dispatch('guardarConvenioEmer');
        }
        //  } catch (\Throwable $th) {
        //      $this->dispatch('noValidadoOrden');
        //  }
    }

    // Para el pdf 
    #[On('convenioEmergenciaPdf')]
    public function convenioEmergenciaPdf($data)
    {
        $this->js('window.open("pdfConvenioEmergencia/' . $data . '", "_blank")');
    }


    public function generarPDF()
    {
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->js('window.open("/vigencia/ConvenioEmergencia/' . $this->fechaInicio . '/' . $this->fechaFin . '")');
        } else {
            $this->dispatch('completeDatos');
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
        $this->skipRender();
    }
}
