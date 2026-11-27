@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('menu.institucion_convenio')
<livewire:vigencia.instituciones.institucion-convenio-main />
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

<script>
        document.addEventListener('instiCreada', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La institución se registró correctamente.",
                icon: "success"

            });
        });
        document.addEventListener('instiCancelado', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Usted canceló el registro.",
                icon: "error"

            });
        });
    </script>
@stop
