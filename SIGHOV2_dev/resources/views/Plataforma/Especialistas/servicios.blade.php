@extends('principal')

@section('contenido')

@can('asignar_servicio.especialistas')
<livewire:plataforma.especialista.especialista-servicio.especialista-servicio-main
    :idEsp="$id">
    @else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
                acceder a
                esta sección.</b></em></p>
    @endcan
    @stop
