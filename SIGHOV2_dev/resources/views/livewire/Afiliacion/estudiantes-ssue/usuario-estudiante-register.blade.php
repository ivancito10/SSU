<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                class="bi bi-person-fill" viewBox="0 0 16 16" style="color:white">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REGISTRO DE
                USUARIOS ESTUDIANTES </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-10">
                                    <p style="background-color:#c4dff3 ;text-align:center;border-radius:6px">
                                        <b>BUSQUEDA DE
                                            ESTUDIANTE</b>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 pt-2 pb-2">
                                    <input class="form-control" type="search" id="search"
                                        style=" padding: 5px; border-radius:8px"
                                        placeholder="Ingrese el Carnet de Identidad" wire:model="ci_b">
                                </div>
                                <div class="col-lg-3 pt-2 pb-2">
                                    <input class="form-control" type="search" id="complemento"
                                        style=" padding: 5px; border-radius:8px"
                                        oninput="this.value = this.value.toUpperCase()" placeholder=" Complemento ..."
                                        wire:model="complemento_b">
                                </div>
                                <div class="col-lg-2 pt-2 pb-2">
                                    <button class="btn btn-primary" type="submit" wire:click="BuscarPersona">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path
                                                d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg>
                                    </button>
                                </div>
                                <div wire:loading wire:target="BuscarPersona">
                                    <div class="spinner-grow text-warning" role="status">
                                    </div>
                                    <b><i>Cargando datos espere porfavor...</i></b>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-10">
                                    @if ($this->encontrado === 1)
                                        <div class="alert alert-success alert-dismissible fade show p-2" role="alert">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                            </svg>
                                            <strong> SE VALIDÓ
                                                CORRECTAMENTE. </strong>
                                            </button>
                                        </div>
                                    @endif
                                    @if ($this->encontrado === 2)
                                        <div class="alert alert-success alert-dismissible fade show p-2" role="alert">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                            </svg>
                                            <strong>SE VALIDÓ
                                                CORRECTAMENTE A TRAVÉS DEL SEGIP.</strong>
                                            </button>
                                        </div>
                                    @endif
                                    @if ($this->encontrado === 0)
                                        <div class="alert alert-warning alert-dismissible fade show p-2" role="alert">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-exclamation-triangle-fill"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                            </svg>
                                            <strong> NO SE VALIDARON LOS DATOS EN EL SEGIP.
                                            </strong>
                                            <p>Ingrese manualmente los datos, con cuidado.</p>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-right">
                            @if ($this->validacion_usuario != 1)
                                <div class="col-lg-12  ">
                                    <br>
                                    <br>
                                    <table class="table table-hover" style="background-color:#f2f4f7">
                                        <thead>
                                            <th>ROL ASIGNADO: </th>
                                        </thead>
                                        <tr>
                                            <td>
                                                @foreach ($this->roles as $role)
                                                    <div>
                                                        <input type="checkbox" disabled id="role_{{ $role->name }}"
                                                            name="roles[]" wire:model="roles_select"
                                                            value="{{ $role->name }}">
                                                        <label
                                                            for="role_{{ $role->name }}">{{ $role->name }}</label>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                            height="18" fill="currentColor"
                                                            class="bi bi-bookmark-check-fill" viewBox="0 0 16 16"
                                                            style="color: green;">
                                                            <path fill-rule="evenodd"
                                                                d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5m8.854-9.646a.5.5 0 0 0-.708-.708L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                                        </svg>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($this->encontrado === 1 || $this->encontrado === 2)
                @if ($this->validacion_usuario != 1)
                    <div class="row">
                        <div class="col-lg-11 ">
                            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los
                                        siguientes datos para el registro:</b>
                                </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <label for="formFile" class="form-label">SELECCIONAR FOTO:
                            </label>
                            <input style=" border-radius:10px;" class="form-control form-control-sm " type="file"
                                id="formFile" name="archivo" required wire:model.live="foto"
                                accept=".jpg,.jpeg,.png">
                            @error('foto')
                                <span class="form-text text-danger">Introduzca la foto.</span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                            @if ($foto)
                                <img src="{{ $foto->temporaryUrl() }}"
                                    style=" box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.5); width: 200px; height: 200px; object-fit: cover;"
                                    class="img-thumbnail">
                            @endif
                        </div>
                    </div>


                    @include('livewire.Afiliacion.estudiantes-ssue.DatosRegistroUsuario')
                @endif
            @elseif($this->encontrado === 0)
                <div class="row">
                    <div class="col-lg-11 ">
                        <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los
                                    siguientes datos para el registro:</b>
                            </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="formFile" class="form-label">SELECCIONAR FOTO: </label>
                        <input class="form-control @error('foto') is-invalid @enderror" type="file"
                            style="border-radius:10px;" id="formFile" name="archivo" required
                            wire:model.live="foto" accept=".jpg,.jpeg,.png">

                        @error('foto')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>

                    <div class="col-lg-6">
                        <div wire:loading wire:target="foto">
                            <x-cargando-dos />
                        </div>

                        @if ($foto)
                            @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                <img src="{{ $foto->temporaryUrl() }}"
                                    style="box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.5);
                                    width: 200px;
                                    height: 200px;
                                    object-fit: cover;"
                                    class="img-thumbnail">
                            @endif
                        @endif
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-lg-6">
                        <label for="formFile" class="form-label">SELECCIONAR FOTO: </label>
                        <input class="form-control @error('foto') is-invalid @enderror" type="file"
                            style=" border-radius:10px;" id="formFile" name="archivo" required wire:model.live="foto"
                            accept=".jpg,.jpeg,.png">
                        @error('foto')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                        @if ($foto)
                            <img src="{{ $foto->temporaryUrl() }}"
                                style=" box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.5); width: 200px; height: 200px; object-fit: cover;"
                                class="img-thumbnail">
                        @endif
                    </div>
                </div> --}}

                @include('livewire.Afiliacion.estudiantes-ssue.DatosRegistroUsuarioManual')

            @endif


        </div>
    </div>
    <br>
    @if ($this->validacion_usuario != 1)
        <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
            <div id="cargando" style="display: none;">
                <x-cargando-dos />
            </div>
            <x-cancel-button wire:click="cancel">
                CANCELAR</x-cancel-button>
            <x-save-button onclick="saveUsuario()">GUARDAR</x-save-button>
        </div>
    @endif
    <script>
        function saveUsuario() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarUsuario');
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }
        document.addEventListener('ErrorYaExisteUsuario', function() {
            Swal.fire({
                title: 'Alerta!',
                text: "Ya existe un usuario asignado a la persona!!!",
                icon: 'error'
            });
        });

        document.addEventListener('error_fecha_nacimiento', function() {
            Swal.fire({
                title: 'Alerta!',
                text: "Error en la fecha de nacimiento!!!",
                icon: 'error'
            });
        });

        document.addEventListener('ingreseDatosPorfavor', function() {
            Swal.fire({
                title: 'Porfavor ingrese datos correctos',
                text: "Recuerde ingresar datos ara realizar la busqueda",
                icon: 'error'
            });
        });
    </script>
</div>
