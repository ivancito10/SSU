<div>
    <x-small-modal id="modalActualizarHorario" labelledby="modalActualizarLabel" title="MODIFICAR HORARIO"
        saveAction="guardarNewHorario()">
        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            <div class="card-body">
                <p style="background-color:#1a5276; color:white;text-align:center;">MODIFICAR HORARIO</p>
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
                            <em class="ml-2"><b>Ingresar y completar los siguientes datos.</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_inicioAsig"> FECHA INICIO:</label>
                        <input class="form-control" type='date' id="fecha_inicioAsig" wire:model=fecha_inicioAsig
                            min="{{ now()->toDateString() }}">
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_finAsig"> FECHA FIN:</label>
                        <input class="form-control" type='date' id="fecha_finAsig" wire:model=fecha_finAsig
                            min="{{ now()->toDateString() }}">
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="id_consultorioAsig"> CONSULTORIO:</label>
                        <select wire:model="id_consultorioAsig" id="id_consultorioAsig" class="form-control"
                            style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                            <option selected="selected">Seleccione el consultorio a registrar.</option>
                            @foreach ($consultorio as $dato)
                                <option value="{{ $dato->id }}">Consul. Nro: {{ $dato->numero_consultorio }} -Sede:
                                    {{ $dato->ubicacion }} - {{ $dato->piso }}</option>
                            @endforeach

                        </select>
                        @error('dia')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Este campo es obligatorio.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror
                        <br>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="id_horarioAsig"> HORARIO:</label>
                        <select wire:model="id_horarioAsig" id="id_horarioAsig" name="id_horarioAsig"
                            class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                            <option selected="selected">Seleccione el horario a registrar.</option>
                            @foreach ($horario as $dato)
                                <option value="{{ $dato->id }}">( HORA: {{ $dato->hora_inicio }} a
                                    {{ $dato->hora_fin }} ) ( INTERVALO:
                                    {{ $dato->intervalo }} min.) ( #PACIENTES: {{ $dato->numero_paciente }} )</option>
                            @endforeach

                        </select>
                        @error('horario')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Seleccione el horario.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror
                        <br>
                    </div>

                </div>
            </div>

        </div>
    </x-small-modal>

</div>

<script>
    window.addEventListener('modalModificarHorario', event => {
        $('#modalActualizarHorario').modal('show');
        // Para scrooll del primer modal 
        $('#modalAsignacionHorario').modal('toggle'); // Nivel base del primer modal
    });

    // GUARDAR NUEVO HORARIO
    function guardarNewHorario() {
        Swal.fire({
            title: '¿Actualizar horario?',
            text: "Se actualizarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarActualizarHorario');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
            //$('#modalAsignacionHorario').modal('hide');
        });
    }

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: 'Actualizado!',
            text: "Se registro con éxito.",
            icon: 'success'
        });
        //$('#modalModificarHorario').modal('hide');
    });
</script>
