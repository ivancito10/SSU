@extends('principal')

@section('contenido')

    <livewire:Afiliacion.estudiantes-ssue.usuario-estudiante-register>

    @endsection

@section('js')
    @parent {{-- Conserva los scripts que ya están en la sección 'js' del layout --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'UsuarioCreado') {
                    Swal.fire({
                        title: "Guardado!",
                        text: "Los datos se han guardado exitosamente.",
                        icon: "success"
                    });
                }
            @endif
        });
    </script>



@endsection
