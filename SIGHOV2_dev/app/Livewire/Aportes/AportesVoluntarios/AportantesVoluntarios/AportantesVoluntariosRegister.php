<?php

namespace App\Livewire\Aportes\AportesVoluntarios\AportantesVoluntarios;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Aportes\ContratoVoluntario;
use App\Services\ValidarPersona;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class AportantesVoluntariosRegister extends Component
{
    use WithFileUploads;

    public $buscarCarnet;

    public $buscarComplemento;

    public $datosPersona;

    public $estadoBusqueda;

    public $modal;
    //Si se encuentra 2, Si no hay coincidencias 1, en otro caso 0

    public $personaId;

    public $existePersona;

    public $idPers;

    public $ci;

    public $nombres;

    public $primerApellido;

    public $segundoApellido;

    public $fechaNacimientoReal;

    public $fechaNacimiento;

    public $complemento;

    public $estado_asuss = false;

    public $file_digital;

    public $correo;

    public $direccion;

    public $telefono1;

    public $telefono2;

    public $fechaInicioContrato;

    public $fechaFinContrato;

    public $descripcionContrato;

    public $zona;

    public $numero;

    public $estado = true;

    protected $listeners = ['guardarContrato'];

    public function render()
    {
        $zonas = Zona::orderBy('nombre', 'asc')->get();

        return view('livewire.Aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-register', compact('zonas'));
    }

    public function rules()
    {
        return [
            'direccion' => 'required',
            'fechaInicioContrato' => 'required',
            'fechaFinContrato' => 'required',
            'fechaNacimiento' => 'required',
            'numero' => 'required',
            'file_digital' => 'required|file|mimes:pdf',
        ];
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

        try {

            if ($this->buscarComplemento == '') {
                $this->buscarComplemento = null;
            }

            $this->existePersona = Persona::where('ci', $this->buscarCarnet)
                ->where('complemento', $this->buscarComplemento)
                ->orderBy('id', 'desc')
                ->first();

            if ($this->existePersona != null) {
                $this->nombres = $this->existePersona->nombres;
                $this->primerApellido = $this->existePersona->p_apellido;
                $this->segundoApellido = $this->existePersona->s_apellido;
                $this->fechaNacimiento = $this->existePersona->fecha_nacimiento;
                //$this->fechaNacimiento = Carbon::parse($this->existePersona->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y'); //Probar esto
                $this->complemento = $this->existePersona->complemento;
                $this->ci = $this->existePersona->ci;
                $this->estado_asuss = $this->existePersona->estado_asuss;
                $this->estadoBusqueda = 2;

                // Suponiendo que $this->existePersona->fecha_nacimiento es una fecha válida
                $fechaNacimiento = Carbon::parse($this->fechaNacimiento);
                // Calcula la edad
                $edad = $fechaNacimiento->age;
                if ($edad < 18) {
                    $this->dispatch('menorEdad');
                }
            } else {

                $validacionAsuss = new ValidarPersona();
                $this->datosPersona = $validacionAsuss->buscarPersona($this->buscarCarnet, $this->buscarComplemento);

                if (isset($this->datosPersona['datosPersonaEnFormatoJson'])) {

                    $this->nombres = $this->datosPersona['datosPersonaEnFormatoJson']['Nombres'];
                    $this->primerApellido = $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'];
                    $this->segundoApellido = $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'];
                    $this->fechaNacimiento = $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'];
                    $this->complemento = $this->datosPersona['datosPersonaEnFormatoJson']['complemento'];
                    $this->ci = $this->datosPersona['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->estado_asuss = 1;
                    $this->estadoBusqueda = 1;

                    // Suponiendo que $this->existePersona->fecha_nacimiento es una fecha válida
                    $fechaNacimiento = Carbon::parse($this->fechaNacimiento);
                    // Calcula la edad
                    $edad = $fechaNacimiento->age;
                    if ($edad < 18) {
                        $this->dispatch('menorEdad');
                    }

                } else {
                    $this->nombres = '';
                    $this->primerApellido = '';
                    $this->segundoApellido = '';
                    $this->fechaNacimiento = '';
                    $this->ci = $this->buscarCarnet;
                    $this->complemento = $this->buscarComplemento;
                }
            }
            $this->skipRender();
        } catch (\Throwable $th) {
            $this->dispatch('noExiste');
        }
    }

    public function guardarContrato()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->existePersona = Persona::where('ci', $this->ci)
                ->where('complemento', $this->complemento)
                ->first();
            if ($this->correo == null || $this->correo == '') {
                $this->correo = 'aportes@ssulapaz.org';
            }
            $this->telefono1 = $this->telefono1 ?: null;
            $this->telefono2 = $this->telefono2 ?: null;

            if ($this->existePersona != null) {

                // Suponiendo que $this->existePersona->fecha_nacimiento es una fecha válida
                $fechaNacimiento = Carbon::parse($this->fechaNacimiento);

                $edad = $fechaNacimiento->age;
                if ($edad < 18) {
                    DB::rollBack();
                    $this->dispatch('menorEdad');

                } else {

                    if ($this->existePersona->fecha_nacimiento == null) {
                        Persona::where('id', $this->existePersona->id)->update(['fecha_nacimiento' => $this->fechaNacimiento]);
                    }

                    $residencia = Residencia::create([
                        'direccion' => $this->direccion,
                        'nro_vivienda' => $this->numero,
                        'id_zona' => $this->zona,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $residenciaId = $residencia->id;

                    $contrato = ContratoVoluntario::create([
                        'id_persona' => $this->existePersona->id,
                        'telefono_1' => $this->telefono1,
                        'telefono_2' => $this->telefono2,
                        'correo' => $this->correo,
                        'fecha_inicio_contrato' => $this->fechaInicioContrato,
                        'fecha_fin_contrato' => $this->fechaFinContrato,
                        'descripcion_contrato' => $this->descripcionContrato,
                        'id_clase_aportante' => 5,
                        'id_residencia' => $residenciaId,
                        'estado' => true,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    if ($contrato) {
                        DB::commit();
                        $this->dispatch('contratoCreado');
                        $this->dispatch('pg:eventRefresh-AportanteVoluntariLista');
                        $nombre_file = $contrato->id.'.pdf';
                        $this->file_digital->storeAs('Aportes/Voluntarios/Contratos', $nombre_file, 'ftp');

                        $this->buscarCarnet = '';
                        $this->buscarComplemento = '';
                        $this->datosPersona = '';
                        $this->estadoBusqueda = '';

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
                        $this->nombres = '';
                        $this->primerApellido = '';
                        $this->segundoApellido = '';
                        $this->fechaNacimiento = '';
                        $this->complemento = '';
                        $this->estado_asuss = false;
                        $this->correo = '';

                    } else {
                        DB::rollBack();
                        $this->dispatch('contratoCancelado');
                    }
                }

            } else {

                $existePersona = Persona::where('ci', $this->ci)
                    ->where('complemento', $this->complemento)
                    ->first();

                if ($existePersona != null) {
                    $fechaNacimiento = Carbon::parse($existePersona->fecha_nacimiento);
                    $edad = $fechaNacimiento->age;

                    if ($edad < 18) {
                        DB::rollBack();
                        $this->dispatch('menorEdad');

                    } else {
                        $personaId = $existePersona->id;
                    }

                } else {
                    if ($this->complemento == null) {
                        $clave_unica = $this->ci;
                    } else {
                        $clave_unica = $this->ci.$this->complemento;
                    }

                    $fechaNacimiento = Carbon::parse($this->fechaNacimiento);
                    $edad = $fechaNacimiento->age;

                    if ($edad < 18) {
                        DB::rollBack();
                        $this->dispatch('menorEdad');
                    } else {

                        $persona = Persona::create([
                            'ci' => $this->ci,
                            'complemento' => $this->complemento,
                            'nombres' => $this->nombres,
                            'p_apellido' => $this->primerApellido,
                            's_apellido' => $this->segundoApellido,
                            'fecha_nacimiento' => $this->fechaNacimiento,
                            'estado_asuss' => $this->estado_asuss,
                            'clave_unica' => $clave_unica,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        $personaId = $persona->id;
                        $residencia = Residencia::create([
                            'direccion' => $this->direccion,
                            'nro_vivienda' => $this->numero,
                            'id_zona' => $this->zona,
                            //'latitud' => $this->latitud,
                            //'longitud' => $this->longitud,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        $residenciaId = $residencia->id;

                        $contrato = ContratoVoluntario::create([
                            'id_persona' => $personaId,
                            'telefono_1' => $this->telefono1,
                            'telefono_2' => $this->telefono2,
                            'correo' => $this->correo,
                            'fecha_inicio_contrato' => $this->fechaInicioContrato,
                            'fecha_fin_contrato' => $this->fechaFinContrato,
                            'descripcion_contrato' => $this->descripcionContrato,
                            'id_clase_aportante' => 5,
                            'id_residencia' => $residenciaId,
                            'estado' => true,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        if ($contrato) {
                            DB::commit();
                            $nombre_file = $contrato->id.'.pdf';
                            $this->file_digital->storeAs('Aportes/Voluntarios/Contratos', $nombre_file, 'ftp');
                            $this->dispatch('contratoCreado');
                            $this->dispatch('pg:eventRefresh-AportanteVoluntariLista');

                            $this->buscarCarnet = '';
                            $this->buscarComplemento = '';
                            $this->datosPersona = '';
                            $this->estadoBusqueda = '';

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
                            $this->nombres = '';
                            $this->primerApellido = '';
                            $this->segundoApellido = '';
                            $this->fechaNacimiento = '';
                            $this->complemento = '';
                            $this->estado_asuss = false;
                            $this->correo = '';
                        } else {
                            DB::rollBack();
                            $this->dispatch('contratoCancelado');
                        }

                    }
                }

            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }

    }

    public function cancelar()
    {
        $this->reset('buscarCarnet', 'buscarComplemento', 'nombres', 'primerApellido', 'segundoApellido', 'ci',
            'complemento', 'fechaNacimiento', 'direccion', 'numero', 'zona', 'telefono1', 'telefono2', 'correo', 'fechaInicioContrato',
            'fechaFinContrato', 'file_digital', 'descripcionContrato');
    }
}
