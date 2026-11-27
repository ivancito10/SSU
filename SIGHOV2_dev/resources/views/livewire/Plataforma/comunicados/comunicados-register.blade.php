<div>
    <x-agregar-button wire:click="abrirModalDeComunicados" title="REGISTRAR COMUNICADO">
        REGISTRAR</x-agregar-button>
    <x-small-modal id="modalRegisterComunicado" labelledby="modalRegisterComunicadolLabel" title="AGREGAR COMUNICADO"
        saveAction="registroComunicado()">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos:</b> </em>
            </p>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="permanente_registrar">DURACIÓN DE COMUNICADO: <b style="color:#cb4335;">*</b></label>
                <div class="col-sm-6 d-flex align-items-center">
                    <label style="color:green ">Temporal</label>
                    <div class="custom-control custom-switch custom-switch-off-success custom-switch-on-blue">
                        <input wire:model="permanente_registrar" name="permanente_registrar" type="checkbox"
                            class="custom-control-input" id="switchPermanente" onchange="toggleFechaCamposRegistrar()">
                        <label class="custom-control-label" for="switchPermanente" style="margin-left: 0.5rem;"></label>
                    </div>
                    <label style="color:#2471a3 ">Permanente</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="fecha_inicio_registrar">FECHA INICIAL : <b style="color:#cb4335;">*</b></label>
                <input id="fecha_inicio_registrar" class="form-control" type="date" wire:model='fecha_inicio'
                    style="height: 40px; border-radius:8px" min="{{ now()->toDateString() }}">
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" id="fecha_fin_div"
                style="display: {{ $permanente_registrar ? 'none' : 'block' }};">
                <label for="fecha_fin_registrar">FECHA FINAL: <b style="color:#cb4335;">*</b></label>
                <input id="fecha_fin_registrar" class="form-control" type="date" wire:model='fecha_fin'
                    style="height: 40px; border-radius:8px" min="{{ now()->toDateString() }}">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="id_unidad">UNIDAD: <b style="color:#cb4335;">*</b> </label>
                <select wire:model="id_unidad" id="id_unidad" class="form-control "
                    style="width: 100%; border-radius:8px;" tabindex="-1" required>
                    <option selected=" ">Seleccione..
                    </option>
                    @foreach ($unidades as $unidad)
                        <option value="{{ $unidad['id'] }}">{{ $unidad['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                <label for="mensaje"> COMUNICADO: </label>
                <div id="mensaje-texto" style="height: 200px; ">
                </div>
            </div>
        </div>
    </x-small-modal>

</div> 

<script>
    document.addEventListener('abrirModalComunicados', function() {
        $('#modalRegisterComunicado').modal('show');
    });

    // para el texto enriquecido:
    let quill;
    document.addEventListener('DOMContentLoaded', function() {
        if (!quill) {
            quill = new Quill('#mensaje-texto', {
                theme: 'snow',
                placeholder: 'Introduzca mensaje',
            });
        } else {
            quill.setText('');
            quill = new Quill('#mensaje-texto', {
                theme: 'snow',
                placeholder: 'Introduzca mensaje',
            });
        }
    });

    function registroComunicado() {
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
                Livewire.dispatch('registrarComunicado', {
                    mensaje: quill.getSemanticHTML()
                });
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalRegisterComunicado').modal('hide');
            }
        });
    }

    // para registro exitoso
    document.addEventListener('comunicadoRegistrado', function() {
        Swal.fire({
            title: "Registro satisfactorio",
            text: "El comunicado fue creado con exito",
            icon: "success"
        });
        $('#modalRegisterComunicado').modal('hide');
    });
    // para cuando se olvidan algunso datos 
    document.addEventListener('sinnDAtos', function() {
        Swal.fire({
            title: "Faltan datos",
            text: "Porfavor ingrese todos los datos necesarios para registrar el comunicado.",
            icon: "error"
        });
        $('#modalRegisterComunicado').modal('hide');
    });
    //PARA EL SWITCH PERMANTE
    function toggleFechaCamposRegistrar() {
        var isPermanente = document.getElementById('switchPermanente').checked;
        var fechaFinDiv = document.getElementById('fecha_fin_div');
        console.log("isPermanente: ", isPermanente);
        if (isPermanente) {
            fechaFinDiv.style.display = 'none';
        } else {
            fechaFinDiv.style.display = 'block';
        }
    }
    window.onload = function() {
        var isPermanente = {{ $permanente_registrar ? 'true' : 'false' }};
        document.getElementById('switchPermanente').checked = isPermanente;
        toggleFechaCamposRegistrar();
    }

    // peramnete, falta la fecha de inicio
    document.addEventListener('permanenteSinFechaIni', function() {
        Swal.fire({
            title: "Agregue la fecha inicial",
            text: "El comunicado es permanete, pero debe tener una fecha inicial.",
            icon: "error"
        });
        $('#modalRegisterComunicado').modal('hide');
    });
    // termporaal , agregar ambas fechas
    document.addEventListener('temporalSinFechas', function() {
        Swal.fire({
            title: "Agregue las fechas correspondientes",
            text: "El comunicado es temporal, porfavor agregue la fecha inicial y final.",
            icon: "error"
        });
        $('#modalRegisterComunicado').modal('hide');
    });
    // para error
    document.addEventListener('NoRegistrado', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "No se pudo completar el registro. Intente nuevamente o comuníquese con la Unidad de Sistemas si el problema persiste.",
            icon: "error"
        });
        $('#modalRegisterComunicado').modal('hide');
    });
    // para quela FechaInicial sea menor que la final
    document.addEventListener('fechaFinalMenor', function() {
        Swal.fire({
            title: "Error en la fecha FINAL",
            text: "La fecha FINAL no puede ser menor a la fecha INICIAL, porfavor ingrese otra fecha.",
            icon: "error"
        });
        $('#modalRegisterComunicado').modal('hide');
    });
</script>
