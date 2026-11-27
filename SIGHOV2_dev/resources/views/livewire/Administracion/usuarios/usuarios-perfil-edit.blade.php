<div class="container mt-4">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    {{-- CARD 1: DATOS PERSONALES --}}
    <div class="card-comun mb-4">
        <div class="card-header cabecera">
            <h3><b>DATOS PERSONALES</b></h3>
        </div>

        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                {{-- Imagen de Perfil --}}
                <div class="me-4 text-center">
                    <div class="form-group">
                        @if ($imagen)
                            <img src="{{ $imagen->temporaryUrl() }}" class="mt-2 img-thumbnail"
                                style="width: 350px; height: 250px; object-fit: cover;">
                        @elseif($user->imagen)
                            <img src="{{ Auth::user()->fotoUsuario() }}" class="mt-2 img-thumbnail"
                                style="width: 350px; height: 250px; object-fit: cover;">
                        @else
                            <img src="{{ asset('img/default-user.png') }}" class="mt-2 img-thumbnail"
                                style="width: 350px; height: 250px; object-fit: cover;">
                        @endif

                        <input type="file" class="form-control mt-2 @error('imagen') is-invalid @enderror"
                            wire:model="imagen" id="imagen">

                        @error('imagen')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Datos personales --}}
                <div class="w-100 ms-4 ps-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Nombre:</strong>
                                <p class="p-1 p-comun-disabled">{{ $name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Usuario SIGHOv2:</strong>
                                <p class="p-1 p-comun-disabled">{{ $email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Cédula de Identidad:</strong>
                                <p class="p-1 p-comun-disabled">{{ $ci }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Ejemplo: fecha de nacimiento --}}
                            <div class="form-group">
                                <strong>Fecha de Nacimiento:</strong>
                                <p class="p-1 p-comun-disabled">{{ $fecha_nacimiento ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            {{-- Ejemplo: edad --}}
                            <div class="form-group">
                                <strong>Edad:</strong>
                                <p class="p-1 p-comun-disabled">{{ $edad ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Correo electrónico como dato personal --}}
                            <div class="form-group">
                                <strong>Correo Electrónico:</strong>
                                <p class="p-1 p-comun-disabled">{{ $correo ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD 2: DATOS DE ASEGURADO --}}
    <div class="card-comun">
        <div class="card-header cabecera">
            <h3><b>DATOS DE ASEGURADO</b></h3>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipo_asegurado">Tipo de Asegurado:</label>
                        <span class="badge"
                            style="font-size: 1rem; padding: 10px;
                                background-color: {{ $this->tipo_asegurado == 1 ? 'blue' : ($this->tipo_asegurado == 2 ? 'green' : ($this->tipo_asegurado == 3 ? 'orange' : 'red')) }};
                                color: white;">
                            {{ $this->tipo_asegurado == 1 ? 'Titular' : ($this->tipo_asegurado == 2 ? 'Beneficiario' : ($this->tipo_asegurado == 3 ? 'Estudiante' : 'No Asegurado')) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Instituciones (ejemplo: lista) --}}
                    <div class="form-group">
                        <strong>Instituciones:</strong>
                        @if(!empty($instituciones ?? null) && count($instituciones) > 0)
                            <ul class="mb-0">
                                @foreach ($instituciones as $inst)
                                    <li>{{ $inst->titularInstitucionConInstitucion->nombre }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="p-1 p-comun-disabled">-</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Telefono:</strong>
                        <p class="p-1 p-comun-disabled">{{ $telefono ?? '-' }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- Matricula SSU como dato de asegurado --}}
                    <div class="form-group">
                        <strong>Matricula SSU:</strong>
                        <p class="p-1 p-comun-disabled">{{ $matricula ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Telefono referencia:</strong>
                        <p class="p-1 p-comun-disabled">{{ $telefono_referencia ?? '-' }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- Número de referencia --}}
                    <div class="form-group">
                        <strong>Nombre referencia:</strong>
                        <p class="p-1 p-comun-disabled">{{ $nombre_referencia ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer mt-3">
                <x-cancel-button wire:click="cancelarPerfil">CANCELAR</x-cancel-button>
                <x-save-button onclick="actualizarPerfil()">GUARDAR</x-save-button>
            </div>
        </div>
    </div>
</div>

<script>
    function actualizarPerfil() {
        Swal.fire({
            title: '¿Desea actualizar sus datos?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarPerfil');
            } else {
                Livewire.dispatch('cancelarUnidad');
            }
        });
    }
    document.addEventListener('unidadCreada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente.",
            icon: "success"
        });
    });
    document.addEventListener('unidadCancelada', function() {
        //cancelarUnidad();
    });

    document.addEventListener('imagenInvalida', function() {
        Swal.fire({
            title: "Archivo no válido",
            text: "El archivo seleccionado no es una imagen válida.",
            icon: "error"
        });
    });
</script>
