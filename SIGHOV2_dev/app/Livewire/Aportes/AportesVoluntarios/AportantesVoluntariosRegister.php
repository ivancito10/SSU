<?php

namespace App\Livewire\Aportes\AportesVoluntarios;

use Livewire\Component;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Persona;
use Carbon\Carbon;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use Livewire\Attributes\On;

class AportantesVoluntariosRegister extends Component
{
    public $buscarCarnet;
    public $buscarComplemento;
    public $datosPersona;
    public $estadoBusqueda;

    public $modal;
    //Si se encuentra 2, Si no hay coincidencias 1, en otro caso 0

    public $personaId;
    public $direccion;
    public $telefono1;
    public $telefono2;
    public $fechaInicioContrato;
    public $fechaFinContrato;
    public $descripcionContrato;
    public $zona;
    public $numero;
    public $estado = true;

    public $ci;
    public $nombre;
    public $primerApellido;
    public $segundoApellido;
    public $fechaNacimiento;
    public $complemento;
    public $estado_asuss;
    public $correo;

    protected $listeners = ['guardarContrato'];

    public function render()
    {
        $zonas = Zona::all();
        return view('livewire.Aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-register', compact('zonas'));
    }

    public function emitirFechaInicio()
    {
        $this->fechaFinContrato = Carbon::parse($this->fechaInicioContrato)
            ->addYears(2)
            ->format('Y-m-d');
        $this->skipRender();
    }

    public function buscarDatos()
    {
        if ($this->buscarComplemento == '') {
            $this->buscarComplemento = null;
        }

        $validacionAsuss = new ValidarPersona();
        $this->datosPersona = $validacionAsuss->buscarPersona($this->buscarCarnet, $this->buscarComplemento);
        //dd($this->datosPersona);
        if (isset($this->datosPersona['datosPersonaEnFormatoJson'])) {
            $this->nombre = $this->datosPersona['datosPersonaEnFormatoJson']['Nombres'];
            $this->primerApellido = $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'];
            $this->segundoApellido = $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'];
            $this->fechaNacimiento = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'], 'America/La_Paz')->format('Y-m-d');
            $this->complemento = $this->datosPersona['datosPersonaEnFormatoJson']['complemento'];
            $this->ci = $this->datosPersona['datosPersonaEnFormatoJson']['numeroDocumento'];
            $this->estado_asuss = 1;
            $this->estadoBusqueda = 2;
        } else {
            $this->nombre = '';
            $this->primerApellido = '';
            $this->segundoApellido = '';
            $this->fechaNacimiento = '';
            $this->ci = $this->buscarCarnet;
            $this->complemento = $this->buscarComplemento;
        }
        $this->dispatch('cambioBusqueda', $this->estadoBusqueda);
        $this->skipRender();
    }

    public function guardarContrato()
    {
        $this->validate([
            'direccion' => 'required',
            'telefono1' => 'required|numeric',
            'telefono2' => 'numeric',
            'fechaInicioContrato' => 'required',
            //'fechaFinContrato' => 'required',
            'numero' => 'required',
            'correo' => 'required|email',
            'nombre' => 'required',
        ]);

        try {
            $existePersona = Persona::where('ci', $this->ci)
                ->where('complemento', $this->complemento)
                ->where('nombres', $this->nombre)
                ->where('p_apellido', $this->primerApellido)
                ->where('s_apellido', $this->segundoApellido)
                ->where('fecha_nacimiento', $this->fechaNacimiento)
                ->first();

            if ($existePersona) {
                $contrato = ContratoVoluntario::create([
                    'id_persona' => $existePersona->id,
                    'direccion' => $this->direccion,
                    'telefono_1' => $this->telefono1,
                    'telefono_2' => $this->telefono2,
                    'correo' => $this->correo,
                    'fecha_inicio_contrato' => $this->fechaInicioContrato,
                    'fecha_fin_contrato' => $this->fechaFinContrato,
                    'descripcion_contrato' => $this->descripcionContrato,
                    'id_clase_aportante' => 5,
                    'id_zona' => $this->zona,
                    'numero' => $this->numero,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);
            } else {

                if ($this->complemento == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento;
                }

                $persona = Persona::create([
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'nombres' => $this->nombre,
                    'p_apellido' => $this->primerApellido,
                    's_apellido' => $this->segundoApellido,
                    'fecha_nacimiento' => $this->fechaNacimiento,
                    'estado_asuss' => $this->estado_asuss,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);

                $personaId = $persona->id;

                $contrato = ContratoVoluntario::create([
                    'id_persona' => $personaId,
                    'direccion' => $this->direccion,
                    'telefono_1' => $this->telefono1,
                    'telefono_2' => $this->telefono2,
                    'correo' => $this->correo,
                    'fecha_inicio_contrato' => $this->fechaInicioContrato,
                    'fecha_fin_contrato' => $this->fechaFinContrato,
                    'descripcion_contrato' => $this->descripcionContrato,
                    'id_clase_aportante' => 5,
                    'id_zona' => $this->zona,
                    'numero' => $this->numero,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($contrato) {
                    $this->dispatch('contratoCreado');
                    $this->dispatch('pg:eventRefresh-AportanteVoluntariLista');
                    $this->buscarCarnet = '';
                    $this->buscarComplemento = '';
                    $this->datosPersona = '';
                    $this->estadoBusqueda = ''; //Si se encuentra 2, Si no hay coincidencias 1, en otro caso 0

                    $this->personaId = '';
                    $this->direccion = '';
                    $this->telefono1 = '';
                    $this->telefono2 = '';
                    $this->fechaInicioContrato = '';
                    $this->fechaFinContrato = '';
                    $this->descripcionContrato = '';
                    $this->zona = '';
                    $this->numero = '';

                    $this->ci = '';
                    $this->nombre = '';
                    $this->primerApellido = '';
                    $this->segundoApellido = '';
                    $this->fechaNacimiento = '';
                    $this->complemento = '';
                    $this->estado_asuss = '';
                    $this->correo = '';
                }
            }
        } catch (\Throwable $th) {
            $this->dispatch('contratoCancelado');
        }
    }

    public function cancelar()
    {

        $this->dispatch('contratoCancelado');
    }
}
