<div>
    @can('ver.afiliacion_seguimiento_ssue')
        <div
            style="display: flex; justify-content: space-between; align-items: center; background-color:#0C2B42;padding:5px;border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
            <div style="display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#86cdf3" class="bi bi-person-fill"
                    viewBox="0 0 16 16" style="color:white">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
                <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">SEGUIMIENTO DE
                    SOLICITUD - ESTUDIANTES</h7>
            </div>
            <x-atras-button wire:click="salir" style="margin-left: auto; padding-right:10px">SALIR</x-atras-button>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 ">
                <div class="card-comun p-2 mb-2 ">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-6 col-12 ">
                            <div class=" row ">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <!-- Paso 4. Mostramos la foto en base 64 -->
                                    <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span class=" h5 font-weight-bold">{{ $edad }} años </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  text-center">
                                <strong style="font-size:90%">MATRICULA :</strong>
                                <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong> FECHA DE SOLICITUD: </strong>
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $fecha_solicitud }}
                                </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong> FECHA DE INGRESO A SSU: </strong>
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $fecha_afiliacion }}
                                </p>
                            </div>
                            @if ($fecha_reafiliacion != null)
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <strong> FECHA DE AFILIACION: </strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $fecha_reafiliacion }} </p>
                                </div>
                            @endif
                            @if ($fecha_fin_vigencia != null)
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <strong> FIN DE VIGENCIA: </strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $fecha_fin_vigencia }} </p>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-6 col-12 ">
                            <div class="card-header p-1"
                                style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0e6296"
                                    class="bi bi-filetype-doc" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zm-7.839 9.166v.522q0 .384-.117.641a.86.86 0 0 1-.322.387.9.9 0 0 1-.469.126.9.9 0 0 1-.471-.126.87.87 0 0 1-.32-.386 1.55 1.55 0 0 1-.117-.642v-.522q0-.386.117-.641a.87.87 0 0 1 .32-.387.87.87 0 0 1 .471-.129q.264 0 .469.13a.86.86 0 0 1 .322.386q.117.255.117.641m.803.519v-.513q0-.565-.205-.972a1.46 1.46 0 0 0-.589-.63q-.381-.22-.917-.22-.533 0-.92.22a1.44 1.44 0 0 0-.589.627q-.204.406-.205.975v.513q0 .563.205.973.205.406.59.627.386.216.92.216.535 0 .916-.216.383-.22.59-.627.204-.41.204-.973M0 11.926v4h1.459q.603 0 .999-.238a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.59-.68q-.395-.234-1.004-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082H.79V12.57Zm7.422.483a1.7 1.7 0 0 0-.103.633v.495q0 .369.103.627a.83.83 0 0 0 .298.393.85.85 0 0 0 .478.131.9.9 0 0 0 .401-.088.7.7 0 0 0 .273-.248.8.8 0 0 0 .117-.364h.765v.076a1.27 1.27 0 0 1-.226.674q-.205.29-.55.454a1.8 1.8 0 0 1-.786.164q-.54 0-.914-.216a1.4 1.4 0 0 1-.571-.627q-.194-.408-.194-.976v-.498q0-.568.197-.978.195-.411.571-.633.378-.223.911-.223.328 0 .607.097.28.093.489.272a1.33 1.33 0 0 1 .466.964v.073H9.78a.85.85 0 0 0-.12-.38.7.7 0 0 0-.273-.261.8.8 0 0 0-.398-.097.8.8 0 0 0-.475.138.87.87 0 0 0-.301.398" />
                                </svg>
                                <b>DOCUMENTOS </b>
                            </div>
                            <div class="row p-2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                                    <table widht="100%">
                                        <tr>
                                            <td class=" pt-2 pb-2 mr-2"><b>Fotocopia de carnet:</b> </td>
                                            <td class=" pt-2  pb-2 mr-2"><x-ver-pdf-button style="padding:4px; "
                                                    wire:click='fotocopia_ci'></x-ver-pdf-button>
                                                <div wire:loading wire:target="fotocopia_ci"> <x-cargando-dos /> </div>
                                            </td>
                                            <td class=" pt-2  pb-2 mr-2 pl-2">
                                                <x-descargar-button style="padding:4px"
                                                    wire:click='descargaFotocopia'></x-descargar-button>
                                                <div wire:loading wire:target="descargaFotocopia"> <x-cargando-dos /> </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class=" pt-2  pb-2 mr-2"><b>Matricula:</b> </td>
                                            <td class=" pt-2  pb-2 mr-2"> <x-ver-pdf-button style="padding:4px;  "
                                                    wire:click='matriculas'></x-ver-pdf-button>
                                                <div wire:loading wire:target="matriculas"> <x-cargando-dos /> </div>
                                            </td>
                                            <td class=" pt-2  pb-2 mr-2 pl-2">
                                                <x-descargar-button style="padding:4px"
                                                    wire:click='descargarMatricula'></x-descargar-button>
                                                <div wire:loading wire:target="descargarMatricula"> <x-cargando-dos />
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class=" pt-2  pb-2 mr-2"><b>Boleta de Inscripción:</b> </td>
                                            <td class=" pt-2  pb-2 mr-2"> <x-ver-pdf-button style="padding:4px"
                                                    wire:click='boleta'></x-ver-pdf-button>
                                                <div wire:loading wire:target="boleta"> <x-cargando-dos /> </div>
                                            </td>
                                            <td class=" pt-2  pb-2 mr-2 pl-2">
                                                <x-descargar-button style="padding:4px"
                                                    wire:click='descargarBoleta'></x-descargar-button>
                                                <div wire:loading wire:target="descargarBoleta"> <x-cargando-dos /> </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class=" pt-2  pb-2 mr-2"><b>Form no afiliación - Gestora:</b> </td>
                                            <td class=" pt-2  pb-2 mr-2"> <x-ver-pdf-button style="padding:4px"
                                                    wire:click='form_no_afiliacion'></x-ver-pdf-button>
                                                <div wire:loading wire:target="form_no_afiliacion"> <x-cargando-dos />
                                                </div>
                                            </td>
                                            <td class=" pt-2  pb-2 mr-2 pl-2">
                                                <x-descargar-button style="padding:4px"
                                                    wire:click='descargarGestora'></x-descargar-button>
                                                <div wire:loading wire:target="descargarGestora"> <x-cargando-dos /> </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class=" pt-2  pb-2 mr-2"><b>Formulario 101:</b> </td>
                                            <td class=" pt-2  pb-2 mr-2"> <x-ver-pdf-button style="padding:4px"
                                                    wire:click='certificado_cajas'></x-ver-pdf-button>
                                                <div wire:loading wire:target="certificado_cajas"> <x-cargando-dos /> </div>
                                            </td>
                                            <td class=" pt-2  pb-2 mr-2 pl-2">
                                                <x-descargar-button style="padding:4px"
                                                    wire:click='descargarForm101'></x-descargar-button>
                                                <div wire:loading wire:target="descargarForm101"> <x-cargando-dos /> </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class=" pt-2  pb-2 mr-2"><b>Tipo de sangre:</b> </td>
                                            <td class=" pt-2  pb-2 mr-2"> <x-ver-pdf-button style="padding:4px"
                                                    wire:click='tipo_sangre'></x-ver-pdf-button>
                                                <div wire:loading wire:target="tipo_sangre"> <x-cargando-dos /> </div>
                                            </td>
                                            <td class=" pt-2  pb-2 mr-2 pl-2">
                                                <x-descargar-button style="padding:4px"
                                                    wire:click='descargarTipoSangre'></x-descargar-button>
                                                <div wire:loading wire:target="descargarTipoSangre"> <x-cargando-dos />
                                                </div>
                                            </td>

                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 ">
                <!--    datos personales -->
                <div class="card-comun p-2 mb-2 ">

                    <div class="row">
                        @if ($estado_validar == 'AFILIADO')
                            <div class="col-lg-7 col-md-6 col-sm-6 col-12">
                                <p class="h6 "
                                    style="border: 2px solid #d4ac0d; border-radius: 5px; background-color: #f7dc6f; padding: 5px;">
                                    <em><b style="font-size:19px"> ¡Tu carnet digital está disponible! Haz clic en el botón
                                            para
                                            verlo. </b></em>
                                </p>
                            </div>
                            <div class="col-lg-7 col-md-6 col-sm-6 col-12">
                                <label><b>CARNET DIGITAL AQUÍ <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" style="transform: rotate(90deg);" fill="currentColor"
                                            class="bi bi-hand-index-thumb-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M8.5 1.75v2.716l.047-.002c.312-.012.742-.016 1.051.046.28.056.543.18.738.288.273.152.456.385.56.642l.132-.012c.312-.024.794-.038 1.158.108.37.148.689.487.88.716q.113.137.195.248h.582a2 2 0 0 1 1.99 2.199l-.272 2.715a3.5 3.5 0 0 1-.444 1.389l-1.395 2.441A1.5 1.5 0 0 1 12.42 16H6.118a1.5 1.5 0 0 1-1.342-.83l-1.215-2.43L1.07 8.589a1.517 1.517 0 0 1 2.373-1.852L5 8.293V1.75a1.75 1.75 0 0 1 3.5 0" />
                                        </svg>
                                        :</b></label>

                                <button class="btn btn-light mt-2" type="button" title="VISTA DE CARNET DIGITAL"
                                    wire:click="generar_carnet_ssue" style="color: #1a5276;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43"
                                        fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                    </svg>
                                </button>

                            </div>
                        @endif

                        <!-- Columna derecha: Estado de solicitud -->
                        <div class="col-lg-12 col-md-12  text-right">
                            <div>
                                <label for="nombres"><b>SOLICITUD:</b></label>
                                @if ($estado_validar == 'PENDIENTE')
                                    <span style="color: #F6C105;font-size: 34px;"><b>PENDIENTE</b></span>
                                @elseif($estado_validar == 'AFILIADO')
                                    <span style="color: green; font-size: 34px;"><b>AFILIADO</b></span>
                                @elseif($estado_validar == 'VALIDADO')
                                    <span style="color: green; font-size: 34px;"><b>VALIDADO</b></span>
                                @elseif($estado_validar == 'OBSERVADO')
                                    <span style="color: red; font-size: 34px;"><b>OBSERVADO</b></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($estado_validar == 'VALIDADO')
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p class="h4"
                                style="border: 2px solid #d4ac0d; border-radius: 5px; background-color: #f7dc6f; padding-left: 15px; display: inline-block;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                    fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg> <em><b> Sus documentos han sido validados. Por favor, acérquese a la Unidad de
                                        Afiliación
                                        para entregar los documentos en físico, caso contrario su proceso de afiliación no
                                        será concluido.</b></em>
                            </p>
                        </div>
                    @endif
                </div>

                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header p-1" style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0e6296"
                            class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                        </svg>
                        <b>DATOS PERSONALES </b>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <label for="nombres"> NOMBRES:</label>
                            <input class="form-control" id="nombres" disabled wire:model=nombres
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <label for="p_apellido"> PRIMER APELLIDO:</label>
                            <input class="form-control" id="p_apellido" disabled wire:model=p_apellido
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <label for="s_apellido"> SEGUNDO APELLIDO:</label>
                            <input class="form-control" id="s_apellido" disabled wire:model=s_apellido
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                            <label for="ci"> C.I.:</label>
                            <input class="form-control" id="ci" disabled wire:model=ci
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                        <div class='col-lg-2 col-md-3 col-sm-6 col-6'>
                            <label for="expedido_ci">EXP.:</label>
                            <input class="form-control" id="expedido_ci" wire:model="expedido_ci" disabled
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-12 col-6">
                            <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:</label>
                            <input class="form-control" id="fecha_nacimiento" disabled wire:model=fecha_nacimiento
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-6">
                            <label for="sexo"> SEXO:</label>
                            <input class="form-control" id="sexo" wire:model="sexo" disabled
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7 col-md-6 col-sm-12 col-6">
                            <label for="estado_civil"> ESTADO CIVIL:</label>
                            <input class="form-control" id="estado_civil" wire:model="estado_civil" disabled
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                        <div class='col-lg-5 col-md-6 col-sm-6 col-6'>
                            <label for="es_extranjero">TIPO DOC.:</label>
                            <input class="form-control" id="es_extranjero" wire:model="es_extranjero" disabled
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                        </div>
                    </div>
                    @if ($tieneNacimiento == false)
                        <div class="row">
                            <div class='col-lg-4 col-md-4 col-sm-6 col-12'>
                                <label for="dep_nacimiento">DEP. NAC.:</label>
                                <input class="form-control" id="dep_nacimiento" wire:model="dep_nacimiento" disabled
                                    style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                            </div>
                            <div class='col-lg-4 col-md-4 col-sm-6 col-12'>
                                <label for="pro_nacimiento">PRO. NAC.:</label>
                                <input class="form-control" id="pro_nacimiento" wire:model="pro_nacimiento" disabled
                                    style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                            </div>
                            <div class='col-lg-4 col-md-4 col-sm-6 col-12'>
                                <label for="mun_nacimiento">MUN. NAC.:</label>
                                <input class="form-control" id="mun_nacimiento" wire:model="mun_nacimiento" disabled
                                    style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                    @endif

                </div>
                <div class="row">
                    <!--  datos universitarios -->
                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                            <div class="card-header p-1"
                                style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0e6296"
                                    class="bi bi-building-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                                </svg>
                                <b>DATOS UNIVERSITARIOS </b>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <strong> REGISTRO UNIVERSITARIO:</strong>
                                    <p class="p-comun-disabled p-1">{{ $registro_universitario }} </p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <strong> GESTION DE INGRESO:</strong>
                                    <p class="p-comun-disabled p-1">{{ $gestion_ingreso }} </p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong> SEMESTRE
                                        ACTUAL:</strong>
                                    <p class="p-comun-disabled p-1">{{ $semestre_actual }} </p>

                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong> CORREO
                                        INSTITUCIONAL:</strong>
                                    <p class="p-comun-disabled p-1">{{ $correo_institucional }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong> CARRERA:</strong>
                                    <p class="p-comun-disabled p-1">{{ $carrera }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong> FACULTAD:</strong>
                                    <p class="p-comun-disabled p-1">{{ $facultad }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                            <div class="card-header p-1"
                                style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0e6296"
                                    class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                                </svg>
                                <b>DATOS REFERENCIALES </b>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong> NOMBRE DE
                                        REFERENCIA:</strong>
                                    <p class="p-comun-disabled p-1">{{ $nombre_referencia }} </p>
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-6 col-6">
                                    <strong> TELEFONO DE
                                        REFERENCIA:</strong>
                                    <p class="p-comun-disabled p-1">{{ $telefono_referencia }} </p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                                    <strong> TELEFONO:</strong>
                                    <p class="p-comun-disabled p-1">{{ $telefono }} </p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                                    <strong> TIPO DE SANGRE:</strong>
                                    <p class="p-comun-disabled p-1">{{ $grupo_sanguineo }} </p>
                                </div>
                                <div class="col-lg-12 col-md-4 col-sm-12 col-12">
                                    <strong> ALERGIAS:</strong>
                                    <p class="p-comun-disabled p-1">{{ $alergias }} </p>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                                    <strong> ZONA:</strong>
                                    <p class="p-1" id="alergias"
                                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;"
                                        disabled id="alergias">{{ $zona }} </p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                    <strong> NRO VIVIENDA:</strong>
                                    <p class="p-1" id="alergias"
                                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;"
                                        disabled id="alergias">{{ $nro_vivienda }} </p>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong> DIRECCIÓN:</strong>
                                    <p class="p-1" id="alergias"
                                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;"
                                        disabled id="alergias">{{ $direccion }} </p>
                                </div>
                            </div>

                            {{-- - <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                                    <x-ver-dos-button wire:click="AbrirModalUbicacionEstudiante">UBICACIÓN
                                    </x-ver-dos-button>
                                    @include('livewire.Afiliacion.ssue.ModalUbicacionEstudiante')
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($mostrarCarnet)
            <div class="popup-container" id="carnetPopup">
                <div class="profile-card-1">
                    <x-cancel-button type="button" class="close-button" title="CERRAR" wire:click="ocultarCarnet">

                    </x-cancel-button>
                    <div class="img">
                        <img src="data:image/png;base64,{{ $foto ?? '' }}">
                    </div>
                    <div class="mid-section">
                        <div class="name">
                            <span>MAT: {{ $matricula ?? '' }} </span><br>
                            <span>CI: {{ $ci ?? '' }} </span>
                        </div>
                        <div class="description">
                            <em>{{ $nombres ?? '' }} {{ $p_apellido ?? '' }} {{ $s_apellido ?? '' }} </em><br>
                            <span> <b>{{ $edad ?? '' }}</b> Años </span>
                        </div>
                        <div class="line"></div>
                        <br>
                        <div class="datos">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td><b>R.U: </b></td>
                                        <td class="px-3">{{ $registro_universitario ?? '' }} </td>
                                    </tr>
                                    <tr>
                                        <td><b>FACULTAD:</b></td>
                                        <td class="px-3">{{ $facultad ?? '' }} </td>
                                    </tr>
                                    <tr>
                                        <td><b>CARRERA: </b></td>
                                        <td class="px-3">{{ $carrera ?? '' }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <br>
                        <!-- <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                                                                                                                                                                                                                                                                                                                                                                                                                                                      alt="Código QR" width="150" height="150" class="qr"/>-->
                        <!-- <img src="data:image/png;base64,{{ $qrCode ?? '' }}" > -->

                        <div class="datos" style="text-align:right;">
                            <p style="font-size:18px;"><b>{{ $grupo_sanguineo }}</b> </p>

                        </div>
                    </div>
                    <div class="links">
                        <p class="fb" href="">
                            <span>Fecha Afiliación:</span><br>
                            <span>{{ $fecha_afiliacion ?? '' }}</span>
                        </p>
                        <p class="follow" href="">
                            <span>Fecha Vencimiento: </span><br>
                            <span>{{ $fecha_vencimiento ?? '' }}</span>
                        </p>
                    </div>
                </div>
            </div>


            <style>
                .popup-container {
                    display: flex;
                    /* Visible cuando $mostrarCarnet es true */
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                }

                .profile-card-1 {
                    width: 380px;
                    height: 600px;
                    background: white;
                    margin: 0 auto;
                    border-radius: 10px;
                    text-align: center;
                    box-shadow: 4px 4px 10px #999;
                    position: relative;
                    box-sizing: border-box;
                    overflow: hidden;
                    font-family: Verdana, sans-serif;
                }

                .profile-card-1 .img img {
                    width: 160px;
                    height: 160px;
                    padding: 3px;
                    border-radius: 50%;
                    border: 3px solid rgba(255, 255, 255, 0.6);
                    position: absolute;
                    left: calc(50% - 84px);
                    top: 26px;
                }

                .profile-card-1 .img {
                    height: 130px;
                    width: 100%;
                    background-image: linear-gradient(rgba(100, 144, 211, 0.8), rgba(5, 34, 78, 0.97)), url("bac.jpg");
                    padding: 20px;
                    box-sizing: border-box;
                    position: relative;
                }

                .profile-card-1 .mid-section {
                    position: absolute;
                    height: 200px;
                    width: 100%;
                    top: 200px;
                    left: 0;
                    padding: 10px 20px 0;
                    box-sizing: border-box;
                    background: white;
                }

                .profile-card-1 .mid-section .name {
                    color: #333333;
                    font-size: 1.4em;
                    padding-top: 5px;
                    background: rgba(255, 255, 255, 0.1);
                    font-weight: bold;
                }

                .profile-card-1 .mid-section .description {
                    color: black;
                    text-decoration: none;
                    font-size: 16px;
                }

                .profile-card-1 .mid-section .datos {
                    color: black;
                    text-decoration: none;
                    font-size: 16px;
                    text-align: left;
                }

                .profile-card-1 .mid-section .description a {
                    color: inherit;
                    text-decoration: none;
                    font-weight: bold;
                }

                .profile-card-1 .mid-section .line {
                    background: #1a5276;
                    width: 80%;
                    height: 2px;
                    margin: 5px auto -3px;
                }

                .profile-card-1 .mid-section .stats {
                    display: flex;
                    position: absolute;
                    left: 10%;
                    padding-top: 10px;
                    width: 80%;
                    justify-content: space-around;
                }

                .profile-card-1 .mid-section .stats .stat {
                    font-size: 1.3em;
                    color: #333333;
                    padding: 5px;
                    font-weight: bold;
                }

                .profile-card-1 .mid-section .stats .stat .subtext {
                    color: gray;
                    font-size: 0.6em;
                    font-weight: normal;
                }

                .profile-card-1 .links {
                    width: 100%;
                    height: 46px;
                    background: #f2f2f2;
                    border-top: 1px solid white;
                    margin-top: 35px;
                    border-radius: 5px;
                    color: white;
                    font-size: 13px;
                    position: absolute;
                    bottom: 0;
                    text-align: center;

                }

                .profile-card-1 .links .fb {
                    position: absolute;
                    left: 0;
                    width: 50%;
                    height: 100%;

                    background-color: #3b5998;

                }

                .profile-card-1 .links .fb:hover {
                    background-color: #2162db;

                }

                .profile-card-1 .links p {
                    color: white;
                }

                .profile-card-1 .links .follow {
                    position: absolute;
                    left: 50%;
                    width: 50%;
                    height: 100%;

                    background-color: #cb4335;
                }

                .profile-card-1 .links .follow:hover {
                    background-color: #e74c3c;
                }

                /* image after effect */
                .profile-card-1 .img::after {
                    content: "";
                    position: absolute;
                    width: 180px;
                    height: 180px;
                    border-radius: 50%;
                    left: calc(50% - 91.5px);
                    top: 20px;
                    border: 3px solid rgba(255, 255, 255, 0.4);
                }

                /* image before effect */
                .profile-card-1 .img::before {
                    content: "";
                    position: absolute;
                    width: 190px;
                    height: 190px;
                    border-radius: 50%;
                    left: calc(50% - 95px);
                    top: 15.5px;
                    border: 2px solid rgba(255, 255, 255, 0.2);
                }

                .profile-card-1 .view-more {
                    position: absolute;
                    top: calc(50% - 0.5em);
                    left: calc(50% - 0.5em);
                    z-index: 2;
                    font-size: 2em;
                    color: #2ecc71;
                }

                .profile-card-1 .view-more .fa-plus-circle {
                    position: absolute;
                    background: white;
                    border-radius: 50%;
                    width: 1.004em;
                }

                .close-button {
                    position: absolute;
                    top: 10px;
                    /* Ajusta la distancia desde la parte superior */
                    right: 15px;
                    /* Ajusta la distancia desde la derecha */
                    background: none;
                    color: red;
                    border: none;
                    font-size: 20px;
                    font-weight: bold;
                    cursor: pointer;
                    z-index: 1001;
                    /* Asegura que esté por encima del contenido del popup */
                    padding: 0;
                    line-height: 1;
                }

                .close-button:hover {
                    color: white;
                }

                .close-button:focus {
                    outline: none;
                }

                .qr {
                    position: absolute;
                    margin-top: 10px;
                    margin-LEFT: 440px;

                    width: 150px;
                    height: 150px;

                }
            </style>
        @endif

    @endcan

    <!--Modal para ver Fotocopia Ci -->
    @include('livewire.Afiliacion.ssue.modalFotocopiaCi')
    <!--Modal para ver Matricula -->
    @include('livewire.Afiliacion.estudiantes-ssue.modalMatricula')
    <!--Modal para ver Boleta de Inscripcion -->
    @include('livewire.Afiliacion.estudiantes-ssue.modalBoletaInscripcion')
    <!--Modal para ver Form no afiliacion -->
    @include('livewire.Afiliacion.estudiantes-ssue.modalFormNoAfiliacion')
    <!--Modal para ver Certificado de cajas -->
    @include('livewire.Afiliacion.estudiantes-ssue.modalCertificadoCajas')
    <!--Modal para ver Tipo Sangre -->
    @include('livewire.Afiliacion.estudiantes-ssue.modaltipoSangre')


</div>
<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('carnetRendered',
            function() { // Emitiremos este evento desde el componente después de mostrar el carnet
                window.onclick = function(event) {
                    var modal = document.getElementById('carnetPopup');
                    if (modal && event.target === modal) {
                        Livewire.emit('ocultarCarnet');
                    }
                };
            });
    });
</script>
