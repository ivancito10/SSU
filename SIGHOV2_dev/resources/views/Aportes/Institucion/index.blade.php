@extends('principal')

@section('contenido')

@can('menu.instituciones')
    @livewire('Aportes.institucion.institucion-main')
@else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
@endcan
<div>

</div>
    
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            @if(session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'institucionCreada') {
                        Swal.fire({
                            title: "Guardado!",
                            text: "Los datos se han guardado exitosamente.",
                            icon: "success"
                        });
                } else if (evento === 'institucionCancelada') {
                    Swal.fire({
                        title: "Cancelo el registro!",
                        text: "Se cancelo el registro",
                        icon: "error"

                    });
                }
            @endif
        });
    </script>
@stop



        