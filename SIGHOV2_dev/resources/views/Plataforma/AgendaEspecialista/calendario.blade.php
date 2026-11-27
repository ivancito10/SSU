@extends('principal')

@section('contenido')

@can('ver.agenda_especialista')
<!-- Contenido específico de la vista Index -->
<livewire:plataforma.agenda-especialista.especialista-calendario :idEsp="$id">
    @else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
                acceder a
                esta sección.</b></em></p>
    @endcan
    @stop
