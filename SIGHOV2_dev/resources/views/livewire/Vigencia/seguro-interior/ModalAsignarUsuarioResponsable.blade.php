<x-small-modal id="AsignarModalSeguro" labelledby="AsignarModalSeguroLabel" title="MODIFICAR USUARIO RESPONSABLE"
    saveAction="guardarAsignacion()">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                    <p style="background-color:#1a5276; color:white;">SEGURO DEL INTERIOR</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nombre_seguro"> INSTITUCIÓN:</label>
                                    <input id="nombre_seguro" class="form-control" disabled wire:model="nombre_seguro"
                                        style=" height: 30px;">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nro_empleador"> NUM. DE EMPLEADOR:</label>
                                    <input id="nro_empleador" class="form-control" disabled wire:model="nro_empleador"
                                        style=" height: 30px;">
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nit"> NIT:</label>
                                    <input id="nit" class="form-control" disabled wire:model="nit"
                                        style=" height: 30px;">
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="sigla_seguro"> ACRÓNIMO:</label>
                                    <input id="sigla_seguro" class="form-control" disabled wire:model="sigla_seguro"
                                        style=" height: 30px;">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-lg-12">
                    <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                    <p style="background-color:#1a5276; color:white;">USUARIO ENCARGADO</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p style="color:#2471a3; text-decoration:underline;">
                                        USUARIO ACTUAL
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    <label for="usuarioResponsable">USUARIO:</label>
                                    <input class="form-control" id="usuarioResponsable" disabled
                                        wire:model="usuarioResponsable" style=" height: 30px;">
                                </div>
                                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                    <label for="carnetResponsable"> CARNET:</label>
                                    <input class="form-control" id="carnetResponsable" disabled
                                        wire:model="carnetResponsable" style=" height: 30px;">
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nombreCompletoResponsable"> NOMBRE COMPLETO:</label>
                                    <input class="form-control" id="nombreCompletoResponsable" disabled
                                        wire:model="nombreCompletoResponsable" style=" height: 30px;">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <p style="color:#2471a3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                        <em class="ml-2"><b>Ingrese los campos para la búsqueda.</b> </em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </p>
                                </div>

                            </div>

                            <div class="row">
                                <!-- Aquí va el botón con icono de lupa -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                    style="display: flex; align-items: center;">
                                    <br>
                                    <button class="btn btn-primary" type="submit" wire:click="searchPersonaUsuario">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path
                                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg>
                                    </button>
                                    <input class="form-control" type="search" wire:model="ciUsuarioBusqueda"
                                        style="width: calc(100% - 20px); margin-left: 5px; background-color:white;"
                                        placeholder="Ingresa el Carnet de identidad">

                                    <input class="form-control" type="search"
                                        wire:model="complementoUsuarioBusqueda"
                                        style="width: calc(100% - 20px); margin-left: 5px; background-color:white;"
                                        placeholder="Ingresa el complemento">

                                </div>

                                <div wire:loading>
                                    <div class="spinner-grow text-warning" role="status">
                                    </div>
                                    <b><i>Cargando datos espere porfavor...</i></b>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</x-small-modal>
<script>
    window.addEventListener('modalAsignarUsuarioResponsable', event => {

        $('#AsignarModalSeguro').modal('show');
    });
    // EDITAR TITULAR

    document.addEventListener('NoExisteLaPersona', function() {

        Swal.fire({
            title: "<b>Error</b>",
            text: "La persona no existe en nuestros registros!!!",
            icon: "error"

        });
    });

    document.addEventListener('noExisteElUsuario', function() {

        Swal.fire({
            title: "<b>Error</b>",
            text: "La persona no tiene un usuario asignado!!!",
            icon: "error"

        });
    });

    function guardarAsignacion() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán la asignación",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('asignarUsuarioSeguroInterior');
                $('#AsignarModalSeguro').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

                $('#AsignarModalSeguro').modal('hide');
            }
        });
    }


    document.addEventListener('asignacionDeUsuarioCorrecta', function() {

        Swal.fire({
            title: "<b>Felicidades</b>",
            text: "Se asignó al usuario exitosamente!!!",
            icon: "success"

        });
        $('#AsignarModalSeguro').modal('hide');
    });

    document.addEventListener('asignacionDeUsuarioError', function() {
        Swal.fire({
            title: "<b>Error</b>",
            text: "Se produjo un error no se pudo asignar al usuario!!!",
            icon: "error"

        });
        $('#AsignarModalSeguro').modal('hide');
    });
</script>
