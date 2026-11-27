<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<div>
    <x-big-component id="modalAgregar" labelledby="modalAgregarLabel" title="REGISTRAR BAJA MÉDICA"
        saveAction="guardarBajaMedica()">
        <div class="row">
            <div class="col-sm-4">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header" style="background-color:#3E6CA7; color:white">
                        <p class='h6'><b>ASEGURADO</b></p>
                    </div>
                    <div class="card-body">


                        <div class="card-body d-flex flex-column align-items-center">
                            <!--  Mostramos la foto del asegurado en base 64 -->
                            <img src="{{ !empty($this->foto) ? 'data:image/jpg;base64,' . $this->foto : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" width="200" height="200" alt="FTP">

                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 35px">
                            <label for="estado_vigencia">
                                @if ($estado_vigencia == 'VIGENTE')
                                    <span style="color: green;"><b>VIGENTE</b></span>
                                @elseif($estado_vigencia == 'CESANTE')
                                    <span style="color: yellow;"><b>CESANTE</b></span>
                                @elseif($estado_vigencia == 'NO VIGENTE')
                                    <span style="color: red;"><b>NO VIGENTE</b></span>
                                @endif
                            </label>
                            <br>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <spam style="color:#2874a6 ;"><b>MATRICULA :</b> </spam>
                                <spam> {{ $matricula }}</spam>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <spam><b style="color:#34495e ;">TIPO :</b> {{ $tipo_asegurado }} </spam>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombreCompleto"> NOMBRE COMPLETO :</label>
                                <input class="form-control" id="nombreCompleto" disabled wire:model=nombreCompleto
                                    style=" height: 30px; background-color: #f8fcff;">
                            </div>
                        </div>


                        <div class="row mb-1">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="carnet"> CARNET :</label>
                                <input class="form-control" id="carnet" disabled wire:model=ci
                                    style=" height: 30px; background-color: #f8fcff;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="compl"> COMPL:</label>
                                <input class="form-control" id="compl" disabled wire:model=complemento
                                    style=" height: 30px; background-color: #f8fcff;">
                            </div>
                        </div>



                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="institucion"> INSTITUCIÓN :</label>
                                <textarea class="form-control" id="institucion" disabled wire:model=institucion
                                    style="resize:none;  background-color: #f8fcff;"></textarea>
                            </div>
                        </div>

                    </div>
                    <br><br><br><br><br>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header" style="background-color:#3E6CA7; color:white">
                        <p class='h6'><b> NUEVA BAJA MÉDICA</b></p>
                    </div>
                    <div class="card-body">
                        <!-- <p class='h6'><b><em>Introduzca los datos solicitados porfavor.</em></b></p> -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#2471a3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Introduzca los datos solicitados porfavor.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">

                            </div>

                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                <label for="cite" style='color:#34495e ;'> NRO. BAJA MÉDICA:</label>
                                <input class="form-control" id="cite" wire:model=cite
                                    placeholder='Escriba en nro. de la baja médica'
                                    oninput="this.value = this.value.toUpperCase()">

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_recepcion"> FECHA RECEPCIÓN: </label>
                                <input class="form-control" id="fecha_recepcion" disabled wire:model=fecha_recepcion
                                    style=" height: 30px; background-color: #f8fcff;">
                                <br>
                            </div>

                        </div>

                        <p class='h6'><b>VALIDEZ:</b></p>
                        <div class='row'>

                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_inicio"> FECHA INICIO: </label>
                                <input class="form-control" id="fecha_inicio" type='date' wire:model=fecha_inicio>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_fin"> FECHA FIN: </label>
                                <input class="form-control" id="fecha_fin" type='date' wire:model=fecha_fin>

                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="dias"> DÍAS DE BAJA: </label>
                                <input class="form-control" id="dias" type='number' wire:model=dias_diferencia
                                    style=" background-color: #f8fcff;">

                            </div>


                        </div>
                        <br>

                        <div class="row">
                            <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                <label for="especialidad">ESPECIALIDAD A SOLICITAR:</label>
                                <select wire:model="id_especialidad" name="especialidad" id="especialidads-select"
                                    class="form-control select2 @error('especialidad') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Selecciona la especialidad </option>
                                    @foreach ($especialidadSelect as $id_especialidad)
                                        <option value="{{ $id_especialidad->id }}">{{ $id_especialidad->especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                                <br>
                            </div>
                            <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                <label for="especialista-select">ESPECIALISTA:</label>
                                <select wire:model="id_especialista" name="especialista" id="especialista-select"
                                    class="form-control select2 @error('especialista') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Selecciona el especialista </option>
                                    @foreach ($especialistaSelect as $id_especialista)
                                        <option value="{{ $id_especialista->id }}">
                                            {{ $id_especialista->personaEspecialista->nombres . ' ' . $id_especialista->personaEspecialista->p_apellido . ' ' . $id_especialista->personaEspecialista->s_apellido }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <label for="diagnostico-texto" style="display: block; text-align: left;">DIAGNÓSTICO
                                </label>
                                <div id="diagnostico-texto" style="height: 200px; ">
                                    <p>introduzca el diagnostico</p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </x-big-component>
</div>
<!-- Quill JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    let quill;
    window.addEventListener('modalBajaMedica', event => {

        $('#modalAgregar').modal('show');

        // Inicializar Quill si no ha sido inicializado
        setTimeout(function() { // Llamamos a la función para crear el nuevo mapa
            if (!quill) {
                console.log('inicia');
                quill = new Quill('#diagnostico-texto', {
                    theme: 'snow'
                });
            }else{
                console.log('iniciaDenuevo');
                quill.setText('');
                quill = new Quill('#diagnostico-texto', {
                    theme: 'snow'
                });
            }
        }, 500);
            
    });


    // GUARDAR BAJA MEDICA
    function guardarBajaMedica() {
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
                Livewire.dispatch('guardarBaja', {
                    diagnostico: quill.getSemanticHTML()
                });
                $('#modalAgregar').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalAgregar').modal('hide');
            }
        });
    }

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: 'Guardado!',
            text: "Se registro con éxito.",
            icon: 'success'
        });
        $('#modalAgregar').modal('hide');
    });
</script>
