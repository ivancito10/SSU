@extends('principal')

@section('contenido')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.body.classList.add('sidebar-collapse');
    });
</script>

@can('agendar.agenda_plataforma')
<!-- Contenido específico de la vista Index -->
<livewire:plataforma.agendamiento.plataforma.agenda-nuevo/>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
