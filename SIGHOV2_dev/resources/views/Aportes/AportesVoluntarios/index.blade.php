@extends('principal')

@section('contenido')
@can('menu.aportes_voluntarios')
{{--@livewire('Aportes.aportes-voluntarios.aporte-voluntario-main')--}}
<livewire:Aportes.aportes-voluntarios.aporte-voluntario-main />
@else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
@endcan

   
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            @if (session('evento'))
                
                let evento = "{{ session('evento') }}";
                if (evento === 'aporteVoluntarioCancelado') {
                    Swal.fire({
                        title: "Se canceló la operación",
                        text: "Se cancelo el registro",
                        icon: "error"

                    });
                }
                else if(evento === 'aporteVoluntarioCreado') {
                    Swal.fire({
                        title: "Guardado!",
                        text: "Se registro correctamente",
                        icon: "success"

                    });
                }
            
            @endif
        }); 

    </script>

@stop