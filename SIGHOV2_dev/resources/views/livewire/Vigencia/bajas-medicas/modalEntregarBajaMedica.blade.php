<div wire:ignore>
    <x-small-modal id="modalEntregar" labelledby="modalEntregarLabel" title="  BAJA MÉDICA A ENTREGAR "
        saveAction="guardarEntrega()">
        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            <p style="background-color:#1a5276; color:white;text-align:center;">
                ASEGURADO</p>
            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="nombreCompleto"> NOMBRE COMPLETO:</label>
                        <input id ="nombreCompleto" class="form-control" type="text" wire:model='nombreCompleto'
                            disabled style=" height: 30px;">
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="matricula" style="color:#212f3c ;"> MATRICULA:</label>
                        <input id ="matricula" class="form-control" type="text" wire:model='matricula' disabled
                            style=" height: 30px;">
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                        <label for="tipo_asegurado" style="color:#21618c;"> TIPO:</label>
                        <input id ="tipo_asegurado" class="form-control" type="text" wire:model='tipo_asegurado'
                            disabled style=" height: 30px;">
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            <p style="background-color:#1a5276; color:white;text-align:center;">
                AGREGAR PERSONA ENCARGADA</p>
            <div class="card-body">
                <!-- <div class="row">
                        <div class="col-lg-12" style="text-align:center;" style="background-color:#e67e22 ">
                            <p style="background-color:#ebf5fb  ; color:#273746 ; ">
                            <b>REGISTRAR PERSONA ENCARGADA</b></p>
                        </div>
                    </div> -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2"><b>Introduzca ambos datos si es necesario.</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>
                <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                <div class="row">
                    <div class="col-lg-12" style="display: flex; align-items: center;">
                        <!-- Aquí va el botón con icono de lupa -->

                        <button class="btn btn-primary" type="submit" wire:click="search_persona">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>

                        <input class="form-control " type="search" id="search"
                            style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Ingrese el carnet"
                            wire:model="carnet">
                        <input class="form-control" type="search" id="complemento"
                            style="width: calc(100% - 20px); margin-left: 5px;" placeholder=" Ingrese complemento"
                            wire:model="complemento_m">
                    </div>

                    <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                        <div class='row'>
                            <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->

                            <div class="col-lg-12 " role="alert">
                                @if ($estado_api == '')
                                @elseif ($estado_api == 1)
                                    <div class="alert alert-success"> Se validó
                                        correctamente!</div>
                                @elseif ($estado_api == 0)
                                    <div class="alert alert-danger">No se validó!</div>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
                <!--DATOS DE LA NUEVA PERSONA-->
                @if ($estado_api == 1)
                    <!--AQUI SE MUESTRAN LOS DATOS RECUPERADOS DEL ASUSS-->
                    <br>
                    <div class="row">
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <br>
                            <label for="nombre">NOMBRE: </label>
                            <input type="text" class="form-control" disabled wire:model.live="nombre" name="nombre"
                                id="nombre" style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_paterno">AP. PATERNO: </label>
                            <input type="text" class="form-control" disabled wire:model.live="ap_paterno"
                                name="ap_paterno" id="ap_paterno" style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_materno">AP. MATERNO:</label>
                            <input type="text" class="form-control" disabled wire:model.live="ap_materno"
                                name="ap_materno" id="ap_materno" style=" height: 30px; background-color: #f8fcff;">

                        </div>

                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA NAC. </label>
                            <input type="text" class="form-control" disabled wire:model.live="fecha_nacimiento"
                                name="fecha_nacimiento" id="fecha_nacimiento"
                                style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class='col-lg-8 col-md-4 col-sm-6 col-xs-12'>
                            <br>
                            <label for="carnet">CARNET: </label>
                            <input type="number" class="form-control" disabled wire:model.live="carnet"
                                name="carnet" id="carnet" style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <br>
                            <label for="complemento_m">COMP.: </label>
                            <input type="text" class="form-control" disabled wire:model.live="complemento_m"
                                name="complemento_m" id="complemento_m"
                                style=" height: 30px; background-color: #f8fcff;">
                        </div>

                    </div>
                @else
                    <!--REGISTRAR MANUALMENTE-->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <hr>
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2"><b>Debe completar los siguientes datos.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre"> NOMBRE</label>
                            <input class="form-control" id="nombre" placeholder=" Nombre completo" type="text"
                                wire:model=nombre oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="ap_paterno"> AP. PATERNO</label>
                            <input class="form-control" id="ap_paterno" placeholder=" Apellido paterno"
                                type="text" wire:model=ap_paterno oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="ap_materno"> AP. MATERNO</label>
                            <input class="form-control" id="ap_materno" placeholder=" Apellido materno"
                                type="text" wire:model=ap_materno oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA NAC. </label>
                            <input type="date" class="form-control" placeholder=" Fecha Nacimiento"
                                wire:model="fecha_nacimiento" name="fecha_nacimiento" id="fecha_nacimiento">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class='col-lg-8 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ci">CARNET</label>
                            <input type="number" class="form-control @error('ci') is-invalid @enderror"
                                placeholder=" Introduzca el carnet" wire:model="carnet" name="ci"
                                id="ci">

                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="complemento_m">COMP. </label>
                            <input type="text" class="form-control" placeholder=" Complemento"
                                wire:model="complemento_m" name="complemento_m" id="complemento_m"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                @endif
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2"><b> Complete observaciones si es necesario.</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>
                <div class="row">

                    <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                        <label for="observaciones">OBSERVACIONES: </label>
                        <textarea id="observaciones" type="text" class="form-control" placeholder=" Describa sus observaciones"
                            wire:model="observaciones" rows='3' oninput="this.value = this.value.toUpperCase()"></textarea>
                    </div>
                </div>


            </div>

        </div>

    </x-small-modal>
</div>

<script>
    window.addEventListener('modalEntregarBajaMedica', event => {

        $('#modalEntregar').modal('show');
    });

    // EDITAR TITULAR
    function guardarEntrega() {
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
                Livewire.dispatch('guardarEntregaBajaMedica');
                $('#modalEntregar').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                @this.set('modal', true);
                $('#modalEntregar').modal('hide');
            }
        });
    }


    // Aqui se busca el C.I
    /*function buscarDatos() {
        var carnet = document.getElementById('search').value;
        var complemento = document.getElementById('complemento').value;
        console.log('Valor del carnet:', carnet, complemento);
        Livewire.dispatch('search_persona', [carnet, complemento]);
    }*/
</script>
