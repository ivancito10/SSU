<?php

namespace App\Livewire\Aportes\Extractos\Instituciones;

use App\Models\Aportes\Institucion;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ExtractoInstitucionesTable extends PowerGridComponent
{
    public string $sortField = 'institucion.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Institucion::query()
        ->join('aportes.clase_aportante as clase', function ($clase){
            $clase->on('institucion.id_clase_aportante', '=', 'clase.id');
        })
        ->select('institucion.*', 'clase.tipo as claseaportante_tipo');
        //->whereNotIn('institucion.nombre', ['UNIVERSIDAD MAYOR DE SAN ANDRES ADMINISTRATIVO', 'UNIVERSIDAD MAYOR DE SAN ANDRES DOCENTE']);

    }

    public function relationSearch(): array
    {
        return [
            'claseAportanteInstitucion' => ['tipo'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nit')
            ->add('numero')
            ->add('nombre')
            ->add('estado')
            ->add('estado_formatted', function ($dish) {
                if ($dish->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill=" #c0392b" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
                }
            })
            ->add('tgn')
            ->add('tgn_formatted', function ($dish) {
                if ($dish->tgn) {
                    return "<span style='color: #196f3d '><b>SI</b></span>";
                } else {
                    return "<span style='color: #c0392b'><b>NO</b></span>";
                }
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('id_usuario_responsable')
            ->add('claseaportante_tipo')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
            ->index(),
            

            Column::make('INSTITUCIÓN', 'nombre')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('NIT', 'nit')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            

            Column::make('APORTANTE', 'claseaportante_tipo')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
            ->sortable()
                ->searchable(),

            Column::make('TGN', 'tgn_formatted', 'tgn')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
            ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado_formatted', 'estado')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
            ->sortable()
            ->searchable(),
            
            

            Column::action('OPCIONES')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
        ];
    }

    #[\Livewire\Attributes\On('ver')]
    public function ver($rowId, $abrirModalVer): void
    {
        $this->modalVer = $abrirModalVer;
        $this->institucionId = $rowId;
        $this->dispatch('vistaExtractoInsti', ['modalVer' => $this->modalVer, 'institucionId' => $this->institucionId])
        ->to(ExtractoInstitucionesMain::class);
        
    }

    public function actions(Institucion $row): array
    {
        return [
            Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                    <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z"/>
                    </svg>')
                ->id()
                ->class('btn btn-orange')
                ->dispatchTo('aportes.extractos.instituciones.extracto-instituciones-main', 'extractoInstitucion', ['data' => $row->id])
                ->tooltip('EXTRACTO INSTITUCIÓN')
                ->can(allowed:auth()->user()->can('extracto.extracto_institucion')),
                
        ];
    }
}
