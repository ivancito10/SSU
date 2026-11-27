<?php

namespace App\Livewire\Aportes\AportesVoluntarios\AportantesVoluntarios;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Afiliacion\Titular;
use App\Models\Aportes\ContratoVoluntario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Predis\Collection\Iterator\ListKey;

class AportantesVoluntariosMain extends Component
{
    use WithPagination;

    public $contratoId;

    public $modal;

    public $contratoAnterior;

    public $personaId;

    public $direccion;

    public $telefono1;

    public $telefono2;

    public $fechaInicioContrato;

    public $fechaFinContrato;

    public $descripcionContrato;

    public $zona;

    public $zonaId;

    public $numero;

    public $estado = true;

    public $correo;

    public $ci;

    public $nombre;

    public $primerApellido;

    public $segundoApellido;

    public $fechaNacimiento;

    public $complemento;

    public $fecha_i_m;

    public $fecha_f_m;

    public $renovarFechaContrato;

    protected $listeners = ['renovarContrato'];

    public function render()
    {

    //     $contratos = ContratoVoluntario::select(
    //         'persona.ci',
    //         'persona.matricula_seguro',
    //         'aportes.contrato_voluntario.fecha_fin_contrato',
    //         'aportes.contrato_voluntario.id_persona',
    //         'titular.id as id_titular'
    //     )
    //         ->join(
    //             'administracion.persona as persona',
    //             'persona.id',
    //             '=',
    //             'aportes.contrato_voluntario.id_persona'
    //         )
    //         ->join('afiliacion.titular as titular', function ($join) {
    //             $join->on('titular.id_persona', '=', 'aportes.contrato_voluntario.id_persona')
    //                 ->where('titular.tipo_titular', false)
    //                 ->where('titular.estado_vigencia', 'VIGENTE');
    //         })
    //         ->join(DB::raw('(
    //     SELECT id_persona, MAX(fecha_fin_contrato) AS max_fecha
    //     FROM aportes.contrato_voluntario
    //     GROUP BY id_persona
    // ) as ult'), function ($join) {
    //             $join->on('aportes.contrato_voluntario.id_persona', '=', 'ult.id_persona')
    //                 ->on('aportes.contrato_voluntario.fecha_fin_contrato', '=', 'ult.max_fecha');
    //         })
    //         ->where('aportes.contrato_voluntario.fecha_fin_contrato', '<', Carbon::now()->format('Y-m-d'))
    //         ->orderBy('aportes.contrato_voluntario.id_persona', 'asc')
    //         ->get();

    //     dd($contratos);
        $zonasSelected = Zona::orderBy('nombre', 'asc')->get();

        return view('livewire.Aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-main', compact('zonasSelected'));
    }

    public function emitirFechaInicio()
    {
        $this->fechaFinContrato = Carbon::parse($this->fechaInicioContrato)
            ->addYears(2)
            ->format('Y-m-d');
        $this->skipRender();
    }

    public function mostrarContrato($contratoId)
    {
        if ($contratoId) {
            $this->contratoAnterior = ContratoVoluntario::find($contratoId);
            $contrato = ContratoVoluntario::find($contratoId);
            $this->telefono1 = $contrato->telefono_1;
            $this->telefono2 = $contrato->telefono_2;
            $this->fecha_i_m = Carbon::parse($contrato->fecha_inicio_contrato)->format('d/m/Y');
            $this->fecha_f_m = Carbon::parse($contrato->fecha_fin_contrato)->format('d/m/Y');

            $this->descripcionContrato = $contrato->descripcion_contrato;

            $residencia = Residencia::find($contrato->id_residencia);
            $this->direccion = $residencia->direccion;
            $this->numero = $residencia->nro_vivienda;

            $zonaActual = Zona::find($residencia->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;
            $this->personaId = $contrato->id_persona;
            $this->correo = $contrato->correo;
            $this->estado = $contrato->estado;

            $persona = Persona::find($contrato->id_persona);
            $this->ci = $persona->ci;
            $this->nombre = $persona->nombres;
            $this->primerApellido = $persona->p_apellido;
            $this->segundoApellido = $persona->s_apellido;
            $this->fechaNacimiento = Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y');
            $this->complemento = $persona->complemento;

            $fechaDentrodeSemanaAnterior = Carbon::parse($this->fechaFinContrato)
                ->subWeek()
                ->lte(Carbon::now());
            $mayorQueFechaActual = Carbon::parse($this->fechaFinContrato)->lt(Carbon::now());

            if ($fechaDentrodeSemanaAnterior || $mayorQueFechaActual) {
                $this->renovarFechaContrato = true;
            } else {
                $this->renovarFechaContrato = false;
            }

            return $contrato;
        }
    }

    public function cancel()
    {
        $this->dispatch('contratoCancelado');
    }

    #[On('vista')]
    public function verContrato($data)
    {
        $this->contratoId = $data['contratoId'];
        $this->modal = $data['modalVista'];
        $this->mostrarContrato($this->contratoId);
        $this->dispatch('openModalVer');
    }

    #[On('habilitar_contrato')]
    public function habilitar($rowId)
    {

        $this->modal = false;
        try {
            $contrato = ContratoVoluntario::find($rowId);

            if ($contrato) {
                $estadoActual = $contrato->estado;
                dd($estadoActual);
                $contrato->estado = ! $estadoActual;
                $contrato->id_user_updated = auth()->user()->id;
                $contrato->save();
                $this->dispatch('pg:eventRefresh-AportanteVoluntariLista');
                $this->dispatch('contratoHabilitado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('contratoNoActualizado');
        }
    }

    #[On('deshabilitar_contrato')]
    public function deshabilitar($rowId)
    {

        $this->modal = false;
        try {
            $contrato = ContratoVoluntario::find($rowId);
            //dd($contrato);
            if ($contrato) {
                $estadoActual = $contrato->estado;
                $contrato->estado = ! $estadoActual;
                $contrato->save();
                $this->dispatch('pg:eventRefresh-AportanteVoluntariLista');
                $this->dispatch('contratoDeshabilitado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('contratoNoActualizado');
        }
    }

    // Listamos todos lo aportes de un aportante voluntario
    public $search;

    public $lista = [];

    public function listaAportes($idPersona)
    {
        // dd($idPersona);
        if ($idPersona) {
            $this->lista = ContratoVoluntario::where('contrato_voluntario.id_persona', $idPersona)
                ->join('aportes.aporte_v as v', 'contrato_voluntario.id', '=', 'v.id_contrato_voluntario')
                ->join('aportes.comprobante as c', 'v.id', '=', 'c.id_aporte_v')
                ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
                ->select('c.gestion', 'c.fecha_liquidacion', 'c.total_comprobante', 'm.mes')
                ->orderBy('c.id', 'desc')
                ->get();
        }
        // Agrega la condición de búsqueda
        /* if ($this->search) {
            $query->where(function($q) {
                $q->where('m.mes', 'LIKE', '%' . strtoupper($this->search) . '%')
                  ->orWhere('c.gestion', 'LIKE', '%' . strtoupper($this->search) . '%')
                  ->orWhere('c.fecha_liquidacion', 'LIKE', '%' . strtoupper($this->search) . '%')
                  ->orWhere('c.total_comprobante', 'LIKE', '%' . strtoupper($this->search) . '%');
            });
            $this->skipRender();
        } */

        return $this->lista;
    }

    // verAportante
    #[On('verAportante')]
    public function verAportante($data)
    {

        $this->contratoId = $data;
        $this->mostrarContrato($this->contratoId);
        $this->dispatch('modalVerAportante');
    }

    // editarAportante
    #[On('editarAportante')]
    public function editarAportante($data)
    {
        $this->contratoId = $data;
        $this->mostrarContrato($this->contratoId);
        $this->dispatch('modalEditarAportante');
    }

    // registroAportes
    #[On('registroAportes')]
    public function registroAportes($data)
    {
        $this->contratoId = $data;
        $this->mostrarContrato($this->contratoId);
        $contratoVol = ContratoVoluntario::find($this->contratoId);
        $persona = $contratoVol->id_persona;
        $this->listaAportes($persona);
        $this->dispatch('modalRegistroAportes');
    }
}
