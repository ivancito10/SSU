<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">

            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white"
                class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                <path
                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
            </svg>

            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">CERTIFICADO DE
                AFILIACIÓN</h6>
        </div>
        <x-atras-button wire:click="f_atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    @if ($datosFamiliaAfiliado)
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-7 col-12">
                <div class="card p-2 card-comun h-100">
                    <fieldset class="field_estilos_sin_hover">
                        <legend>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em> Complete los siguientes datos para el certificado:</em>
                            <span>*</span>
                        </legend>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <select class="form-control input-comun" wire:model="idPersona"
                                    style="padding-rigth:1;  border-radius:10px;" wire:change="mostrarPersona">

                                    @foreach ($datosFamiliaAfiliado as $datos)
                                        <option value="{{ $datos['id'] }}">{{ $datos['nombres'] }}
                                            {{ $datos['p_apellido'] }}
                                            {{ $datos['s_apellido'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                                <label for="nro_cite"> NRO. CITE: </label>
                                <input id="nro_cite" class="form-control input-comun" type="text"
                                    wire:model="m_cite" disabled>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                                <label for="fecha_emision">FECHA EMISIÓN: </label>
                                <input id="fecha_emision" class="form-control input-comun" type="date"
                                    wire:model="fecha_emision" style="padding-rigth:1;  border-radius:10px;"
                                    wire:change="funcion_fecha" disabled>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                                <label for="fecha_caducidad">FECHA CADUCIDAD: </label>
                                <input id="fecha_caducidad" class="form-control input-comun" type="date"
                                    wire:model="fecha_caducidad" disabled>
                            </div>


                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="motivo">MOTIVO: </label>
                                <textarea class="form-control input-comun" placeholder="Ingrese el motivo del registro." wire:model="motivo"
                                    rows="3" style="padding-rigth:1;  border-radius:10px;" oninput="this.value = this.value.toUpperCase()"></textarea>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 pt-2" style="text-align:center;">
                                <x-save-button onclick="saveCertificado()" wire:loading.attr="disabled"
                                    id="save-button">
                                    GENERAR
                                    CERTIFICADO</x-save-button>
                            </div>
                        </div>
                    </fieldset>


                    <div class="row">


                        <br>
                        <div class="row">


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-12">
                <div class="card p-2 card-comun ">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <div class="card cabecera">
                            <b> DETALLE DE DATOS </b>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table tabla-ploma">
                            <tr>
                                <td class="text-center" style="width: 50%; ">
                                    @if ($texto == 'TITULAR')
                                        <img src="{{ !empty($fotoTit) ? 'data:image/jpg;base64,' . $fotoTit : asset('images/default_afiliacion.jpg') }}"
                                            class="img-thumbnail" width="100" height="100">
                                    @else
                                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                            class="img-thumbnail" width="100" height="100">
                                    @endif
                                </td>
                                <td class="text-center" style="width: 50%">
                                    <b>{{ $aseguradoTipo }}</b>
                                    @if ($texto == 'TITULAR')
                                        <span class=" font-weight-bold  " style="color:#273746;  ">{{ $tipo_titular }}
                                        </span>
                                        <span class=" font-weight-bold " style="color: #236cb6; "> {{ $permantente }}
                                        </span>
                                    @endif


                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <strong>NOMBRE COMPLETO: </strong> {{ $nombreCompleto }}
                                </td>
                            </tr>
                            <tr>
                                <td> <strong>C.I. :</strong> {{ $ci }} {{ $expedido }}
                                </td>
                                <td>
                                    <strong>MATRICULA :</strong>
                                    {{ $matricula }}
                                    {{--@if ($texto == 'TITULAR')
                                        <span class=" font-weight-bold " style="color: #236cb6; ">
                                            {{ $codigoTitular }}
                                        </span>
                                    @else
                                        <span class=" font-weight-bold " style="color: #236cb6; ">
                                            {{ $codigoBeneficiario }}
                                        </span>
                                    @endif--}}
                                </td>
                            </tr>
                            @if ($texto == 'TITULAR')
                                @if ($nombreInstitucionTit)
                                    <tr>
                                        <td colspan="2">
                                            <strong>INSTITUCIÓN: </strong> {{ $nombreInstitucionTit }}
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </table>
                        @if ($texto == 'BENEFICIARIO')
                            <table class="table tabla-ploma">
                                <tr>
                                    <td class="text-center" colspan="2" style="background-color:#DFE5EB">
                                        <b>DATOS DE TITULAR</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <strong>NOMBRE COMPLETO: </strong> {{ $nombreCompletoTit }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>CARNET: </strong> {{ $ci_tit }}
                                    </td>
                                    <td>
                                        <strong>MATRICULA: </strong> {{ $matricula_tit }} {{ $codigoTitular }}
                                    </td>
                                </tr>

                                @if ($nombreInstitucionTit)
                                    <tr>
                                        <td colspan="2">
                                            <strong>INSTITUCIÓN: </strong>
                                            {{ $nombreInstitucionTit }}
                                        </td>
                                    </tr>
                                @endif

                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 10vh;">
            <div class="card shadow border-0" style="max-width: 450px; border-radius: 15px; overflow: hidden;">
                <div class="card-body p-5 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 70px; height: 70px; background-color: #f8d7da; color: #dc3545;">
                        <span style="font-size: 2rem; font-weight: bold;">!</span>
                    </div>

                    <h4 class="font-weight-bold text-dark">Solicitud Detenida</h4>

                    <p class="text-muted mt-3">
                        Tu solicitud de certificado no puede procesarse porque tu afiliación figura como
                        <span class="badge badge-danger p-2">'Cesante'</span> o
                        <span class="badge badge-danger p-2">'Sin Vigencia'</span>.
                    </p>

                    <div class="alert alert-warning border-0 text-left mt-4 mb-4" style="font-size: 0.85rem;">
                        <strong>Nota:</strong> Si crees que se trata de un error, por favor contacta con la unidad de
                        afiliaciones para verificar tu estado.
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
<script>
    function saveCertificado() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarCertificado');
            }
        });

    }

    document.addEventListener('no_registra', function() {
        Swal.fire({
            title: " Error de registro!",
            text: "El registro fue cancelado.",
            icon: "warning"
        });
        $('#modalRegister').modal('hide');
    });

    document.addEventListener('afiliacionNoVigente', function() {
        Swal.fire({
            title: "No se puede emitir el certificado!",
            text: "El asegurado no tiene una afiliación vigente, por lo que no se puede emitir el certificado.",
            icon: "error"
        });
        $('#modalRegister').modal('hide');
        $('#modalCertificadoSsuRegister').modal('hide');
    });
</script>
