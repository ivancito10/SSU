@extends('principal')

@section('contenido')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.body.classList.add('sidebar-collapse');
    });
</script>

@can('agendar.agenda_ecografia')
<!-- Contenido específico de la vista Index -->
<livewire:Plataforma.agendamiento.ecografia.agenda-ecografia.agenda-nuevo/>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop