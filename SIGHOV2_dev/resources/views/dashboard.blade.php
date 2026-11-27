@extends('adminlte::page')

@section('title', 'SIGHO V2')

@section('content_header')
    <div
        style="
        background: linear-gradient(to right, #f0f4f8,rgb(214, 214, 221));
        padding: 20px 20px;
        border-radius: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 10000px;
        margin: 0 auto;
    ">
        <h1 style="font-size: 2.2em; margin-bottom: 10px;">
            <span style="color: #1f2937;">Bienvenido al</span><br>
            <strong style="color: #2471a3;">Seguro Social Universitario</strong>
            <span style="color: #cb4335;">La Paz</span>
        </h1>
        <p style="color: #6b7280; font-size: 1em; margin-top: 10px;">
            Sistema de Gestión Hospitalaria <strong>SIGHOv2</strong>
        </p>
    </div>

@stop


@section('content')
    <!-- <p>Welcome to this beautiful admin panel.</p>-->


    @livewire('administracion.dashboard.dashboard-estadisticas')
    @livewire('administracion.notificaciones.notificaciones-detalles')
    @livewire('administracion.cambio-contrasenia.cambio-contrasenia-inicio')
    <!-- Para bloquear la pantalla si la UFV no esta actualizada del dia -->
    @can('menu.agenda_plataforma')
        @livewire('aportes.ufv.alertaufv.alerta-ufv-main')
    @endcan


    @persist('player')
        <audio src="{{ asset('sound/BienvenidaSistema.mp3') }}" autoplay></audio>
    @endpersist

    @include('numeros_emergencia')

@stop

@section('content_top_nav_left')
    <!-- Botón de emergencias -->
    <li class="nav-item me-3">
        <a href="#" class="nav-link" title="Números de emergencia" data-toggle="modal" data-target="#modalEmergencia">
            <i class="fas fa-phone-volume text-danger fa-2x"></i>
        </a>
    </li>

    <!-- Nuevo botón que redirige a una ruta -->
    @can('menu.agenda_web')
        <li class="nav-item me-3">
            <a id="botonAgendamientoWeb" href="{{ route('plataforma.agenda-web.index') }}"
                class="btn btn-primary d-flex align-items-center px-3 py-2 shadow-lg rounded-pill fw-bold animate__animated animate__pulse animate__infinite"
                title="Agendamiento Web">
                <span>Agendamiento Web</span>
            </a>
        </li>
    @endcan
    <h3 class="card-title ml-2" style="margin-top:10px;">
        <b class="text-info d-none d-sm-inline">SEGURO SOCIAL UNIVERSITARIO</b>
        <b class="text-info d-none d-sm-none">SSU</b>
        <b class="text-danger d-none d-sm-inline">LA PAZ</b>
    </h3>
    <style>
        .card-title {
            font-size: 1.3rem;
        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1rem;
            }
        }
    </style>

@endsection

@section('content_top_nav_right')

    <div class="d-none d-sm-inline">
        {{-- @livewire('administracion.notificaciones.notificaciones-main') --}}
    </div>

@endsection


@section('js')
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "ed0394bd-c7ad-488d-a461-4125ccbb5411",
                //   appId: "dd52755f-15c1-4bac-9863-984174a3a0fc",

            });
        });
    </script>
    <script src="{{ asset('js/bloqueos.js') }}"></script>
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('botonAgendamientoWeb');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    btn.classList.add('disabled');
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.7';
                });
            }
        });
    </script>
@stop
