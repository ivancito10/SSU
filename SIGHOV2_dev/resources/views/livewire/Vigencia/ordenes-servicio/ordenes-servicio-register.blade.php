<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <div class="card-header" style="background-color:#34495e ">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                REGISTRO DE ORDENES DE SERVICIO</h7>
            <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); height: 100%;">
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
                        <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoInstitucional }}
                        </span>
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
                        @if ($esConvenioInteriorSwitch)
                            <strong style="font-size:90%"> SEGURO DE ORIGEN:</strong><br>
                        @endif

                        @if ($esConvenioEmergenciaSwitch)
                            <strong style="font-size:90%"> TITULAR RESPONSABLE:</strong><br>
                        @endif

                        <span class=" font-weight-bold h5" style="color:#a50601"> {{ $institucion }}
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

        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
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
                                <em class="ml-2 h5"><b>Ingresar los siguientes datos.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_orden_servicio" style="color:#2c3e50">
                                NRO. ORDEN DE SERVICIO: </label>
                            <input class="form-control @error('nro_orden_servicio') is-invalid @enderror"
                                id="nro_orden_servicio" wire:model.live=nro_orden_servicio
                                placeholder="Ingrese el numero de servicio"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nro_orden_servicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_recepcion"> FECHA DE RECEPCIÓN:
                            </label>
                            <input class="form-control" id="fecha_recepcion" wire:model.live="fecha_recepcion" disabled
                                type="date">
                            @error('fecha_recepcion')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="id_institucion_convenio"> CENTRO MÉDICO:</label>
                            <select wire:model.live="id_institucion_convenio" id="id_institucion_convenio"
                                class="form-control select2 @error('id_institucion_convenio') is-invalid @enderror"
                                tabindex="-1" style="width: 100%;" required>
                                <option selected="selected">Seleccione el centro médico </option>
                                @foreach ($instiSelect as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->nombre_institucion }}</option>
                                @endforeach
                            </select>
                            @error('id_institucion_convenio')
                                <div class="text-danger">
                                    <b>El campo institucion de convenio es obligatorio.</b>
                                </div>
                            @enderror
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="id_especialidad"> ESPECIALIDAD: </label>
                            <select wire:model.live="id_especialidad" id="id_especialidad"
                                class="form-control select @error('id_especialidad') is-invalid @enderror"
                                tabindex="-1" style="width: 100%;" required
                                wire:change="buscarEspecialista($event.target.value)">
                                <option selected="selected">Seleccione la
                                    especialidad </option>
                                @foreach ($especialidades as $espe)
                                    <option value="{{ $espe->id }}">{{ $espe->especialidad }}</option>
                                @endforeach
                            </select>
                            @error('id_especialidad')
                                <div class="text-danger">
                                    <b>El campo especialidad es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="id_especialista"> ESPECIALISTA:
                            </label>
                            <select wire:model="id_especialista" id="id_especialista"
                                class="form-control select @error('id_especialista') is-invalid @enderror"
                                tabindex="-1" style="width: 100%;" required>
                                <option selected="selected">Seleccione al especilista
                                </option>

                                @foreach ($especialistaSelect as $med)
                                    <option value="{{ $med->id }}">
                                        {{ $med->personaEspecialista->nombres .
                                            ' ' .
                                            $med->personaEspecialista->p_apellido .
                                            ' ' .
                                            $med->personaEspecialista->s_apellido }}
                                    </option>
                                @endforeach

                            </select>
                            @error('id_especialista')
                                <div class="text-danger">
                                    <b>El campo especialista es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
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
                                <em class="ml-2 h5"><b>Registre el diagnóstico.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="estudio"> ESTUDIO: </label>
                            {{-- <!-- <textarea class="form-control  @error('estudio') is-invalid @enderror " id="estudio" wire:model.live='estudio'
                                class="swal2-input" rows='4' placeholder="Ingrese el diagnóstico"
                                oninput="this.value = this.value.toUpperCase()"></textarea> --> --}}
                            <div id="estudio-texto" style="height: 200px; ">
                                {{-- <!-- <p>introduzca el diagnostico</p> --> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
            </div>

            <div class="modal-footer">
                <x-cancel-button wire:click="cancelarOrden">
                    CANCELAR</x-cancel-button>
                <x-save-button onclick="registrarOrdenServicio()">GUARDAR</x-save-button>
            </div>
        </div>

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
</div>

<!-- Quill JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    let quill;
    document.addEventListener('DOMContentLoaded', function() {
        if (!quill) {
            quill = new Quill('#estudio-texto', {
                theme: 'snow',
                placeholder: 'Introduzca estudio',
            });
        } else {
            quill.setText('');
            quill = new Quill('#estudio-texto', {
                theme: 'snow',
                placeholder: 'Introduzca estudio',
            });
        }
    });

    // esto es lo del centro medico 
    //document.addEventListener('DOMContentLoaded', function() {
    //    var select = document.querySelector('#id_institucion_convenio');
    //    $(select).select2();
    //    $('#id_institucion_convenio').on('change', function(e) {
    //        var data = $(this).val();
    //        @this.set('id_institucion_convenio', data);
    //    });
    //});

    // esto es lo de especialidad
    //document.addEventListener('DOMContentLoaded', function() {
    //    var select = document.querySelector('#id_especialidad');
    //    $(select).select2();
    //    $('#id_especialidad').on('change', function(e) {
    //        var data = $(this).val();
    //        @this.set('id_especialidad', data);
    //    });
    //});

    // esto es lo de especialista
    //document.addEventListener('DOMContentLoaded', function() {
    //    var select = document.querySelector('#id_especialista');
    //    $(select).select2();
    //    $('#id_especialista').on('change', function(e) {
    //        var data = $(this).val();
    //        @this.set('id_especialista', data);
    //    });
    //});



    function registrarOrdenServicio() {
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
                Livewire.dispatch('registrarOrden', {
                    estudio: quill.getSemanticHTML()
                });
                document.getElementById('cargando').style.display = 'block';
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }

    document.addEventListener('ordenCreada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La orden de servicio se registro correctamente.",
            icon: "success",
            showConfirmButton: false
        });
    });

    document.addEventListener('ordenCancelado', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "Se canceló el registro",
            icon: "error",
            showConfirmButton: false

        });
    });
</script>
