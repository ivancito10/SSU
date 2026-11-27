@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->

    @livewire('afiliacion.examen-preocupacional.r-r-h-h.solicitud-examen-preocupacional-main')
@stop


<script>
         document.addEventListener('DOMContentLoaded', function() {
            @if(session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'ExamenRegistrado') {
                        Swal.fire({
                            title: "Guardado!",
                            text: "Los datos se han guardado exitosamente.",
                            icon: "success"
                        });
                } else if (evento === 'examenNoRegistrado') {
                    Swal.fire({
                        title: "Cancelo el registro!",
                        text: "Se cancelo el registro",
                        icon: "error"

                    });
                }
            @endif
        });
    </script>