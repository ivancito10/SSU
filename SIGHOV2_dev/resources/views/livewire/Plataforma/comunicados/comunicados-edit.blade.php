<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                class="bi bi-person-fill" viewBox="0 0 16 16" style="color:white">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">EDICIÓN
                DE DATOS
                DE ASEGURADO</h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="atras" wire:loading.attr="disabled" wire:target="atras">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="atras">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <div class="d-flex flex-wrap align-items-center">
                <label for="permanente" class="pr-3">DURACIÓN DE COMUNICADO: </label>
                <label style="color:green ">Temporal</label>
                <div class="custom-control custom-switch custom-switch-off-success custom-switch-on-blue">
                    <input wire:model="permanente" name="permanente" type="checkbox" class="custom-control-input"
                        id="switchPermanenteEditar" onchange="toggleFechaCampos()">
                    <label class="custom-control-label" for="switchPermanenteEditar"
                        style="margin-left: 0.5rem;"></label>
                </div>
                <label style="color:#2471a3 ">Permanente</label>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <label for="fecha_inicio">FECHA INICIAL : <b style="color:#cb4335;">*</b></label>
            <input id="fecha_inicio" class="form-control" type="date" wire:model='fecha_inicio'
                style="height: 40px; border-radius:8px" min="{{ now()->toDateString() }}">
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" id="fecha_fin_editar"
            style="display: {{ $permanente ? 'none' : 'block' }};">
            <label for="fecha_fin">FECHA FINAL: <b style="color:#cb4335;">*</b></label>
            <input id="fecha_fin" class="form-control" type="date" wire:model='fecha_fin'
                style="height: 40px; border-radius:8px" min="{{ now()->toDateString() }}">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="id_unidad">UNIDAD: <b style="color:#cb4335;">*</b> </label>
            <select wire:model="id_unidad" id="id_unidad" class="form-control " style="width: 100%; border-radius:8px;"
                tabindex="-1" required>
                @foreach ($unidades as $unidad)
                    <option value="{{ $unidad['id'] }}">{{ $unidad['nombre'] }}</option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:ignore>
        <label for="mensaje" style="display: block; text-align: left;">COMUNICADO:</label>
        <!-- <textarea class="form-control" id="mensaje" wire:model='mensaje' class="swal2-input" rows='4'
            oninput="this.value = this.value.toUpperCase()"></textarea> -->

        <div id="mensaje" style="height: 200px;">
            {!! $mensaje !!}
        </div>
    </div>
    <br>
    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cancelarEdicionComunicado">
            CANCELAR</x-cancel-button>
        <x-save-button onclick="edicionComunicado()">GUARDAR</x-save-button>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</div>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // Para el texto enriquecido
    let quill1;

    document.addEventListener('DOMContentLoaded', function() {
        if (!quill1) {
            quill1 = new Quill('#mensaje', {
                theme: 'snow'
            });
        } else {
            quill1.setText('');
            quill1 = new Quill('#mensaje', {
                theme: 'snow'
            });
        }
    });
    // funcion principal
    function edicionComunicado() {
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
                document.getElementById('cargando').style.display = 'block';
                Livewire.dispatch('editandoComunicado', {
                    mensajeEditado: quill1.getSemanticHTML()
                });
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }

    // para registro exitoso
    document.addEventListener('comunicadoEditado', function() {
        Swal.fire({
            title: "Registro satisfactorio",
            text: "El comunicado fue editado con exito",
            icon: "success"
        });
        $('#modalEditarComunicado').modal('hide');
    });
    // para cuando se olvidan algunso datos 
    document.addEventListener('sinnDAtos', function() {
        Swal.fire({
            title: "Faltan datos",
            text: "Porfavor ingrese todos los datos necesarios para registrar el comunicado.",
            icon: "error"
        });
        $('#modalEditarComunicado').modal('hide');
    });
    //PARA EL SWITCH PERMANTE
    function toggleFechaCampos() {
        var isPermanente = document.getElementById('switchPermanenteEditar').checked;
        var fechaFinDiv = document.getElementById('fecha_fin_editar');
        if (isPermanente) {
            fechaFinDiv.style.display = 'none';
        } else {
            fechaFinDiv.style.display = 'block';
        }
    }
    window.onload = function() {
        var isPermanente = {{ $permanente ? 'true' : 'false' }};
        document.getElementById('switchPermanenteEditar').checked = isPermanente;
        toggleFechaCampos();
    }

    // peramnete, falta la fecha de inicio
    document.addEventListener('permanenteSinFechaIni', function() {
        Swal.fire({
            title: "Agregue la fecha inicial",
            text: "El comunicado es permanete, pero debe tener una fecha inicial.",
            icon: "error"
        });
    });
    // termporaal , agregar ambas fechas
    document.addEventListener('temporalSinFechas', function() {
        Swal.fire({
            title: "Agregue las fechas correspondientes",
            text: "El comunicado es temporal, porfavor agregue la fecha inicial y final.",
            icon: "error"
        });
    });
    // para error
    document.addEventListener('NoRegistrado', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "No se pudo completar el registro. Intente nuevamente o comuníquese con la Unidad de Sistemas si el problema persiste.",
            icon: "error"
        });
    });
    // para quela FechaInicial sea menor que la final
    document.addEventListener('fechaFinalMenor', function() {
        Swal.fire({
            title: "Error en la fecha FINAL",
            text: "La fecha FINAL no puede ser menor a la fecha INICIAL, porfavor ingrese otra fecha.",
            icon: "error"
        });
    });
    // es  permanete, borrar la fecha final
    document.addEventListener('borrarFechaFin', function() {
        Swal.fire({
            title: "Es permanente",
            text: "El comunicado es permanete, no puede tener una fecha FINAL.",
            icon: "error"
        });
    });
</script>
