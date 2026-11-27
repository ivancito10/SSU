<div wire:ignore>
    <x-agregar-button wire:click="abrirModalDeHorario" title="REGISTRAR HORARIOS">
        REGISTRAR</x-agregar-button>

    <!-- Modal para registrar horario -->
    <x-small-modal id="modalRegistroHorario" labelledby="modalRegistroHorariolLabel" title="AGREGAR HORARIO"
        saveAction="registroHorario()">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <p style="color:#2471a3" class="h5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em class="ml-2"><b>Ingrese y complete los siguientes datos:</b> </em>

                </p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="hora_inicio"> HORA INICIO: <b style="color:#cb4335;">*</b></label>
                <input class="form-control" id="hora_inicio" type="time" wire:model=hora_inicio
                    wire:change="asignarTurno" wire:change="numeroPacientes" style="border-radius:8px;">
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="hora_fin"> HORA FIN: <b style="color:#cb4335;">*</b></label>
                <input class="form-control" id="hora_fin" type="time" wire:model=hora_fin
                    wire:change="numeroPacientes" style="border-radius:8px;">
                <!-- Mostrar el error de hora_fin si existe -->
                @if (session()->has('error'))
                    <div class="text-danger mt-1">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="intervaloReg">INTERVALO: <b style="color:#cb4335;">*</b></label>
                <input class="form-control" id="intervaloReg" type="number" placeholder=" Ej: 15"
                    wire:model="intervalo" wire:change="numeroPacientes" style="border-radius:8px;" min="0">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="turnoReg"> TURNO:</label>
                <input class="form-control" id="turnoReg" readonly wire:model=turno style="border-radius:8px;">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="numero_paciente_reg"> N° DE PACIENTES:</label>
                <input class="form-control" id="numero_paciente_reg" type="numeric" readonly wire:model=numero_paciente
                    style="border-radius:8px;">
            </div>
        </div>


    </x-small-modal>
</div>


<script>
    // esto es para abrir el modal 
    document.addEventListener('abrirModalHorario', function() {
        $('#modalRegistroHorario').modal('show');
    });

    //registrar HORARIO
    function registroHorario() {
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
                Livewire.dispatch('registrarHorario');
                $('#modalRegistroHorario').modal('hide');
            } else {
                //Livewire.dispatch('resetearDatos');
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

                $('#modalRegistroHorario').modal('hide');
            }
        });
    }

    document.addEventListener('horarioEditado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se editó correctamente",
            icon: "success"

        });
    });

    document.addEventListener('horarioNoRegistrado', function() {
        Swal.fire({
            title: "Completa todos los datos!",
            text: "Se cancelo el registro",
            icon: "error"

        });
    });
    // esto es por si existe el horario
    document.addEventListener('horarioExiste', function() {
        Swal.fire({
            title: "El horario ya fue registrado!",
            text: "Ingresa otros datos",
            icon: "error"

        });
    });
    // esto es por si existe el horario
    document.addEventListener('horarioMayor', function() {
        Swal.fire({
            title: "Verifica los horarios",
            text: "El horario de INICIO debe ser MENOR al horario FIN",
            icon: "error"

        });
    });
</script>
