<div>
    <x-small-modal id="modalEditarControlHorario" labelledby="modalEditarControlHorarioLabel"
        title="MODIFICAR CONTROL DE HORARIO" saveAction="editarControlHorioos()">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos:</b> </em>
                <spam style="color:#cb4335;"><b>*</b></spam>
            </p>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 ">
                <label for="posicion"> POSICIÓN: <b style="color:#cb4335;">*</b></label>
                <input id="posicion" class="form-control" type="number" wire:model='posicion'
                    style="height: 40px; border-radius:8px">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 ">
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 ">
                <label for="porcentaje"> PORCENTAJE %: <b style="color:#cb4335;">*</b></label>
                <input id="porcentaje" class="form-control" type="number" min="0" max="100"
                    wire:model='porcentaje' style="height: 40px; border-radius:8px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                <label for="hora_inicio"> HORA INICIO: <b style="color:#cb4335;">*</b></label>
                <input id="hora_inicio" class="form-control" type="time" wire:model='hora_inicio'
                    style="height: 40px; border-radius:8px">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                <label for="hora_fin"> HORA FIN: <b style="color:#cb4335;">*</b></label>
                <input id="hora_fin" class="form-control" type="time" wire:model='hora_fin'
                    style="height: 40px; border-radius:8px">
            </div>
        </div>

    </x-small-modal>
</div>
<script>
    document.addEventListener('abrirmodalEditarControlHorarios', function() {
        $('#modalEditarControlHorario').modal('show');
    });

    function editarControlHorioos() {
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
                Livewire.dispatch('editandoControlHorario');
                // $('#modalRegisterFeriado').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalEditarControlHorario').modal('hide');
            }
        });
    }

    // para cuando se registre con exito 
    document.addEventListener('controlHorarioEditado', function() {
        Swal.fire({
            title: "Creado correctamente",
            text: "El control de horarios fue modificado con exito.",
            icon: "success"
        });
        $('#modalEditarControlHorario').modal('hide');
    });
    // para cuando se registre con exito 
    document.addEventListener('ingresaDatosControlHorarioEditar', function() {
        Swal.fire({
            title: "Faltan datos",
            text: "Porfavor ingrese todos los datos necesarios para completar el registro.",
            icon: "warning"
        });
        $('#modalEditarControlHorario').modal('hide');
    });
    // para ya existe el horario 
    document.addEventListener('controlHorarioExisteEditar', function() {
        Swal.fire({
            title: "El dato ya existe",
            text: "Porfavor ingrese otros datos, para completar el registro.",
            icon: "warning"
        });
        $('#modalEditarControlHorario').modal('hide');
    });
    // error con catch
    document.addEventListener('errorCatchControlHorarioEditar', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "Intenta nuevamente o comunicate con la unidad de sistemas",
            icon: "error"
        });
        $('#modalEditarControlHorario').modal('hide');
    });
    // error con catch
    document.addEventListener('finMenorInicioEditar', function() {
        Swal.fire({
            title: "La hora de inicio es mayor que la hora fin",
            text: "Intenta ingresar una hora fin mayor a la hora de inicio.",
            icon: "error"
        });
        $('#modalEditarControlHorario').modal('hide');
    });
    // mayor que 100
    document.addEventListener('mayor100Editar', function() {
        Swal.fire({
            title: "El porcentaje no puede ser mayor que 100 %",
            text: "Intenta ingresar un porcentaje diferente",
            icon: "error"
        });
        $('#modalEditarControlHorario').modal('hide');
    });
</script>
