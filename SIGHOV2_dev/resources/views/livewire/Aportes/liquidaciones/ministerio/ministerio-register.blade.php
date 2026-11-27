<div>
    <!--BOTON GUARADR NUEVA LIQUIDACION -->
    <x-agregar-button style=" padding: 5px 10px;" data-toggle="modal" data-target="#modalMinisterio"> REGISTRAR</x-agregar-button>

    <!--  MODAL ministerio-->
    <x-big-component id="modalMinisterio" labelledby="modalMinisterioLabel" title=" AGREGAR LIQUIDACIÓN A MINISTERIO DE SALUD" saveAction="saveMinisterio()">
        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#1a5276 ;" class="h5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg> <em><b>Seleccionar y completar los siguientes campos.</b> </em> <spam style="color:#cb4335;"><b>*</b></p>
                    </div>
                    
                </div>
                <br>
                <div class="row">
                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                        <label for="id_mes">MES: </label>
                        <select wire:model="id_mes" id="id_mes" name="id_mes" class="form-control"
                         style="width: 100%; border-radius: 10px;"
                            tabindex="-1" aria-hidden="true" required>
                            <option selected="selected">Seleccione el mes.</option>
                            @foreach ($meses as $data)
                                <option value="{{ $data->id }}">{{ $data->mes }}</option>
                            @endforeach
                        </select>
                        @error('id_mes')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Este campo es obligatorio.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror

                    </div>
                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                        <label for="gestion">GESTIÓN:</label>
                        <input wire:model="gestion" name="gestion" id="gestion" 
                        class="form-control" type="number" required style="border-radius: 10px;" min="0">
                        @error('gestion')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Este campo es obligatorio.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                       
                        <label for="fecha_liquidacion">FECHA LIQUIDACIÓN:</label>
                        <input type="date" class="form-control" name="fecha_liquidacion" id="fecha_liquidacion"
                            wire:model='fecha_liquidacion' style="border-radius: 10px;">

                        @error('fecha_liquidacion')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Este campo es obligatorio.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>

                </div>

            </div>
        </div>
    </x-big-component>


</div>



<script>
    function saveMinisterio() {
        Swal.fire({
            title: '¿Guardar liquidación?',
            text: "Se generará el extracto del mes seleccionado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, generar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarMinisterio');
                $('#modalMinisterio').modal('hide');

            }
        });
    }

// para cuando no ingresen todos los campos
document.addEventListener('datosImcompletos', function() {
    Swal.fire({
        title:'Completa todos los datos.',
        text: "Deben ingresar todos los datos, para realizar la liquidacion.",
        icon:'warning'
        });
    });
</script>