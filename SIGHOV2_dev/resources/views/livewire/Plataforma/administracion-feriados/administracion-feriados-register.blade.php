<div wire:ignore>
    <x-agregar-button wire:click="abrirModalDeFeriados" title="REGISTRAR FERIADOS">
        REGISTRAR</x-agregar-button>
    <x-small-modal id="modalRegisterFeriado" labelledby="modalRegisterFeriadolLabel" title="AGREGAR FERIADO"
        saveAction="registroFeriado()">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
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
                <label for="fecha">FECHA:</label>
                <input class="form-control" id="fecha" wire:model=fecha wire:change="obtenerNombreDia()"
                    type="date" style="border-radius:10px">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                <label for="dia">DÍA:</label>
                <input class="form-control" id="dia" wire:model=dia disabled type="text"
                    style="border-radius:10px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="descripcion">DESCRIPCIÓN:</label>
                <textarea id="descripcion" wire:model="descripcion" class="form-control swal2-input"
                    style="border-radius:8px; text-transform: uppercase;" rows="4"></textarea>
            </div>
        </div>
    </x-small-modal>

</div>
<script>
    document.addEventListener('abrirModalFeriados', function() {
        $('#modalRegisterFeriado').modal('show');
    });

    function registroFeriado() {
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
                Livewire.dispatch('crearFeriado');
                // $('#modalRegisterFeriado').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalRegisterFeriado').modal('hide');
            }
        });
    }
    // para el texto enriquecido
    //  let var1;
    //  document.addEventListener('DOMContentLoaded', function() {
    //      if (!var1) {
    //          var1 = new Quill('#descripcion', {
    //              theme: 'snow',
    //              placeholder: 'Introduzca la descripcion para el feriado',
    //          });
    //      } else {
    //          var1.setText('');
    //          var1 = new Quill('#descripcion', {
    //              theme: 'snow',
    //              placeholder: 'Introduzca la descripcion para el feriado',
    //          });
    //      }
    //  });
    // para registro exitoso
    document.addEventListener('feriadoRegistrado', function() {
        Swal.fire({
            title: "Registro satisfactorio",
            text: "El feriado fue creado con exito",
            icon: "success"
        });
        $('#modalRegisterFeriado').modal('hide');
    });

    // para error
    document.addEventListener('feriadooNoRegistrado', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "No se pudo completar el registro. Intente nuevamente o comuníquese con la Unidad de Sistemas si el problema persiste.",
            icon: "error"
        });
        $('#modalRegisterFeriado').modal('hide');
    });
    // REGISTRO YA EXISTE
    document.addEventListener('feriadoYaExiste', function() {
        Swal.fire({
            title: "El feriado ya fue registrado",
            text: "Intente registrar otro feriado o actualice el existente.",
            icon: "error"
        });
        $('#modalRegisterFeriado').modal('hide');
    });
    // faltan datos
    document.addEventListener('faltaDatos', function() {
        Swal.fire({
            title: "Falta datos",
            text: "Por favor, complete todos los campos requeridos.",
            icon: "error"
        });
        $('#modalRegisterFeriado').modal('hide');
    });
    // la fecha ya existe, es yunik
    document.addEventListener('fechaYunik', function() {
        Swal.fire({
            title: "La fecha ya existente",
            text: "Por favor, seleccione otra fecha, o edite la que ya esta registrada.",
            icon: "error"
        });
        $('#modalRegisterFeriado').modal('hide');
    });
</script>
