<?php

namespace App\Livewire\Afiliacion\Titulares;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Aportes\Institucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Administracion\NotificacionUsuarios;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Attributes\On;
use App\Models\Administracion\Persona;
use Livewire\Attributes\Validate;

class AgregarInstitucion extends Component
{
    public $idTit;
    public $instituciones_registradas = [];
    public $instituciones_sin_registrar = [];
    public $institucion_principal = false;
    public $institucionesRegistradasIds = [];
    #[Validate]
    public $institucionNuevaId, $nuevaFechaIngreso, $nuevoCargoActual, $nuevoSalario, $nueva_carga_horaria, $nuevaTitularidad;
    public $nuevoItem;
    public $nombreNuevaInstitucion, $tipoNuevaInstitucion, $nombreNuevoCodigoTitular, $nombreNuevoCodBen;


    public function render()
    {
        return view('livewire.Afiliacion.titulares.agregar-institucion');
    }
    // agrgar rules

    /* public function mount()
    {
        $this->resetearVariables();
    } */

    #[On('agregarInstitucion')]
    public function agregarInstitucion($data)
    {
        $this->resetearVariables();
        $this->idTit = $data;
        $this->mostrarInstitucionesRegistradas();
        $this->instituciones_sin_registrar = $this->obtenerInstituciones();

        $this->dispatch('modalAgregarNuevaInst');
    }


    public function mostrarInstitucionesRegistradas()
    {
        $instituciones = TitularInstitucion::with('titularInstitucionConInstitucion.codigosInstitucion')
            ->where('id_titular', $this->idTit)
            ->where('estado', true)
            ->orderBy('id', 'asc')
            ->get();
        //dd($instituciones);


        $this->institucionesRegistradasIds = $instituciones->pluck('id_institucion')->toArray();
        //dd($this->institucionesRegistradasIds);
        $this->instituciones_registradas = $instituciones->map(function ($insti, $key) {

            return [
                'estado' => $insti->estado,
                'id' => $insti->id,
                'primera_institucion' => ($key == 0),
                'nombre_institucion' => $insti->titularInstitucionConInstitucion->nombre ?? 'N/A',
                'codigo_titular' => ($insti->titularInstitucionConInstitucion?->codigosInstitucion?->codigo ?? '') .
                    ($insti->titularInstitucionConInstitucion?->codigosInstitucion?->tipo_titular ?? ''),

                'codigo_beneficiario' => ($insti->titularInstitucionConInstitucion?->codigosInstitucion?->codigo ?? '') .
                    ($insti->titularInstitucionConInstitucion?->codigosInstitucion?->tipo_beneficiario ?? ''),
                'tipo_institucion' => $insti->tipo_institucion,
                'cargo_actual' => $insti->cargo_actual,
                'titularidad' => $insti->titularidad,
                'salario' => $insti->salario,
                'fecha_ingreso' => $insti->fecha_ingreso,
                'item' => $insti->item,
                'carga_horaria' => $insti->carga_horaria,

            ];
        })->toArray();

        //dd($this->instituciones_registradas);
    }

    public function obtenerInstituciones()
    {
        //dd($this->institucionesRegistradasIds);
        $instituciones = Institucion::whereNotIn('id', $this->institucionesRegistradasIds)
            ->where('estado', true)
            ->where('nombre', '!=', 'UNIVERSIDAD MAYOR DE SAN ANDRES')
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nombre', 'like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%')
                        ->where('nombre', 'not like', '%-%');
                })
                    ->orWhere('nombre', 'not like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%');
            })
            ->with(['codigosInstitucion', 'claseAportanteInstitucion', 'institucionResidencia'])
            ->orderBy('nombre', 'asc')
            ->get();
        return $instituciones;
        //  dd($instituciones);
    }

    public function rules()
    {
        return [
            //'institucionNuevaId' => 'required',
            'nombreNuevaInstitucion' => 'required',
            'nuevaFechaIngreso' => 'required',
            'nuevoCargoActual' => 'required',
            'nuevoSalario' => 'required',
            'nueva_carga_horaria' => 'required',
            'nuevaTitularidad' => 'required',
        ];
    }

    public function datosInstitucionNueva()
    {
        $nuevaInstitucion = Institucion::where('id', $this->institucionNuevaId)
            ->with('claseAportanteInstitucion')
            ->first();
        $this->nombreNuevaInstitucion = $nuevaInstitucion->nombre;
        $this->tipoNuevaInstitucion = $nuevaInstitucion->claseAportanteInstitucion->tipo;
        $nuevosCodigos = CodigoInstitucionSsu::where('id_institucion', $nuevaInstitucion->id)->first();
        if (!$nuevosCodigos) {
            $this->nombreNuevoCodigoTitular = '';
            $this->nombreNuevoCodBen = '';
        } else {
            $this->nombreNuevoCodigoTitular = $nuevosCodigos->codigo . $nuevosCodigos->tipo_titular;
            $this->nombreNuevoCodBen = $nuevosCodigos->codigo . $nuevosCodigos->tipo_beneficiario;
        }
    }

    #[On('guardarInstitucionNueva')]
    public function guardarInstitucionNueva()
    {
        //dd('nuev', $this->nuevoItem);
        $this->validate();
        try {
            $nuevaAdicion = TitularInstitucion::create([
                'id_titular' => $this->idTit,
                'id_institucion' => $this->institucionNuevaId,
                'estado' => true,
                'fecha_ingreso' => $this->nuevaFechaIngreso,
                'cargo_actual' => $this->nuevoCargoActual,
                'salario' => $this->nuevoSalario,
                'item' => $this->nuevoItem,
                'carga_horaria' => $this->nueva_carga_horaria,
                'titularidad' => $this->nuevaTitularidad,
                'tipo_institucion' => $this->tipoNuevaInstitucion,
                'id_user_created' => auth()->user()->id,
            ]);
            $this->dispatch('nuevaInstitucionAgregada');
            $this->dispatch('cerrarModalAgregarNuevaInst');
            $tit = Titular::find($this->idTit);
            $user = User::where('id_persona', $tit->id_persona)->first();
            //  dd($user);
            $nuevaNotificacion = NotificacionUsuarios::create([
                'estado' => 'NO LEIDO',
                'prioridades' => 3,
                'id_usu_emisor' => auth()->user()->id,
                'id_usu_receptor' => $user->id, // 
                'fecha_hora_enviada' => Carbon::now(),
                'asunto' => 'AFILIACIÓN - INSTITUCIÓN AGREGADA',
                'mensaje' => 'La nueva institución fue añadida exitosamente.',
            ]);
        } catch (\Throwable $e) {
            $this->dispatch('nuevaInstitucionNoAgregada');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al guardar institucion nueva", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function resetearVariables()
    {
        $this->reset(
            'institucionNuevaId',
            'nombreNuevaInstitucion',
            'nuevaFechaIngreso',
            'nuevoCargoActual',
            'nuevoSalario',
            'nuevoItem',
            'nueva_carga_horaria',
            'nuevaTitularidad',
            'tipoNuevaInstitucion',
        );
    }

    public function cambiarAPrincipal($id_actual_a_cambiar)
    {
        $primera_institucion_actual = TitularInstitucion::where('id_titular', $this->idTit)
            ->where('estado', true)
            ->orderBy('id', 'asc')
            ->first();
        // dd($primera_institucion_actual);
        $institucion_seleccionada = TitularInstitucion::find($id_actual_a_cambiar);

        if ($primera_institucion_actual && $institucion_seleccionada && $primera_institucion_actual->id !== $institucion_seleccionada->id) {
            DB::transaction(function () use ($primera_institucion_actual, $institucion_seleccionada) {
                $datos_temporales = [
                    'id_institucion' => $primera_institucion_actual->id_institucion,
                    'fecha_ingreso' => $primera_institucion_actual->fecha_ingreso,
                    'cargo_actual' => $primera_institucion_actual->cargo_actual,
                    'salario' => $primera_institucion_actual->salario,
                    'created_at' => $primera_institucion_actual->created_at,
                    'updated_at' => $primera_institucion_actual->updated_at,
                    'id_user_created' => $primera_institucion_actual->id_user_created,
                    'id_user_updated' => $primera_institucion_actual->id_user_updated,
                    'tipo_institucion' => $primera_institucion_actual->tipo_institucion,
                    'item' => $primera_institucion_actual->item,
                    'carga_horaria' => $primera_institucion_actual->carga_horaria,
                    'titularidad' => $primera_institucion_actual->titularidad,
                    'fecha_baja' => $primera_institucion_actual->fecha_baja,
                ];
                // dd($datos_temporales);
                $primera_institucion_actual->update([
                    'id_institucion' => $institucion_seleccionada->id_institucion,
                    'fecha_ingreso' => $institucion_seleccionada->fecha_ingreso,
                    'cargo_actual' => $institucion_seleccionada->cargo_actual,
                    'salario' => $institucion_seleccionada->salario,
                    'created_at' => $institucion_seleccionada->created_at,
                    'updated_at' => $institucion_seleccionada->updated_at,
                    'id_user_created' => $institucion_seleccionada->id_user_created,
                    'id_user_updated' => $institucion_seleccionada->id_user_updated,
                    'tipo_institucion' => $institucion_seleccionada->tipo_institucion,
                    'item' => $institucion_seleccionada->item,
                    'carga_horaria' => $institucion_seleccionada->carga_horaria,
                    'titularidad' => $institucion_seleccionada->titularidad,
                    'fecha_baja' => $institucion_seleccionada->fecha_baja,
                ]);
                $institucion_seleccionada->update($datos_temporales);
                $this->mostrarInstitucionesRegistradas();
                $this->dispatch('institucionCambiada');
                $this->dispatch('pg:eventRefresh-TitularesInstitucionalesVoluntarios');
            });
        }
    }
}
