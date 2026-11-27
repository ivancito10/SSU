@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    @can('menu.titulares')
        @livewire('afiliacion.titulares.titulares-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan

@stop
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('evento'))
            let evento = "{{ session('evento') }}";
            if (evento === 'TitularRegistrado') {
                //alert(evento);
                Swal.fire({
                    title: "Guardado!",
                    text: "Los datos se han guardado exitosamente.",
                    icon: "success"
                });
            } else if (evento === 'TitularNoRegistrado') {
                //alert(evento);
                Swal.fire({
                    title: "Cancelo el registro!",
                    text: "Se cancelo el registro",
                    icon: "error"

                });
            } else if (evento === 'TitularDuplicado') {
                //alert(evento);
                Swal.fire({
                    title: "Titular Duplicado",
                    text: "El titular que se intenta registrar ya existe y se encuentra vigente",
                    icon: "error"
                });
            }
        @endif
    });
</script>
