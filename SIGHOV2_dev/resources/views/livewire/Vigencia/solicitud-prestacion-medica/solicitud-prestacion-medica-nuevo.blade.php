<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <div class="card-header" style="background-color:#34495e ">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                REGISTRO DE SOLICITUD DE PRESTACIONES MÉDICAS</h7>
            <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>
    <div class="row">


        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> ASEGURADO </b></p>
                <div class="row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <span class="h5 font-weight-bold">{{ $edadPersona }} años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                        <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoInstitucional }} </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_asegurado }}</span>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <span class=" font-weight-bold h5" style="color:#a50601">{{ $institucion }}
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1" id="nombre"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $nombreCompleto }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong> CARNET:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $ci }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong>SEXO:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $sexo }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>
                            FECHA DE NACIMIENTO: </strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ \Carbon\Carbon::parse($fecha_nacimiento)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2 h5"><b>Ingresar los siguientes datos.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <b>DÍAS DE VIGENCIA:</b>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <input class="form-control" id="diasVigencia" type="text" wire:model="diasVigencia"
                                disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_hoja_de_ruta"> NRO DE HOJA DE RUTA:</label>
                            <input class="form-control @error('nro_hoja_de_ruta') is-invalid @enderror "
                                id="nro_hoja_de_ruta" wire:model.live=nro_hoja_de_ruta
                                placeholder="Ingrese la nueva hoja de ruta"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nro_hoja_de_ruta')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud_hr"> FECHA SOLICITUD:</label>
                            <input class="form-control @error('fecha_solicitud_hr')is-invalid @enderror"
                                id="fecha_solicitud_hr" type='date' wire:model.live=fecha_solicitud_hr>
                            @error('fecha_solicitud_hr')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="cite"> CITE:</label>
                            <input class="form-control" id="cite" disabled wire:model=cite>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <b>SEGURO DEL INTERIOR:</b>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <b>VALIDEZ DE LA SOLICITUD:</b>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <select wire:model="id_seguro_interior" id="id_seguro_interior"
                                class="form-control  @error('id_seguro_interior') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" required>
                                <option selected="selected">Selecciona el Seguro del Interior
                                </option>
                                @foreach ($seguros as $seguro)
                                    <option value="{{ $seguro->id }}">{{ $seguro->nombre_seguro }}</option>
                                @endforeach
                            </select>
                            @error('id_seguro_interior')
                                <div class="text-danger">
                                    <b>El campo seguro del Interior es obligatorio. </b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 mt-1">
                            <b>DESDE:</b>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <input class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio"
                                type='date' wire:model.live=fecha_inicio>
                            @error('fecha_inicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 mt-1">
                            <b>HASTA:</b>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <input class="form-control  @error('fecha_fin') is-invalid @enderror" id="fecha_fin"
                                type='date' wire:model.live=fecha_fin min="{{ $fecha_inicio }}"
                                wire:change="calcularDiasVigencia">
                            @error('fecha_fin')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="especialidades">ESPECIALIDADES A SOLICITAR:</label>
                            <select wire:model="especialidades" id="especialidades"
                                class="form-control select2 @error('especialidades') is-invalid @enderror"
                                style="width: 100%; background-color: white; color: #2874A6;" multiple="multiple"
                                tabindex="-1" required>

                                @foreach ($especialidadesSelect as $especialidad)
                                    <option value="{{ $especialidad->id }}"
                                        style="background-color: white; color: #2874A6;">
                                        {{ $especialidad->especialidad }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fecha_infecha_finicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="servicios">SERVICIOS A
                                SOLICITAR:</label>
                            <select wire:model.live="servicios" id="servicios"
                                class="form-control select2 @error('servicios') is-invalid @enderror"
                                style="width: 100%;" multiple="multiple" tabindex="-1" required>

                                @foreach ($serviciosSelect as $servicio)
                                    <option value="{{ $servicio->id }}">{{ $servicio->servicio }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <b>FARMACIA:</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <p class='h6'><b>MEDICAMENTOS LINAME:</b></p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <p class='h6'><b>MEDICAMENTOS EXTRALINAME:</b></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center;">
                            <div class="col-sm-12 d-flex align-items-center">
                                <label class="mr-6 text-info">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-info custom-switch-on-success">
                                    <input wire:model="medicamentos_liname" name="medicamentos_liname"
                                        type="checkbox" class="custom-control-input" id="medicamentos_liname">
                                    <label class="custom-control-label" for="medicamentos_liname"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-6 text-success">SI</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center;">
                            <div class="col-sm-12 d-flex align-items-center">
                                <label class="mr-6 text-info">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-info custom-switch-on-success">
                                    <input wire:model="medicamentos_extraliname" name="medicamentos_extraliname"
                                        type="checkbox" class="custom-control-input" id="medicamentos_extraliname">
                                    <label class="custom-control-label" for="medicamentos_extraliname"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-6 text-success">SI</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2 h5"><b>Agregar beneficiarios a la solicitud.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center;">
                            @include('livewire.Vigencia.solicitud-prestacion-medica.modalAgregarGrupoFamiliar')
                        </div>
                    </div>
                    @if ($solicitantesAgregados == [])
                    @else
                        <div class="row mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:#e5eff9  ; color:#1e5886; "><b>
                                            AFILIADOS - GRUPO FAMILIAR AGREGADOS A LA SOLICTUD.</b> </div>
                                    <div class="card-body">
                                        <div class='row'>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr align="center"
                                                        style="background-color:#2471a3 ; color:white;">
                                                        <th scope="col">#</th>
                                                        <th scope="col">NOMBRE COMPLETO</th>
                                                        <th scope="col">MATRICULA</th>
                                                        <th scope="col">CARNET</th>
                                                        <th scope="col">PARENTESCO</th>
                                                        <th scope="col">ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $counter = 1;
                                                    @endphp
                                                    @foreach ($solicitantesAgregados as $personaList)
                                                        <tr style="padding: 15px;">
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $counter++ }}</td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['p_apellido'] . ' ' . $personaList['s_apellido'] . ' ' . $personaList['nombres'] }}
                                                            </td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['matricula_seguro'] }}</td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['complemento'] !== null ? $personaList['ci'] . '-' . $personaList['complemento'] : $personaList['ci'] }}
                                                            </td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['tipo_asegurado'] }}</td>

                                                            <td align="center" style="padding: 4px;">
                                                                <div class="row">
                                                                    <!--    Boton Agregar o Quitar -->
                                                                    <div class="col-sm-12">
                                                                        <x-cancel-button
                                                                            onclick="quitarBeneficiario('{{ $personaList['p_apellido'] }} {{ $personaList['s_apellido'] }} {{ $personaList['nombres'] }}', '{{ $personaList['id'] }}')"></x-cancel-button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2 h5"><b>Agregar observaciones y otras limitaciones.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="otras_limitaciones" style="display: block; text-align: left;">OTRAS
                                LIMITACIONES:</label>
                            <!--<textarea class="form-control" id="otras_limitaciones" wire:model='otras_limitaciones' class="swal2-input"
                                rows='4' oninput="this.value = this.value.toUpperCase()"></textarea>-->
                            <!-- Libreria Quill para docs -->
                            <div id="otras_limitaciones" style="height: 200px;">
                                <!--<p>introduzca texto</p>-->
                            </div>



                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="observacion" style="display: block; text-align: left;">OBSERVACIONES:</label>
                            <!-- <textarea class="form-control" id="observacion" wire:model='observacion' class="swal2-input" rows='4'
                                oninput="this.value = this.value.toUpperCase()"></textarea> -->

                            <div id="observacion" style="height: 200px;">
                                <!--<p>introduzca texto</p>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <x-cancel-button wire:click="cancelarSolicitud"> CANCELAR</x-cancel-button>
                <x-save-button onclick="guardarSolicitud()">GUARDAR</x-save-button>
            </div>
        </div>
    </div>

    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        let quill1;
        let quill2;

        document.addEventListener('DOMContentLoaded', function() {
            if (!quill1) {
                quill1 = new Quill('#otras_limitaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca otras limitaciones',
                });
            } else {
                quill1.setText('');
                quill1 = new Quill('#otras_limitaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca otras limitaciones',
                });
            }

            if (!quill2) {
                quill2 = new Quill('#observacion', {
                    theme: 'snow',
                    placeholder: 'Introduzca observacion',
                });
            } else {
                quill2.setText('');
                quill2 = new Quill('#observacion', {
                    theme: 'snow',
                    placeholder: 'Introduzca observacion',
                });
            }
        });


        /*document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#id_seguro_interior');
            $(select).select2();

            $(select).on('change', function(e) {
                var selectedValuesSeg = $(this).val();
                // Enviar los valores seleccionados al componente Livewire
                @this.set('id_seguro_interior', selectedValuesSeg);
            });
        });*/

        document.addEventListener('DOMContentLoaded', function() {
            var selectMultipleEsp = document.querySelector('#especialidades');
            $(selectMultipleEsp).select2({
                placeholder: 'Selecciona las especialidades'
            });

            $(selectMultipleEsp).on('change', function(e) {
                var selectedValuesEsp = $(this).val();
                // Enviar los valores seleccionados al componente Livewire
                @this.set('especialidades', selectedValuesEsp);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var selectMultipleServ = document.querySelector('#servicios');
            $(selectMultipleServ).select2({
                placeholder: 'Selecciona los servicios'
            });

            $(selectMultipleServ).on('change', function(e) {
                var selectedValuesServ = $(this).val();
                // Enviar los valores seleccionados al componente Livewire
                @this.set('servicios', selectedValuesServ);
            });
        });

        function guardarSolicitud() {
            Swal.fire({
                title: '¿Registrar Solicitud?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarSolicitud', {
                        otraslimitaciones: quill1.getSemanticHTML(),
                        observaciones: quill2.getSemanticHTML()
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

        function quitarBeneficiario(persona, idPersona) {
            Swal.fire({
                title: `¿Está seguro de quitar al Beneficiario ${persona}?`,
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('quitarSolicitante', [idPersona]);
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }


        document.addEventListener('solRetirado', function() {
            Swal.fire({
                title: "Guardado",
                text: "El solicitante fue retirado de la solicitud existosamente",
                icon: "success"

            });
        });

        document.addEventListener('solNoRetirado', function() {
            Swal.fire({
                title: "Error",
                text: "El solicitante no pudo ser retirado de la solicitud. Intente nuevamente",
                icon: "error"

            });
        });

        document.addEventListener('guardado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La solictud prestación médica fue registrada exitosamente.",
                icon: "success"

            });
        });

        document.addEventListener('Noguardado', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "La solictud prestación médica fue cancelado.",
                icon: "error"

            });
        });

        document.addEventListener('sinEspNiServ', function() {
            Swal.fire({
                title: "Sin especialidades ni servicios",
                text: "Debe añadir al menos una especialidad o un servicio para proceder",
                icon: "warning"
            });
        });

        document.addEventListener('repetido', function() {
            Swal.fire({
                title: "Solicitud con número de CITE repetido!",
                text: "Recargue la página",
                icon: "error"

            });
        });
    </script>
    <!-- Estilos específicos de select2 -->
    <style>
        /* Cambia el fondo y color del select cuando tiene elementos seleccionados */
        .select2-container--default .select2-selection--multiple {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
            border-color: #ccc !important;
            /* Borde */
        }

        /* Cambia el color de los ítems seleccionados */
        .select2-container--default .select2-selection__choice {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
            border-color: #2874A6 !important;
            /* Borde del ítem */
        }

        /* Cambia los colores de los ítems en la lista desplegable */
        .select2-container--default .select2-results__option {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
        }

        /* Cambia el color del ítem que está resaltado al hacer hover o está seleccionado en la lista */
        .select2-container--default .select2-results__option--highlighted {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
        }
    </style>

</div>
