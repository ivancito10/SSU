@extends('principal')

@section('contenido')

@can('menu.aportes_institucionales')
@livewire('Aportes.aportes-institucionales.aportes-institucionales-main')
@else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
@endcan


<script>
         document.addEventListener('DOMContentLoaded', function() {
            @if(session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'ErrorRegistroBajaMedica') {
                        Swal.fire({
                            title: "Error!",
                            text: "El comprobante no fue LIQUIDAD o la planilla no corresponde a un comprobante PATRONAL!!!",
                            icon: "error"
                        });
                } 
            @endif
        });
    </script>

@stop 


        