@extends('principal')

@section('contenido')

@can('menu.convenios_interior')
<div>
    @livewire('vigencia.convenios.interior.convenio-interior-main')
</div>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
<script>
        /document.addEventListener('DOMContentLoaded', function() {
            @if (session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'AutorizacionRegistrada') {
                    Swal.fire({
                        title: "Guardado!",
                        text: "Los datos se han guardado exitosamente.",
                        icon: "success"
                    });
                } else if (evento === 'AutorizacionNoRegistrada') {
                    Swal.fire({
                        title: "Se canceló la operación",
                        text: "Se cancelo el registro",
                        icon: "error"
                    });
                }
            @endif
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('eventoEd'))
                let eventoEd = "{{ session('eventoEd') }}";
                if (eventoEd === 'AutorizacionEditada') {
                    Swal.fire({
                        title: "Editado!",
                        text: "Los datos se han editado exitosamente.",
                        icon: "success"
                    });
                } else if (eventoEd === 'AutorizacionNoEditada') {
                    Swal.fire({
                        title: "Se canceló la operación",
                        text: "Se cancelo la edición",
                        icon: "error"
                    });
                }
            @endif
        });
    </script>

@stop
