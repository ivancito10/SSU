<div wire:ignore>
    <x-small-modal id="entregarOrden" labelledby="entregarOrdenLabel" title=" ORDEN DE SERVICIO A ENTREGAR"
        saveAction="guardarEdicionOrden()">

        <div class="row">
            <div class="col-lg-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <p style="background-color:#1a5276; color:white;text-align:center;">
                        AFILIADO</p>
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombreCompleto"> NOMBRE COMPLETO: </label>
                                <input class="form-control" id="nombreCompleto" disabled wire:model=nombreCompleto
                                    style=" height: 30px; ">
                            </div>

                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="matricula" style="color:#212f3c ;"> MATRICULA:</label>
                                <input class="form-control" id="ci" disabled wire:model=matricula
                                    style=" height: 30px; ">
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="tipo_asegurado" style="color:#21618c;"> TIPO:</label>
                                <input class="form-control" id="ci" disabled wire:model=tipo_asegurado
                                    style=" height: 30px; ">
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <p style="background-color:#1a5276; color:white;text-align:center;">
                        AGREGAR PERSONA A ENTREGAR</p>
                    <div class="card-body">

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
                                    <em class="ml-2"><b>Introduzca los datos necesarios para la búsqueda.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div style="display: flex; align-items: center;">
                                    <!-- Aquí va el botón con icono de lupa -->
                                    <button class="btn btn-primary" type="submit" wire:click="buscarDatos">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path
                                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg>
                                    </button>
                                    <input class="form-control" type="search" id="buscarCarnet"
                                        wire:model='buscarCarnet' style="width: calc(100% - 20px); margin-left: 5px;"
                                        placeholder="Ingresa el carnet ">
                                    <input class="form-control" type="search" id="buscarComplemento"
                                        wire:model='buscarComplemento'
                                        style="width: calc(100% - 20px); margin-left: 5px;"
                                        placeholder="Ingrese el Complemento">

                                </div>
                            </div>
                            @if ($encontrado == '')
                            @else
                                <div class="col-lg-12 " role="alert">
                                    @if ($encontrado == 1)
                                        <div class="alert alert-success"> Se validó
                                            correctamente!</div>
                                    @else
                                        <div class="alert alert-danger">No se validó!</div>
                                    @endif
                                </div>
                            @endif
                            <div wire:loading wire:target="buscarDatos">
                                <div class="spinner-grow text-warning" role="status">
                                </div>
                                <b><i>Cargando datos espere porfavor...</i></b>
                            </div>
                        </div>
                        <!-- cuando no se encuentra la persona -->
                        <!-- <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                                    <em   class="ml-2" ><b>Debe completar los siguientes campos.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div> -->
                        <div class="row  mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombresExt"> NOMBRES: </label>
                                <input class="form-control" id="nombresExt" wire:model=nombresExt
                                    @if ($busqueda) disabled @endif placeholder="Ingrese los nombres"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="p_apellidoExt"> AP. PATERNO: </label>
                                <input class="form-control" id="p_apellidoExt" wire:model=p_apellidoExt
                                    @if ($busqueda) disabled @endif
                                    placeholder="Ingrese el primer apellido"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="s_apellidoExt"> AP. MATERNO:</label>
                                <input class="form-control" id="s_apellidoExt" wire:model=s_apellidoExt
                                    @if ($busqueda) disabled @endif
                                    placeholder="Ingrese el segundo apellido"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="row md-2">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="ciExt"> CARNET: </label>
                                <input class="form-control" id="ciExt" wire:model=ciExt
                                    @if ($busqueda) disabled @endif placeholder="Ingrese el carnet ">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="complementoExt"> COMPL.: </label>
                                <input class="form-control" id="complementoExt" wire:model=complementoExt
                                    @if ($busqueda) disabled @endif
                                    placeholder="Ingrese el complemento "
                                    oninput="this.value = this.value.toUpperCase()">
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
                                    <em class="ml-2"><b>Introduzca observaciones si es necesario.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="today"> FECHA ENTREGA: </label>
                                <input class="form-control" id="today" wire:model=today style="height: 30px;"
                                    disabled type="date">
                            </div>
                            <div class="col-lg-12">
                                <br>
                                <label for="observacion"> OBSERVACIONES: </label>
                                <textarea class="form-control" id="observacion" wire:model=observacion rows="3"
                                    placeholder="Ingrese las observaciones" oninput="this.value = this.value.toUpperCase()">
                                </textarea>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-7">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header" style="background-color:#3E6CA7; color:white">
                        <p class='h6'><b>ORDEN DE SERVICIO</b></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                                    <em   class="ml-2" ><b>Introduzca observaciones si es neecsario.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-4">
                                <label for="today"> FECHA ENTREGA: </label>
                                <input class="form-control" id="today" wire:model=today
                                style=" height: 30px;" disabled>
                            </div>
                            <div class="col-lg-12">
                                <br>
                                <label for="observacion"> OBSERVACIONES: </label>
                                <textarea class="form-control" id="observacion" wire:model=observacion rows="3"
                                    placeholder="Ingrese las observaciones" oninput="this.value = this.value.toUpperCase()">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br>
                </div>
            </div> -->
        </div>

    </x-small-modal>

    <script>
        window.addEventListener('modalEntregarOrden', event => {
            $('#entregarOrden').modal('show');
        });
        // EDITAR ORDEN
        function guardarEdicionOrden() {
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
                    Livewire.dispatch('entregarOrdenServicio');
                    $('#entregarOrden').modal('hide');
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                    @this.set('modal', true);
                    $('#entregarOrden').modal('hide');
                }
            });
        }

        document.addEventListener('ordenModificado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente",
                icon: "success"

            });
        });
        document.addEventListener('ordenCancelado', function() {
            Swal.fire({
                title: "Completa todos los datos!",
                text: "Se cancelo el registro",
                icon: "error"

            });
        });
    </script>
</div>
