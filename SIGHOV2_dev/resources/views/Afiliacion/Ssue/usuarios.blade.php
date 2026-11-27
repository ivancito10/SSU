@extends('principal')

@section('contenido')

    @can('menu.afiliacion_estudiantes_ssue')
        <livewire:Afiliacion.estudiantes-ssue.usuario-estudiante>
        @else
            <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                        esta sección.</b></em></p>
        @endcan
    @stop
