@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
@can('menu.instituciones_externas')
    @livewire('Aportes.externos.institucion.institucion-main-ext')
@else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
@endcan
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'institucionCreada') {
                    Swal.fire({
                        title: "Guardado!",
                        text: "Los datos se han guardado exitosamente.",
                        icon: "success"
                    });
                }
                elseif(evento === 'institucionCancelada') {
                    Swal.fire({
                        title: "Cancelo el registro!",
                        text: "Se cancelo el registro",
                        icon: "error"

                    });
                }
                elseif(evento === 'instiCancelado') {
                    Swal.fire({
                        title: "Registro cancelado",
                        text: "Usted canceló el registro",
                        icon: "error"
                    });
                }
            @endif
        });
    </script>
@stop
