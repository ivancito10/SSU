<div>
    <x-big-component id="modalInstitucionBenef" labelledby="modalInstitucionBenefLabel"
        title="AGREGAR INSTITUCIÓN DE TRANSFERENCIA" saveAction="guardarNuevaInstitucionBen()">
        <div class="p-2 ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg> <em class="ml-2 h5" style="color:#2471a3"><b>Ingrese los datos
                            institucionales de tranferencia del beneficiario:</b> </em></p>
            </div>
            <div class="row">
                <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                    <label for="id_institucion_remu">INSTITUCIÓN: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <select wire:model="id_institucion_remu" name="id_institucion_remu" id="id_institucion_remu"
                        class="form-control " style=" border-radius:10px;" tabindex="-1" required>
                        <option selected="selected">Selecciona la Institucion</option>
                        @foreach ($institucionSelect as $institucion)
                            <option value="{{ $institucion->id }}">{{ $institucion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="fecha_ingreso_remu"> FECHA DE INGRESO: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <input class="form-control " type="date" id="fecha_ingreso_remu" style=" border-radius:10px;"
                        wire:model=fecha_ingreso_remu>

                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <label for="cargoRemu"> CARGO: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <input class="form-control  " id="cargoRemu" style=" border-radius:10px;text-transform: uppercase;"
                        placeholder="Ingrese el cargo del beneficiario.." wire:model=cargoRemu>

                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="salarioRemu"> SALARIO: <spam style="color:#cb4335;"><b>*</b></spam> </label>
                    <input class="form-control " type='numeric' style=" border-radius:10px;"
                        placeholder="Ingrese el salario del beneficiario.." id="salarioRemu" wire:model=salarioRemu>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="numero_item_remu">ITEM: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <input class="form-control" id="numero_item_remu" style=" border-radius:10px;"
                        placeholder="Ingrese número item.." wire:model=numero_item_remu>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="carga_horaria_remu">CARGA HORARIA: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <select wire:model="carga_horaria_remu" name="carga_horaria_remu" id="carga_horaria_remu"
                        class="form-control " style=" border-radius:10px;" tabindex="-1" required>
                        <option value="" selected>Seleccione carga horaria</option>
                        <option value="160 Hrs.">160 Hrs.</option>
                        <option value="128 Hrs.">128 Hrs.</option>
                        <option value="114 Hrs.">114 Hrs.</option>
                        <option value="112 Hrs.">112 Hrs.</option>
                        <option value="96 Hrs.">96 Hrs.</option>
                        <option value="80 Hrs.">80 Hrs.</option>
                        <option value="64 Hrs.">64 Hrs.</option>
                        <option value="48 Hrs.">48 Hrs.</option>
                        <option value="32 Hrs.">32 Hrs.</option>
                        <option value="16 Hrs.">16 Hrs.</option>
                        <option value="POR ACTUALIZAR">POR ACTUALIZAR</option>
                        <option value="TC">TC</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="titularidad_remu">TITULARIDAD: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <select wire:model="titularidad_remu" name="titularidad_remu" id="titularidad_remu"
                        class="form-control  " style=" border-radius:10px;" tabindex="-1" required>
                        <option value="" selected>Seleccione titularidad</option>
                        <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                        <option value="CONTRATADO">CONTRATADO</option>
                        <option value="EVENTUAL">EVENTUAL</option>
                        <option value="INTERINO">INTERINO</option>
                         <option value="INVITADO">INVITADO</option>
                        <option value="NINGUNO">NINGUNO</option>
                        <option value="TITULAR A">TITULAR A</option>
                        <option value="TITULAR B">TITULAR B</option>
                        <option value="TITULAR C">TITULAR C</option>
                        <option value="TITULAR EMERITO">TITULAR EMERITO</option>
                    </select>
                </div>

            </div>
        </div>
    </x-big-component>
</div>
<script>
    window.addEventListener('modalAgregarInstitucionBeneficiario', event => {
        $('#modalInstitucionBenef').modal('show');
    });

    function guardarNuevaInstitucionBen() {
        Swal.fire({
            title: 'Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('agregarInstitucionBeneficiario');
                $('#modalInstitucionBenef').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                @this.set('modal', true);
                $('#modalInstitucionBenef').modal('hide');
            }
        });
    }

    document.addEventListener('nuevaInstitucionAgregadaBen', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente",
            icon: "success",
            showConfirmButton: false,
            timer: 2000,
        });
    });
    document.addEventListener('nuevaInstitucionNoAgregadaBen', function() {
        Swal.fire({
            title: "Completa todos los datos!",
            text: "Se cancelo el registro",
            icon: "error",
            showConfirmButton: false,
            timer: 2000,
        });
    });

    document.addEventListener('faltanDAtosRemu', function() {
        Swal.fire({
            title: "Agrega todos los campos",
            text: "Porfavor verifica que todos los campos requeridos esten ingresados",
            icon: "error",
            showConfirmButton: false,
            timer: 2000,
        });
    });

    document.addEventListener('yaTieneRemu', function() {
        Swal.fire({
            title: "El beneficiario ya tiene una Institución",
            text: "El beneficiario seleccionado, ya tiene una institución agregada",
            icon: "error",
            showConfirmButton: false,
            timer: 5000,
        });
    });
</script>
