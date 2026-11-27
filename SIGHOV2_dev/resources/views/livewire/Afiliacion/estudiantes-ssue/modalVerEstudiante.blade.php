<div>
    <x-ver-dos-modal id="verModalEstudiante" labelledby="verModalEstudianteLabel" title=" VISTA DE ESTUDIANTE  ">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-12 ">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:#1a5276; color:white;text-align:center;border-radius:8px">
                        ESTUDIANTE</p>

                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-6 col-12">
                            <div class=" row d-flex flex-column align-items-center">
                                <!-- Paso 4. Mostramos la foto en base 64 -->
                                <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail" style="width:200px; height:200px; object-fit:cover; object-position: top;" alt="FTP.{{ $foto }}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-6 col-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12"
                                    style="display: flex; justify-content: center;">
                                    @if ($estado_validar == 'AFILIADO')
                                        <span class="h3" style="color: green;"><b>AFILIADO</b></span>
                                    @elseif($estado_validar == 'PENDIENTE')
                                        <span class="h3 parpadeo" style="color: #eaa719;"><b>PENDIENTE</b></span>
                                    @elseif($estado_validar == 'OBSERVADO')
                                        <span class="h3 parpadeo" style="color: red;"><b>OBSERVADO</b></span>
                                    @endif
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center  ">
                                    <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $edad }}
                                        años</span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center ">
                                    <strong style="font-size:90%">MATRÍCULA :</strong>
                                    <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }}
                                    </span>
                                </div>
                            </div>
                            <hr class="p-0 m-1">
                            <div class="row">
                                <div class="col-lg-12 col-md-6 col-sm-12 col-4 text-center">
                                    <strong> FECHA DE SOLICITUD: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $fecha_solicitud }} </p>
                                </div>
                                @if ($fecha_afiliacion != null)
                                    <div class="col-lg-12 col-md-6 col-sm-12 col-4  text-center">
                                        <strong> FECHA DE INGRESO A SSU: </strong>
                                        <p class="p-1"
                                            style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $fecha_afiliacion }} </p>
                                    </div>
                                @endif
                                @if ($fecha_reafiliacion != null)
                                    <div class="col-lg-12 col-md-6 col-sm-12 col-4  text-center">
                                        <strong> FECHA DE AFILIACIÓN:</strong>
                                        <p class="p-1"
                                            style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $fecha_reafiliacion }} </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="p-0 m-0">
                    <!--     PARTE DE VER LOS PDFs -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 table-responsive">
                            <table width="100%">
                                <tr>
                                    <td width="80%" class=" pt-2 pb-2 mr-2">
                                        <x-ver-pdf-button style="padding:2px;" wire:click='fotocopia_ci'>FOTOCOPIA DE
                                            CARNET</x-ver-pdf-button>
                                        <div wire:loading wire:target="fotocopia_ci">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td width="20%" class=" pt-2 pb-2 mr-2 pl-2">
                                        <x-descargar-button style="padding:4px;"
                                            wire:click='descargarCarnet'></x-descargar-button>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%" class=" pt-2 pb-2 mr-2">
                                        <x-ver-pdf-button style="padding:2px; widht:100%"
                                            wire:click='matriculas'>MATRICULA</x-ver-pdf-button>
                                        <div wire:loading wire:target="matriculas">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td width="20%" class=" pt-2 pb-2 mr-2 pl-2">
                                        <x-descargar-button style="padding:4px;"
                                            wire:click='descargarMatricula'></x-descargar-button>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%" class=" pt-2 pb-2 mr-2">
                                        <x-ver-pdf-button style="padding:2px" wire:click='boleta'>BOLETA DE
                                            INSCRIPCIÓN</x-ver-pdf-button>
                                        <div wire:loading wire:target="boleta">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td width="20%" class=" pt-2 pb-2 mr-2 pl-2">
                                        <x-descargar-button style="padding:4px;"
                                            wire:click='descargarBoleta'></x-descargar-button>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%" class=" pt-2 pb-2 mr-2">
                                        <x-ver-pdf-button style="padding:2px" wire:click='form_gestora'>FORM NO
                                            AFILIACIÓN - GESTORA</x-ver-pdf-button>
                                        <div wire:loading wire:target="form_gestora">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td width="20%" class=" pt-2 pb-2 mr-2 pl-2">
                                        <x-descargar-button style="padding:4px;"
                                            wire:click='descargarGestora'></x-descargar-button>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%" class=" pt-2 pb-2 mr-2">
                                        <x-ver-pdf-button style="padding:2px"
                                            wire:click='certificado_cajas_101'>FORMULARIO
                                            101</x-ver-pdf-button>
                                        <div wire:loading wire:target="certificado_cajas_101">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td width="20%" class=" pt-2 pb-2 mr-2 pl-2">
                                        <x-descargar-button style="padding:4px;"
                                            wire:click='descargarForm101'></x-descargar-button>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%" class=" pt-2 pb-2 mr-2">
                                        <x-ver-pdf-button style="padding:2px" wire:click='tipo_sangre'>TIPO DE
                                            SANGRE</x-ver-pdf-button>
                                        <div wire:loading wire:target="tipo_sangre">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td width="20%" class=" pt-2 pb-2 mr-2 pl-2">
                                        <x-descargar-button style="padding:4px;"
                                            wire:click='descargarTipoSangre'></x-descargar-button>

                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="row ">
                        {{-- <div class="col-lg-12 col-md-4 col-sm-4 col-4 p-2">
                            <x-ver-pdf-button style="padding:2px" wire:click='ver_formulario_101'>FORMUALRIO 101 EJEMPLO</x-ver-pdf-button>
                            <div wire:loading wire:target="tipo_sangre">
                                <x-cargando-dos />
                            </div>

                            <a href="#" wire:click='ver_formulario_101'
                            onclick="window.open('{{ $this->url }}', '_blank', 'width=900,height=700,resizable=yes,scrollbars=yes'); return false;" 
                            class="btn btn-primary">
                             Ver certificado en ventana emergente
                         </a>

                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <strong> NOMBRES:</strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $nombres }} </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <strong> PRIMER APELLIDO: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $p_apellido }} </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <strong> SEGUNDO APELLIDO:</strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $s_apellido }} </p>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-7 col-7">
                            <strong> C.I.: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $ci }} </p>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-5 col-5">
                            <strong> EXP.: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $expedido_ci }} </p>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-6 col-12">
                            <strong> FECHA DE NACIMIENTO: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $fecha_nacimiento }} </p>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                            <strong> SEXO: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $sexo }} </p>
                        </div>

                        <div class="col-lg-5 col-md-4 col-sm-6 col-6">
                            <strong> TIPO DOC.: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $es_extranjero }} </p>
                        </div>

                        <div class="col-lg-7 col-md-6 col-sm-6 col-12">
                            <strong> ESTADO CIVIL: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $estado_civil }} </p>
                        </div>
                        <div class='col-lg-4 col-md-6 col-sm-6 col-12'>
                            <strong> DEPARTAMENTO DE NACIMIENTO:</strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $dep_nacimiento }} </p>
                        </div>
                        <div class='col-lg-4 col-md-6 col-sm-6 col-12'>
                            <strong> PROVINCIA DE NACIMIENTO: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $pro_nacimiento }} </p>
                        </div>
                        <div class='col-lg-4 col-md-6 col-sm-6 col-12'>
                            <strong> MUNICIPIO DE NACIMIENTO:</strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $mun_nacimiento }} </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                            <p style="background-color:#1a5276; color:white;text-align:center;border-radius:6px">
                                DATOS UNIVERSITARIOS
                            </p>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <strong> REGISTRO UNIVERSITARIO:</strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $registro_universitario }} </p>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <strong> GESTION DE INGRESO:</strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $gestion_ingreso }} </p>
                                </div>

                            </div>
                            <div class="row ">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <strong> CORREO UNIVERSITARIO: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $correo_institucional }} </p>

                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <strong> SEMESTRE ACTUAL:</strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $semestre_actual }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong> CARRERA: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $carrera }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong> FACULTAD: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $facultad }} </p>

                                </div>
                            </div>
                            <div>
                                <x-ver-button wire:click='ver_afiliado({{ $this->idEst }})'> DATOS
                                    ACADÉMICOS</x-ver-button>
                            </div>

                        </div>

                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                            <p style="background-color:#1a5276; color:white;text-align:center; border-radius:6px">
                                DATOS REFERENCIALES
                            </p>
                            <div class="row">
                                <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                                    <strong> NOMBRE DE REFERENCIA: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $nombre_referencia }} </p>
                                </div>
                                <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                                    <strong> TELEFONO DE REFERENCIA: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $telefono_referencia }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                                    <strong> TELEFONO: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $telefono }} </p>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                                    <strong> TIPO DE SANGRE: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $grupo_sanguineo }} </p>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <strong> ZONA: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $zona }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-9 col-md-12 col-sm-12 col-12">
                                    <strong> DIRECCIÓN: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $direccion }} </p>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                                    <strong> NRO DE VIVIENDA: </strong>
                                    <p class="p-1"
                                        style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $nro_vivienda }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <label for="alergias"> ALERGIAS: </label>
                                    <textarea class="form-control" name="alergias" id="alergias" type='text' disabled
                                        style="padding-rigth:1;  border-radius:10px;" wire:model=alergias> </textarea>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <label for="observaciones"> OBSERVACIONES:
                                    </label>
                                    <textarea class="form-control" id="observaciones" type='text' disabled
                                        style="padding-rigth:1;  border-radius:10px;" wire:model=observaciones> </textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div wire:loading wire:target="AbrirModalUbicacionEstudiante">
                                        <x-cargando-dos />
                                    </div>
                                    <x-ver-dos-button wire:click="AbrirModalUbicacionEstudiante">UBICACIÓN
                                    </x-ver-dos-button>
                                    @include('livewire.Afiliacion.ssue.ModalUbicacionEstudiante')
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        @can('super-admin-only')
            <div style="height: 0.5rem;"></div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                            <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Nombre de usuario:</strong>
                                {{ $user_created }}
                            </li>
                            <li>
                                <strong>Fecha de registro:</strong>
                                {{ $fecha_created }}
                            </li>
                        </ul>
                    </div>
                </div>
                @if ($tieneEdicion === true)
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 font-weight-bold border-bottom pb-1" style="color: orange;">
                                <i class="fas fa-check-circle mr-1"></i> MODIFICADO POR
                            </h6>

                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $user_edited }}
                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong>
                                    {{ $fecha_edicion }}
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif

            </div>
        @endcan
    </x-ver-dos-modal>
</div>
<script>
    window.addEventListener('modalVerEstudiante', event => {

        $('#verModalEstudiante').modal('show');
    });

    function openPdfPopup() {
        const base64Pdf = @json($pdf_ver);

        // Convertir Base64 a un objeto Blob
        const byteCharacters = atob(base64Pdf.split(',')[1]);
        const byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], {
            type: 'application/pdf'
        });

        // Crear una URL para el Blob
        const blobUrl = URL.createObjectURL(blob);

        // Abrir la URL en una nueva ventana
        const width = 800;
        const height = 600;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);

        window.open(blobUrl, 'PDF', `width=${width},height=${height},top=${top},left=${left}`);
    }
</script>

<style>
    @keyframes parpadeo {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .parpadeo {
        animation: parpadeo 1s infinite;
    }
</style>
