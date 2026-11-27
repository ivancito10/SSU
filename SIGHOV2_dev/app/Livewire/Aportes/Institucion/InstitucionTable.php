<?php

namespace App\Livewire\Aportes\Institucion;

use App\Models\Aportes\Institucion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class InstitucionTable extends PowerGridComponent
{
    use WithExport;

    public $institucionId;

    public $modal = false;

    public $modalVer = false;

    public string $tableName = 'InstitucionesRegistro';

    public string $sortField = 'id';

    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Collection
    {
        //Nueva consulta usando joins
        $instituciones = Institucion::query()
            ->join('administracion.persona as representante', function ($representante) {
                $representante->on('institucion.id_persona', '=', 'representante.id');
            })
            ->join('aportes.clase_aportante as clase_aportante', function ($clase) {
                $clase->on('institucion.id_clase_aportante', '=', 'clase_aportante.id');
            })
            ->select([
                'institucion.*',
                'representante.nombres as nom_rep',
                'representante.p_apellido as pap_rep',
                'representante.s_apellido as sap_rep',
                'clase_aportante.nombre as nombre_clase_aportante',
            ])
            //->whereNotIn('institucion.nombre', ['UNIVERSIDAD MAYOR DE SAN ANDRES ADMINISTRATIVO', 'UNIVERSIDAD MAYOR DE SAN ANDRES DOCENTE'])
            ->orderBy('institucion.id', 'desc')
            ->get();
        $instituciones->map(function ($institucion) {
            $institucion->persona_nombre_completo = $institucion->pap_rep.' '.$institucion->sap_rep.' '.$institucion->nom_rep;

            return $institucion;
        });

        return $instituciones;
    }

    //Para la busqueda por campos

    public function relationSearch(): array
    {
        return [
            'claseAportanteInstitucion' => ['tipo'],
            'personaRepresentanteInstitucion' => ['nombres', 'p_apellido', 's_apellido'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre')
            ->add('nit')
            ->add('id_persona')
            //->add('nom_rep', fn ($institucion) => e($institucion->representante->nombres))
            //->add('fecha_afiliacion_formatted', fn(Institucion $model) => Carbon::parse($model->fecha_afiliacion)->format('d/m/Y'))
            //->add('persona_nombre_completo', fn(Institucion $model) => $model->personaRepresentanteInstitucion->p_apellido . ' ' . $model->personaRepresentanteInstitucion->s_apellido . ' ' . $model->personaRepresentanteInstitucion->nombres)
            ->add('tipo', fn (Institucion $model) => $model->claseAportanteInstitucion->tipo)
            ->add('estado')
            ->add('estado_formatted', function ($estado) {
                if ($estado->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill=" #c0392b" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
                }
            })
            ->add('estado_validado')
            ->add('acronimo')
            ->add('publica', function ($publica) {
                if ($publica->publica == true) {
                    return '<span style="color:#154360;">PÚBLICA</span>';
                } else {
                    return '<span style="color: #c0392b;">PRIVADA</span>';
                }
            })
            ->add('estado_validado_formatted', function ($estado_validado) {
                if ($estado_validado->estado_validado == 0) {
                    return '<span style="color: orange"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                    <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                    </svg>
                    </span>';
                } else {
                    return '<span style="color:#4a8262 "> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                        <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                        <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/>
                        </svg>
                    </span>';
                }
            });
    }

    /*public function beforeSearch(string $field = null, string $search = null): string
    {
        if ($field === 'in_stock') {
            return match (mb_strtolower(trim($search))) {
                "<span style='color: green'>ACTIVO</span>" => '1',
                "<span style='color:red'>INACTIVO</span>"  => '0',
                default => $search,
            };
        }

        return $search;
    }*/

    public function columns(): array
    {
        return [

            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('INSTITUCIÓN', 'nombre')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('NIT', 'nit')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            /*Column::make('Fecha de Afiliacion', 'fecha_afiliacion_formatted', 'fecha_afiliacion')
                ->sortable()
                ->searchable(), */

            Column::make('REP. LEGAL', 'persona_nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CLASE APORTANTE', 'nombre_clase_aportante')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),
            Column::make('INSTITUCIÓN', 'publica')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ACRÓNIMO', 'acronimo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado_formatted', 'estado')
                ->bodyAttribute('text-center', 'color:red')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable(),

            Column::make('VALIDACIÓN', 'estado_validado_formatted', 'estado_validado')
                ->bodyAttribute('text-center', 'color:red')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-left tablita-cuerpo-sin-mover '),

        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId, $abrirModal): void
    {
        //Logica para enviar el id de la institucion
        $this->modal = $abrirModal;
        $this->institucionId = $rowId;
        $this->dispatch('edicion', ['modalEstado' => $this->modal, 'institucionId' => $this->institucionId])
            ->to(InstitucionMain::class);
    }

    #[\Livewire\Attributes\On('editrep')]
    public function editrep($rowId, $abrirModalEditarRep): void
    {
        $this->modalEditar = $abrirModalEditarRep;
        $this->institucionId = $rowId;
        $this->dispatch('edicionrep', ['modalEditarRep' => $this->modalEditar, 'institucionId' => $this->institucionId])
            ->to(InstitucionMain::class);
    }

    #[\Livewire\Attributes\On('ver')]
    public function ver($rowId, $abrirModalVer): void
    {
        $this->modalVer = $abrirModalVer;
        $this->institucionId = $rowId;
        $this->dispatch('vista', ['modalVer' => $this->modalVer, 'institucionId' => $this->institucionId])
            ->to(InstitucionMain::class);
    }

    #[\Livewire\Attributes\On('habilitar')]
    public function habilitar($rowId): void
    {

        $this->institucionId = $rowId;
        $this->dispatch('institucionId', [$this->institucionId])->to(InstitucionMain::class);
    }

    #[\Livewire\Attributes\On('deshabilitar')]
    public function deshabilitar($rowId): void
    {
        $this->institucionId = $rowId;
        $this->dispatch('institucionId', [$this->institucionId])->to(InstitucionMain::class);
    }

    public function actions(Institucion $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>')
            ->id()
            ->class('btn btn-blue')
            ->tooltip('VER INSTITUCIÓN')
            ->dispatchTo('aportes.institucion.institucion-main', 'abrirInstitucion', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.instituciones'));
        if ($row->estado_validado == false) {

            $buttons[] = Button::add('teal')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->dispatchTo('aportes.institucion.institucion-main', 'ValidaInstitucionConfirmacion', ['data' => $row->id])
                ->tooltip('VALIDAR INSTITUCIÓN')
                ->can(allowed: auth()->user()->can('validar.instituciones'));
        }
        $buttons[] = Button::add('edit')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-pencil-square"
                        viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>')

            ->class('btn btn-orange')
            ->route('aportes.institucion.edit-institucion', ['id' => $row->id])
            ->tooltip('MODIFICAR INSTITUCIÓN')
            ->can(allowed: auth()->user()->can('editar.instituciones'));

        $buttons[] = Button::add('editrep')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg>')
            ->class('btn btn-lila')
            ->route('aportes.institucion.edit-rep-legal', ['id' => $row->id])
            ->tooltip('MODIFICAR REPRESENTANTE LEGAL')
            //->dispatchTo('aportes.institucion.institucion-main', 'editarRepresentante',['data' => $row->id])
            ->can(allowed: auth()->user()->can('editar_representante.instituciones'));

        $buttons[] = Button::add('editUsuario')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-textarea-resize" viewBox="0 0 16 16">
            <path d="M0 4.5A2.5 2.5 0 0 1 2.5 2h11A2.5 2.5 0 0 1 16 4.5v7a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 0 11.5zM2.5 3A1.5 1.5 0 0 0 1 4.5v7A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5v-7A1.5 1.5 0 0 0 13.5 3zm10.854 4.646a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708l3-3a.5.5 0 0 1 .708 0m0 2.5a.5.5 0 0 1 0 .708l-.5.5a.5.5 0 0 1-.708-.708l.5-.5a.5.5 0 0 1 .708 0"/>
            </svg>')
            ->id()
            ->class('btn btn-blue')
            ->tooltip('REGISTRAR USUARIO ENCARGADO')
            ->dispatchTo('aportes.institucion.institucion-main', 'editarUsuarioEncargado', ['data' => $row->id]);

        $buttons[] = Button::add('asignarUsuario')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                </svg>')
            ->id()
            ->class('btn btn-infoDos')
            ->tooltip('REGISTRAR USUARIO AVISO BAJA')
            ->dispatchTo('aportes.institucion.institucion-main', 'asignarUsuarioAvisoBaja', ['data' => $row->id]);

        if ($row->estado == false) {
            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->dispatch('deshabilitar', ['rowId' => $row->id])
                ->tooltip('HABILITAR INSTITUCIÓN')
                ->can(allowed: auth()->user()->can('habilitar_desabilitar.instituciones'));
        } else {
            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->dispatch('habilitar', ['rowId' => $row->id])
                ->tooltip('DESHABILITAR INSTITUCIÓN')
                ->can(allowed: auth()->user()->can('habilitar_desabilitar.instituciones'));
        }

        $buttons[] = Button::add('verificar_aportantes')
            ->slot('
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-xls" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM6.472 15.29a1.2 1.2 0 0 1-.111-.449h.765a.58.58 0 0 0 .254.384q.106.073.25.114.143.041.319.041.246 0 .413-.07a.56.56 0 0 0 .255-.193.5.5 0 0 0 .085-.29.39.39 0 0 0-.153-.326q-.152-.12-.462-.193l-.619-.143a1.7 1.7 0 0 1-.539-.214 1 1 0 0 1-.351-.367 1.1 1.1 0 0 1-.123-.524q0-.366.19-.639.19-.272.527-.422.338-.15.777-.149.457 0 .78.152.324.153.5.41.18.255.2.566h-.75a.56.56 0 0 0-.12-.258.6.6 0 0 0-.247-.181.9.9 0 0 0-.369-.068q-.325 0-.513.152a.47.47 0 0 0-.184.384q0 .18.143.3a1 1 0 0 0 .405.175l.62.143q.326.075.566.211a1 1 0 0 1 .375.358q.135.222.135.56 0 .37-.188.656a1.2 1.2 0 0 1-.539.439q-.351.158-.858.158-.381 0-.665-.09a1.4 1.4 0 0 1-.478-.252 1.1 1.1 0 0 1-.29-.375m-2.945-3.358h-.893L1.81 13.37h-.036l-.832-1.438h-.93l1.227 1.983L0 15.931h.861l.853-1.415h.035l.85 1.415h.908L2.253 13.94zm2.727 3.325H4.557v-3.325h-.79v4h2.487z"/>
                </svg>
                ')
            ->class('btn btn-green')
            ->tooltip('DETALLE DE APORTANTES')
            ->dispatchTo('aportes.institucion.institucion-aportantes', 'verificarAfiliacion', ['id' => $row->id])
            ->can(allowed: auth()->user()->can('detalle_aportantes.instituciones'));

        return $buttons;
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
