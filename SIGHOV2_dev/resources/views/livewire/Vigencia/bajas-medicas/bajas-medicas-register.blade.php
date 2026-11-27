<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <div class="card">
        <div class="card-header" style="background-color:#34495e ">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                    REGISTRAR BAJA MÉDICA </h7>
                <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
            </div>
        </div>
        <br>
        <div class="row">

            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:#c4dff3 ;text-align:center;"> <b> ASEGURADO </b></p>
                    <div class="row d-flex flex-column align-items-center">
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12"
                            style="display: flex; justify-content: center; font-size: 35px">

                            @if ($estado_vigencia == 'VIGENTE')
                                <span style="color: green;"><b>VIGENTE</b></span>
                            @elseif($estado_vigencia == 'CESANTE')
                                <span class="h3 parpadeo" style="color: #ed8a03;"><b>CESANTE</b></span>
                            @elseif($estado_vigencia == 'NO VIGENTE')
                                <span style="color: red;"><b>NO
                                        VIGENTE</b></span>
                            @endif


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                            <span class="h5 font-weight-bold">{{ $edadPersona }} años</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
                            <strong style="font-size:90%">MATRICULA :</strong>
                            <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                            <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoInstitucional }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <strong style="font-size:90%">TIPO :</strong>
                            <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_asegurado }}</span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <span class=" font-weight-bold h5" style="color:#a50601">{{ $institucion }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <strong> NOMBRE COMPLETO:</strong>
                            <p class="p-1" id="nombre"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $nombreCompleto }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-4 col-sm-4 col-4">
                            <strong> CARNET:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $ci }}</p>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-4 col-4">
                            <strong>SEXO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $sexo }} </p>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-4 col-4">
                            <strong>
                                FECHA DE NACIMIENTO: </strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ \Carbon\Carbon::parse($fecha_nacimiento)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <p style="background-color:#c4dff3 ;text-align:center;"> <b> DATOS DE LA NUEVA BAJA MÉDICA </b></p>
                    <div class="card-body">
                        <!-- <p class='h6'><b><em>Introduzca los datos solicitados porfavor.</em></b></p> -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
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

                            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                                <label for="cite" style='color:#34495e ;'> NRO. BAJA MÉDICA:</label>
                                <input class="form-control input-comun @error('cite') is-invalid @enderror"
                                    id="cite" wire:model.live=cite placeholder='Escriba en nro. de la baja médica'
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('cite')
                                    <div class="text-danger">
                                        <b>El campo nro de baja médica es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                                <label for="fecha_recepcion"> FECHA RECEPCIÓN: </label>
                                <input class="form-control input-comun @error('fecha_recepcion') is-invalid @enderror"
                                    id="fecha_recepcion" disabled wire:model.live=fecha_recepcion type="date">
                                @error('fecha_recepcion')
                                    <div class="text-danger">
                                        <b>{{ $message }} </b>
                                    </div>
                                @enderror
                                <br>
                            </div>
                        </div>
                        <p class='h6'><b>VALIDEZ:</b></p>
                        <div class='row'>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                                <label for="fecha_inicio"> FECHA INICIO: </label>
                                <input class="form-control input-comun @error('fecha_inicio') is-invalid @enderror"
                                    id="fecha_inicio" type='date' wire:model.live=fecha_inicio>
                                @error('fecha_inicio')
                                    <div class="text-danger">
                                        <b>{{ $message }} </b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                                <label for="fecha_fin"> FECHA FIN: </label>
                                <input class="form-control input-comun @error('fecha_fin') is-invalid @enderror"
                                    id="fecha_fin" type='date' wire:model.live=fecha_fin>
                                @error('fecha_fin')
                                    <div class="text-danger">
                                        <b>{{ $message }} </b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                                <label for="dias_diferencia"> DÍAS DE BAJA: </label>
                                <input class="form-control input-comun @error('dias_diferencia') is-invalid @enderror"
                                    id="dias_diferencia" type='number' wire:model=dias_diferencia disabled>
                                @error('dias_diferencia')
                                    <div class="text-danger">
                                        <b>{{ $message }} </b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <!-- Select de especialidades -->
                            <div class='col-lg-6 col-md-4 col-sm-6 col-12'>
                                <label for="especialidad">ESPECIALIDAD A SOLICITAR:</label>
                                <select wire:model="id_especialidad" name="especialidad" id="especialidad-select"
                                    class="form-control input-comun select2 @error('id_especialidad') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required
                                    wire:change="buscarEspecialista($event.target.value)">
                                    <option selected="selected">Selecciona la especialidad</option>
                                    @foreach ($especialidadSelect as $especialidad)
                                        <option value="{{ $especialidad->id }}">{{ $especialidad->especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_especialidad')
                                    <div class="text-danger">
                                        <b> El campo especialidad es obligatorio. </b>
                                    </div>
                                @enderror
                            </div>

                            <!-- Select de especialistas -->
                            <div class='col-lg-6 col-md-4 col-sm-6 col-12'>
                                <label for="especialista-select">ESPECIALISTA:</label>
                                <select wire:model="id_especialista" name="especialista" id="especialista-select"
                                    class="form-control input-comun select2 @error('id_especialista') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Selecciona el especialista</option>
                                    @foreach ($especialistaSelect as $especialista)
                                        <option value="{{ $especialista->id }}">
                                            {{ $especialista->personaEspecialista->nombres . ' ' . $especialista->personaEspecialista->p_apellido . ' ' . $especialista->personaEspecialista->s_apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_especialista')
                                    <div class="text-danger">
                                        <b> El campo especialidad es obligatorio. </b>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" wire:ignore>
                                <br>
                                <label for="diagnostico-texto" style="display: block; text-align: left;">DIAGNÓSTICO
                                </label>
                                <!-- campo para llenar diagnostico -->
                                <div id="diagnostico-texto" style="height: 200px; ">
                                    <!-- <p>introduzca el diagnostico</p> -->
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <x-cancel-button wire:click="cancel">
                        CANCELAR</x-cancel-button>
                    <x-save-button onclick="guardarBaja()">GUARDAR</x-save-button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Quill JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    let quill;
    document.addEventListener('DOMContentLoaded', function() {
        if (!quill) {
            quill = new Quill('#diagnostico-texto', {
                theme: 'snow',
                placeholder: 'Introduzca diagnostico',
            });
        } else {
            quill.setText('');
            quill = new Quill('#diagnostico-texto', {
                theme: 'snow',
                placeholder: 'Introduzca diagnostico',
            });
        }
    });

    function guardarBaja() {
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
                //document.getElementById('cargando').style.display = 'block';
            }
        });
    }

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La baja medica se registró correctamente.",
            icon: "success",
            showConfirmButton: false,
            timer: 2000 // Opcional: Cierra la alerta automáticamente después de 2 segundos
        });
    });

    document.addEventListener('bajaActiva', function(event) {

        Swal.fire({
            title: "Existe una baja activa",
            text: `La baja médica está vigente desde ${event.detail.fechaInicio} hasta ${event.detail.fechaFin}.`,
            icon: "warning",
            confirmButtonText: "Entendido"
        });
    });
</script>
