@extends('principal')

@section('contenido')

@can('menu.afiliacion_estudiantes_ssue')
<livewire:afiliacion.estudiantes-ssue.estudiantes-create>
    @else
    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
                acceder a
                esta sección.</b></em></p>
    @endcan
    @stop
