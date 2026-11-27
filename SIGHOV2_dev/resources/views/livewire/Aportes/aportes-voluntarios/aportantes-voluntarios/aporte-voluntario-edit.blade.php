<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;"> MODIFICAR CONTRATO VOLUNTARIO </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    

        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class ="card-body">
                        <div class="row">
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>NOMBRE COMPLETO: </b>
                                     {{$nombre}} {{$primerApellido}} {{$segundoApellido}} </span>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <span><b>CARNET: </b>{{$ci}} @if($complemento !=null) {{$complemento}} @endif </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>FECHA DE NACIMIENTO: </b>{{$fechaNacimiento}} </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="color:#1a5276;  " class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em>Contrato Registrado.</em> </spam>
                                    </b>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span><b>FECHA  INICIO: </b> {{$fecha_i_m}}</span> 
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                 
                                <span><b>FECHA  FIN: </b>{{$fecha_f_m}} </span>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <p class="h5"> 
                                @if ($renovarFechaContrato == false)
                                    <b style="color: #196f3d ; "> VIGENTE</b>
                                @else
                                    <b style="color: #c0392b;"> NO VIGENTE</b> </p>
                                @endif 
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">

                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class ="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Actualizar la dirección y  los datos necesarios.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="direccion">DIRECCIÓN:</label>
                                <input wire:model="direccion" class="form-control @error('direccion') is-invalid @enderror" name="direccion" id="direccion"
                                    style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()" 
                                    placeholder="Ingrese la dirección">
                                    @error('direccion')
                                        <div class="text-danger">
                                            <b>El campo nombre de institución es obligatorio.</b>
                                        </div>
                                    @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                <label for="numero">NÚMERO DE PUERTA:</label>
                                <input wire:model="numero" class="form-control @error('numero') is-invalid @enderror" name="numero" id="numero"
                                    style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="Número de puerta">
                                    @error('numero')
                                        <div class="text-danger">
                                            <b>El campo nombre de institución es obligatorio.</b>
                                        </div>
                                    @enderror
                            </div>
                            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                <label for="zonaId">ZONA:</label>
                                <select wire:model="zonaId" name="zonaId" id="zonaId" class="form-control @error('zonaId') is-invalid @enderror"
                                    style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                    <option selected="selected"value="{{ $zonaId }}"> {{ $zona }}
                                    </option>
                                    @foreach ($zonasSelected as $zonaSelected)
                                        <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('zonaId')
                                    <div class="text-danger">
                                        <b>El campo nombre de institución es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono1">1er. TELÉFONO:</label>
                                <input wire:model="telefono1" class="form-control @error('telefono1') is-invalid @enderror" type='number' name="telefono1"
                                    id="telefono1" style="border-radius: 10px;" placeholder="Ingrese teléfono" min="0">
                                    @error('telefono1')
                                    <div class="text-danger">
                                            <b>El campo nombre de institución es obligatorio.</b>
                                        </div>
                                    @enderror
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono2">2do. TELÉFONO:</label>
                                <input wire:model="telefono2" class="form-control" type='number' name="telefono2"
                                    id="telefono2" style="border-radius: 10px;" placeholder="Ingrese teléfono" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="correo">CORREO ELÉCTRONICO:</label>
                                <input class="form-control" name="correo" id="correo" wire:model='correo' required
                                    style="border-radius: 10px;" placeholder="Ingrese el correo eléctronico">
                            </div>
                        </div>
                        <br>
                        
                        <div class="row">
                             
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Actualizar el registro contrato.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                            
                            
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="fechaInicioContrato">FECHA DE INICIO:</label>
                                <input wire:model="fechaInicioContrato" type="date" class="form-control @error('fechaInicioContrato') is-invalid @enderror"
                                    name="fechaInicioContrato" id="fechaInicioContrato"
                                    style="border-radius: 10px;">
                                    @error('fechaInicioContrato')
                                        <div class="text-danger">
                                            <b>El campo nombre de institución es obligatorio.</b>
                                        </div>
                                    @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="fechaFinContrato"> FECHA DE FIN:</label>
                                <input wire:model='fechaFinContrato' type="date" class="form-control @error('fechaFinContrato') is-invalid @enderror"
                                    name="fechaFinContrato" id="fechaFinContrato" 
                                    style=" border-radius: 10px;">
                                    @error('fechaFinContrato')
                                        <div class="text-danger">
                                            <b>El campo nombre de institución es obligatorio.</b>
                                        </div>
                                    @enderror
                            </div>
                             
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <p style="color: #1a5276 ;" class="h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Modificar la descripción del contrato.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               
                                <label for="descripcionContrato">DESCRIPCIÓN:</label>
                                <textarea wire:model='descripcionContrato' class="form-control" name="descripcionContrato" id="descripcionContrato"
                                    rows='3' placeholder="Ingrese la descripción" style="border-radius: 10px;"
                                    oninput="this.value = this.value.toUpperCase()"></textarea>
                            </div>


                        </div>
                         

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <br>
                                <x-cancel-button wire:click="atras">CANCELAR</x-cancel-button>
                                <x-save-button onclick="saveEditContrato()" wire:loading.attr="disabled"
                                    id="save-button">GUARDAR</x-save-button>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="saveEditContrato">
                                <x-cargando-dos />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .select2-container .select2-selection--single {
                height: 40px !important;
                padding: 6px 12px;
                border-color: #ced4da;
            }

            .select2-selection__rendered {
                line-height: 25px;
            }
        </style>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#zonaId');
        $(select).select2();
        
        $(select).on('select2:open', function() {
            setTimeout(function() {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        $('#zonaId').on('change', function(e) {
            var data = $(this).val();
            @this.set('zonaId', data);
        });
    });

    function saveEditContrato() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se modificarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, modificar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('renovarContrato');
            }
        });
    }

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: "Modificado!",
            text: "El contrato se modifico correctamente.",
            icon: "success"

        });
    });
    document.addEventListener('contratoCancelado', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "El contrato fue cancelado.",
            icon: "error"

        });
    });

</script>
