<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <div class="card-header" style="background-color:#34495e ">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                MODIFICAR CONVENIO DEL INTERIOR</h7>
            <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>

    <div class="row">
         

        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> ASEGURADO DEL INTERIOR</b></p>
                <div class="row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto_nombre) ? 'data:image/jpg;base64,' . $foto_nombre : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto_nombre }}">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <span class="h5 font-weight-bold">{{ $edadPersona }} años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula_seguro }} </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_asegurado }}</span>
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



        <!-- vista para el registro-->
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                <div class="card-body">
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
                                <em class="ml-2 h5"><b>Modificar los siguientes datos.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_hoja_de_ruta"> NRO. HOJA RUTA:</label>
                            <input class="form-control @error('nro_hoja_de_ruta') is-invalid @enderror"
                                id="nro_hoja_de_ruta" wire:model.live=nro_hoja_de_ruta
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nro_hoja_de_ruta')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                            <br>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud_hr"> FECHA SOLICITUD:</label>
                            <input class="form-control @error('fecha_solicitud_hr') is-invalid @enderror" type='date'
                                id="fecha_solicitud_hr" wire:model.live=fecha_solicitud_hr>
                            @error('fecha_solicitud_hr')
                                <div class="text-danger">
                                    <b>El campo de fecha de solicitud es obligatorio.</b>
                                </div>
                            @enderror
                            <br>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="cod_cite_int"> CÓDIGO CITE INTERIOR:</label>
                            <input class="form-control @error('cod_cite_int') is-invalid @enderror" id="cod_cite_int"
                                wire:model.live=cod_cite_int oninput="this.value = this.value.toUpperCase()">
                            @error('cod_cite_int')
                                <div class="text-danger">
                                    <b>El campo codigo cite interior es obligatorio.</b>
                                </div>
                            @enderror
                            <br>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="codInte"> NRO. CÓDIGO INTERIOR:</label>
                            <input class="form-control @error('codInte') is-invalid @enderror" id="codInte"
                                wire:model="codInte" disabled>
                            @error('codInte')
                                <div class="text-danger">
                                    <b>El campo nro de codigo interior es obligatorio. </b>
                                </div>
                            @enderror
                            <br>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <b>SEGURO DE ORIGEN:</b>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <b>VALIDEZ DE LA SOLICITUD:</b>
                        </div>
                    </div>

                    <div class="row mb-2">

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <select wire:model="id_seguro_interior" id="id_seguro_interior"
                                class="form-control select2 " style="width: 100%;" tabindex="-1">
                                <option value="" disabled>Seleccione un seguro</option>
                                <option selected="selected" value="{{ $id_seguro_interior }}">{{ $nombre_seguro }}
                                </option>
                                @foreach ($seguros as $seguro)
                                    <option value="{{ $seguro->id }}">{{ $seguro->nombre_seguro }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 mt-1">
                            <b>DESDE:</b>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <input class="form-control" id="fecha_inicio" type='date' wire:model=fecha_inicio>
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 mt-1">
                            <b>HASTA:</b>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <input class="form-control" id="fecha_fin" type='date' wire:model=fecha_fin>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="especialidades">ESPECIALIDADES A
                                SOLICITAR:</label>
                            <select wire:model="especialidades" id="especialidades" class="form-control select2 "
                                style="width: 100%" multiple="multiple" tabindex="-1">

                                @foreach ($especialidadesSelect as $especialidad)
                                    <option value="{{ $especialidad->id }}"
                                        {{ in_array($especialidad->id, $especialidades) ? 'selected' : '' }}>
                                        {{ $especialidad->especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="servicios">SERVICIOS A
                                SOLICITAR:</label>
                            <select wire:model="servicios" id="servicios" class="form-control select2"
                                style="width: 100%;" multiple="multiple" tabindex="-1">

                                @foreach ($serviciosSelect as $servicio)
                                    <option value="{{ $servicio->id }}"
                                        {{ in_array($servicio->id, $servicios) ? 'selected' : '' }}>
                                        {{ $servicio->servicio }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <b>FARMACIA:</b>
                        </div>
                        {{--@if ($id_tipo_asegurado == '' || $id_tipo_asegurado == 9)
                        @else
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <b>RELACIONAR CON TITULAR DEL INTERIOR:</b>
                            </div>
                        @endif--}}
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <p class='h6'><b>MEDICAMENTOS LINAME:</b></p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <p class='h6'><b>MEDICAMENTOS EXTRALINAME:</b></p>
                        </div>
                        {{--@if ($id_tipo_asegurado == '' || $id_tipo_asegurado == 9)
                        @else
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#2471a3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Se debe relacionar al beneficiario con el titular del
                                            interior.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        @endif--}}
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
                        {{--@if ($id_tipo_asegurado == '' || $id_tipo_asegurado == 9)
                        @else
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                                style="display: flex; justify-content: center;">
                                <x-agregar-button wire:click="abrirTablaTitularesInterior"> REGISTRAR TITULAR DEL
                                    INTERIOR
                                </x-agregar-button>
                                @include('livewire.Vigencia.convenios.interior.modalRelacionarTitular')
                            </div>
                        @endif--}}
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="otras_limitaciones" style="display: block; text-align: left;">OTRAS
                                LIMITACIONES:</label>
                            <!-- <textarea class="form-control" id="otras_limitaciones" wire:model='otras_limitaciones' class="swal2-input"
                                rows='4' oninput="this.value = this.value.toUpperCase()"></textarea> -->
                            <div id="otras_limitaciones" style="height: 200px;">
                                {!! $otras_limitaciones !!}
                            </div>

                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="observacion" style="display: block; text-align: left;">OBSERVACIONES:</label>
                            <!-- <textarea class="form-control" id="observacion" wire:model='observacion' class="swal2-input" rows='4'
                                oninput="this.value = this.value.toUpperCase()"></textarea> -->
                            <div id="observacion" style="height: 200px;">
                                {!! $observacion !!}
                            </div>

                        </div>


                    </div>

                </div>
            </div>
            <br>
        </div>

    </div>

    <div class="modal-footer">
        <x-cancel-button wire:click="cancelarEdicionAutorizacion"> CANCELAR</x-cancel-button>
        <x-save-button onclick="editarAutorizacion()">GUARDAR</x-save-button>
    </div>

    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 25px;
        }
    </style>
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


    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        let quill1;
        let quill2;
        document.addEventListener('DOMContentLoaded', function() {
            if (quill1) {
                quill1.setText('');
            }
            quill1 = new Quill('#otras_limitaciones', {
                theme: 'snow'
            });

            if (quill2) {
                quill2.setText('');
            }
            quill2 = new Quill('#observacion', {
                theme: 'snow'
            });


        });

        // esto es lo de SEGURO ORIGEN
        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#id_seguro_interior');
            $(select).select2();
            $('#id_seguro_interior').on('change', function(e) {
                var data = $(this).val();
                @this.set('id_seguro_interior', data);
            });
        });
        // esto es lo ESPECIALIDADES
        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#especialidades');
            $(select).select2();
            $('#especialidades').on('change', function(e) {
                var data = $(this).val();
                @this.set('especialidades', data);
            });
        });
        // esto es lo de SERIVICIOS
        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#servicios');
            $(select).select2();
            $('#servicios').on('change', function(e) {
                var data = $(this).val();
                @this.set('servicios', data);
            });
        });
        // Aqui se busca el carnet

        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#id_seguro_interior');
            $(select).select2();

            $(select).on('change', function(e) {
                var selectedValuesSeg = $(this).val();
                // Enviar los valores seleccionados al componente Livewire
                @this.set('id_seguro_interior', selectedValuesSeg);
            });
        });

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

        function editarAutorizacion() {
            Swal.fire({
                title: 'Modificar Convenio del Interior?',
                text: "Se guardarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('editarConvenioInterior', {
                        otraslimitaciones: quill1.getSemanticHTML(),
                        observaciones: quill2.getSemanticHTML()
                    });

                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló la edición",
                        icon: "error"
                    });
                }
            });
        }


        // MODALS
        window.addEventListener('modalTablaTitularInterior', event => {

            $('#modalTablaTitularInterior').modal('show');

        });

        document.addEventListener('titularSel', function() {

            Swal.fire({
                title: 'Relacionar con el Titular del Interior?',
                text: "Se guardara la informacion.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, validar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modalTablaTitularInterior').modal('hide');
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló la validación",
                        icon: "error"
                    });
                }
            });
        });

        document.addEventListener('actualizado', function() {
            Swal.fire({
                title: "Modificado!",
                text: "El convenio interior fue modificado exitosamente.",
                icon: "success",
                showConfirmButton: false,
            });
        });

        document.addEventListener('Noactualizado', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "Se canceló el registro.",
                icon: "error"

            });
        });

        document.addEventListener('AutorizacionNoEditada', function() {
            Swal.fire({
                title: "Cancelado",
                text: "Se canceló el registro.",
                icon: "error",
                showConfirmButton: false,

            });
        });


    </script>
</div>
