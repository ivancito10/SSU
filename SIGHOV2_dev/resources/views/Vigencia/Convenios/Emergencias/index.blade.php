@extends('principal')

@section('contenido')

    <div>
        @livewire('vigencia.convenios.emergencias.convenio-emer-main')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'ConvenioRegistrado') {
                    Swal.fire({
                        title: "GUARDADO",
                        text: "El convenio de emergencia fue registrado existosamente",
                        icon: "success"
                    });
                } else if (evento === 'ConvenioNoRegistrado') {
                    Swal.fire({
                        title: "CANCELADO",
                        text: "No se realizó el registro",
                        icon: "error"
                    });
                }
            @endif
        });
    </script>

@stop
