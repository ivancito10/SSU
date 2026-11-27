@extends('principal')

@section('contenido')
    <!-- ACA VAN LAS INSTITUCIONES--> 

    @can('menu.extracto_institucion')
    @livewire('Aportes.Extractos.Instituciones.extracto-instituciones-main')
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan

@stop
