<div>
    @can('registro.afiliacion_ssue')
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <div class="card p-2 card-comun">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($errors->has('error_message'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('error_message') }}
                                </div>
                            @endif

                            <input class="form-control form-control-sm @error('foto') is-invalid @enderror" type="file"
                                id="formFile" name="archivo" required wire:model.ignore="foto" style="border-radius:8px"
                                accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                            @error('foto')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                            @if ($foto)
                                <div class=" d-flex flex-column align-items-center">
                                    <img src="{{ $foto->temporaryUrl() }}"
                                        style=" width: 200px; height: 200px; object-fit: cover;" class="img-thumbnail">
                                </div>
                            @elseif ($fotoAntigua)
                                <div class=" d-flex flex-column align-items-center">
                                    <img src="{{ !empty($fotoAntigua) ? 'data:image/jpg;base64,' . $fotoAntigua : asset('images/default_afiliacion.jpg') }}"
                                        style=" width: 200px; height: 200px; object-fit: cover;" class="img-thumbnail">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <span class=" h5 font-weight-bold">{{ $edad }} años </span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  text-center">
                            <strong style="font-size:90%">MATRICULA :</strong>
                            <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-4 col-sm-12 col-xs-12">
                            <strong> FECHA DE SOLICITUD: </strong>
                            <p class="p-1"
                                style=" background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $today }}
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-12 col-xs-12">
                            <strong> FECHA DE INGRESO A SSU: </strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8; padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $fecha_afiliacion }}
                            </p>
                        </div>
                        @if ($fecha_reafiliacion != null)
                            <div class="col-lg-12 col-md-4 col-sm-12 col-xs-12">
                                <strong> FECHA DE AFILIACION: </strong>
                                <p class="p-1"
                                    style=" background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $fecha_reafiliacion }} </p>
                            </div>
                        @endif
                    </div>

                    <div class="card-header p-1" style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0e6296"
                            class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                        <b>VERIFICACIÓN DE DOCUMENTOS </b>
                    </div>

                    <div class="row">
                        @if ($tieneDocumentos)
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td><b>FOTOCOPIA DE CARNET:</b> </td>
                                        <td>
                                            @if ($estado_carnet === 2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @elseif ($estado_carnet === 3)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($estado_carnetOriginal == 3)
                                        <tr>
                                            <td colspan="2">
                                                <div class=" alert alert-warning d-flex align-items-center gap-1 p-1 mb-1 "
                                                    role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                    <span>
                                                        Subir el archivo de fotocopia de carnet (PDF).
                                                    </span>
                                                </div>
                                                <input
                                                    class="form-control @error('file_fotocopiaCarnet') is-invalid @enderror"
                                                    type="file" id="formfile_fotocopiaCarnet" required
                                                    wire:model="file_fotocopiaCarnet" accept=".pdf"
                                                    wire:change="estadoCarnet">
                                                @error('file_fotocopiaCarnet')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div wire:loading wire:target="file_fotocopiaCarnet"> <x-cargando-dos />
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><b>MATRICULA:</b> </td>
                                        <td>
                                            @if ($estado_matricula === 2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @elseif ($estado_matricula === 3)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($estado_matriculaOriginal == 3)
                                        <tr>
                                            <td colspan="2">
                                                <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                    role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                    <span>
                                                        Subir el archivo de matricula (PDF).
                                                    </span>
                                                </div>
                                                <input class="form-control @error('file_matricula') is-invalid @enderror"
                                                    type="file" id="formfile_matricula" required
                                                    wire:model="file_matricula" accept=".pdf"
                                                    wire:change="estadoMatricula">
                                                @error('file_matricula')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div wire:loading wire:target="file_matricula"> <x-cargando-dos />
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><b>BOLETA DE INSCRIPCION:</b> </td>
                                        <td>
                                            @if ($estado_boleta === 2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @elseif ($estado_boleta === 3)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($estado_boletaOriginal == 3)
                                        <tr>
                                            <td colspan="2">
                                                <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                    role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                    <span>
                                                        Subir el archivo de boleta de inscripción (PDF).
                                                    </span>
                                                </div>
                                                <input
                                                    class="form-control @error('file_boletaInscripcion') is-invalid @enderror"
                                                    type="file" id="formfile_boletaInscripcion" required
                                                    wire:model="file_boletaInscripcion" accept=".pdf"
                                                    wire:change="estadoBoleta">
                                                @error('file_boletaInscripcion')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div wire:loading wire:target="file_boletaInscripcion"> <x-cargando-dos />
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><b>FORM NO AFLIACÍÓN- GESTORA:</b> </td>
                                        <td>
                                            @if ($estado_certificado_gestora === 2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @elseif ($estado_certificado_gestora === 3)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($estado_certificado_gestoraOriginal == 3)
                                        <tr>
                                            <td colspan="2">
                                                <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                    role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                    <span>
                                                        Subir el archivo de form de no afiliación (PDF).
                                                    </span>
                                                </div>
                                                <input class="form-control @error('file_enteGestor') is-invalid @enderror"
                                                    type="file" id="formfile_enteGestor" required
                                                    wire:model="file_enteGestor" accept=".pdf"
                                                    wire:change="estadoCertificadoGestora">
                                                @error('file_enteGestor')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div wire:loading wire:target="file_enteGestor"> <x-cargando-dos />
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><b>FORMULARIO 101:</b> </td>
                                        <td>
                                            @if ($estado_certificado_cajas === 2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @elseif ($estado_certificado_cajas === 3)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($estado_certificado_cajasOriginal == 3)
                                        <tr>
                                            <td colspan="2">
                                                <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                    role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                    <span>
                                                        Subir el archivo certificado de cajas (PDF).
                                                    </span>
                                                </div>
                                                <input
                                                    class="form-control @error('file_certificadoCajas') is-invalid @enderror"
                                                    type="file" id="formfile_certificadoCajas" required
                                                    wire:model="file_certificadoCajas" accept=".pdf"
                                                    wire:change="estadoCertificadoCajas">
                                                @error('file_certificadoCajas')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div wire:loading wire:target="file_certificadoCajas"> <x-cargando-dos />
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><b>TIPO DE SANGRE:</b>
                                            <spam style="color:#106faf;"><b> (opcional)</b>
                                            </spam>
                                        </td>
                                        <td>
                                            @if ($estado_tipo_sangre === 2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @elseif ($estado_tipo_sangre === 3)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($estado_tipo_sangreOriginal == 3)
                                        <tr>
                                            <td colspan="2">
                                                <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                    role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                    <span>
                                                        Subir el archivo de tipo de sangre (PDF).
                                                    </span>
                                                    <spam style="color:#106faf;"><b> (opcional)</b>
                                                    </spam>
                                                </div>

                                                <input class="form-control form-control-sm " type="file"
                                                    id="formfile_tipo_sangre" required wire:model="file_tipo_sangre"
                                                    accept=".pdf" wire:change="estadoTipoSangre">

                                                @error('file_tipo_sangre')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div wire:loading wire:target="file_tipo_sangre"> <x-cargando-dos />
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td><b>FOTOCOPIA DE CARNET:</b> </td>
                                        <td>
                                            @if ($file_fotocopiaCarnet2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class=" alert alert-warning d-flex align-items-center gap-1 p-1 mb-1 "
                                                role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                <span>
                                                    Subir el archivo de fotocopia de carnet (PDF).
                                                </span>
                                            </div>
                                            <input
                                                class="form-control form-control-sm @error('file_fotocopiaCarnet2') is-invalid @enderror"
                                                type="file" id="formfile_fotocopiaCarnet" required
                                                wire:model.live="file_fotocopiaCarnet2" accept=".pdf"
                                                style="border-radius:8px">
                                            @error('file_fotocopiaCarnet2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div wire:loading wire:target="file_fotocopiaCarnet2"> <x-cargando-dos />
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><b>MATRICULA:</b> </td>
                                        <td>
                                            @if ($file_matricula2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                <span>
                                                    Subir el archivo de matricula (PDF).
                                                </span>
                                            </div>
                                            <input
                                                class="form-control form-control-sm @error('file_matricula2') is-invalid @enderror"
                                                type="file" id="formfile_matricula" required
                                                wire:model="file_matricula2" accept=".pdf" style="border-radius:8px">
                                            @error('file_matricula2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div wire:loading wire:target="file_matricula2"> <x-cargando-dos /> </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>BOLETA DE INSCRIPCION:</b> </td>
                                        <td>
                                            @if ($file_boletaInscripcion2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                role="alert" style="font-size: 12px;">
                                                <span>
                                                    Subir el archivo de boleta de inscripción (PDF).
                                                </span>
                                            </div>
                                            <input
                                                class="form-control form-control-sm @error('file_boletaInscripcion2') is-invalid @enderror"
                                                type="file" id="formfile_boletaInscripcion" required
                                                wire:model="file_boletaInscripcion2" accept=".pdf"
                                                style="border-radius:8px">
                                            @error('file_boletaInscripcion2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div wire:loading wire:target="file_boletaInscripcion2"> <x-cargando-dos />
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><b>FORM NO AFLIACÍÓN- GESTORA:</b> </td>
                                        <td>
                                            @if ($file_enteGestor2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                role="alert" style="font-size: 12px; background-color:#f7dc6f">
                                                <span>
                                                    Subir el archivo de form de no afiliación (PDF).
                                                </span>
                                            </div>
                                            <input
                                                class="form-control form-control-sm @error('file_enteGestor2') is-invalid @enderror"
                                                type="file" id="formfile_enteGestor2" required
                                                wire:model="file_enteGestor2" accept=".pdf" style="border-radius:8px">
                                            @error('file_enteGestor2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div wire:loading wire:target="file_enteGestor2"> <x-cargando-dos /> </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>FORMULARIO 101:</b> </td>
                                        <td>
                                            @if ($file_certificadoCajas2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                role="alert" style="font-size: 12px;background-color:#f7dc6f">
                                                <span>
                                                    Subir el archivo certificado de cajas (PDF).
                                                </span>
                                            </div>
                                            <input
                                                class="form-control form-control-sm @error('file_certificadoCajas2') is-invalid @enderror"
                                                type="file" id="formfile_certificadoCajas2" required
                                                wire:model="file_certificadoCajas2" accept=".pdf"
                                                style="border-radius:8px">
                                            @error('file_certificadoCajas2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div wire:loading wire:target="file_certificadoCajas2"> <x-cargando-dos />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>TIPO DE SANGRE:</b> </td>
                                        <td>
                                            @if ($file_tipo_sangre2)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#e7a10c" class="bi bi-emoji-smile-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="#950d0d" class="bi bi-emoji-frown-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m-2.715 5.933a.5.5 0 0 1-.183-.683A4.5 4.5 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.5 3.5 0 0 0 8 10.5a3.5 3.5 0 0 0-3.032 1.75.5.5 0 0 1-.683.183M10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="alert alert-warning d-flex align-items-center gap-1 p-1 mb-1"
                                                role="alert" style="font-size: 12px;background-color:#f7dc6f">
                                                <span>
                                                    Subir el archivo de tipo de sangre (PDF).
                                                </span>
                                                <spam style="color:#106faf;"><b> (opcional)</b>
                                                </spam>
                                            </div>
                                            <input class="form-control form-control-sm " type="file"
                                                id="formfile_tipo_sangre2" required wire:model="file_tipo_sangre2"
                                                accept=".pdf" style="border-radius:8px">

                                            @error('file_tipo_sangre2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div wire:loading wire:target="formfile_tipo_sangre2"> <x-cargando-dos />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <label class="mr-2"> SOLICITUD: </label>
                            @if ($estado_validar == 'PENDIENTE')
                                <span style="color: #F6C105;font-size:40px"><b>PENDIENTE</b></span>
                            @elseif($estado_validar == 'AFILIADO')
                                <span style="color: green; font-size:40px"><b>AFILIADO</b></span>
                            @elseif($estado_validar == 'OBSERVADO')
                                <span style="color: red; font-size:40px"><b>OBSERVADO</b></span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <p class="h6"
                                style="border: 2px solid #d4ac0d; border-radius: 5px; background-color: #f7dc6f; padding-left: 15px; display: inline-block;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                    <path
                                        d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13.113 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                                    <path
                                        d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                </svg> <em><b> Lee detalladamente las observaciones, y sube los documentos si es
                                        necesario.</b></em>
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong style="color:#0e6296; font-size:20px">OBSERVACIONES:</strong>
                            <textarea class="form-control" style=" border-color:white; border-radius:10px; font-size:20px; font-weight: bold;"
                                wire:model=observacion disabled></textarea>
                        </div>
                    </div>
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
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombres"> NOMBRES:</label>
                            <input class="form-control" id="nombres" disabled wire:model=nombres
                                style=" border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="p_apellido"> PRIMER APELLIDO:</label>
                            <input class="form-control" id="p_apellido" disabled wire:model=p_apellido
                                style=" border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="s_apellido"> SEGUNDO APELLIDO:</label>
                            <input class="form-control" id="s_apellido" disabled wire:model=s_apellido
                                style=" border-color:white; border-radius:10px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> C.I.:</label>
                            <input class="form-control" id="ci" disabled wire:model=ci
                                style=" border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <label for="expedido_ci">EXP.:</label>
                            <input class="form-control" id="expedido_ci" wire:model="expedido_ci" disabled
                                style=" border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:</label>
                            <input class="form-control" id="fecha_nacimiento" disabled wire:model=fecha_nacimiento
                                style=" border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo"> SEXO:</label>
                            <input class="form-control" id="sexo" wire:model="sexo" disabled
                                style=" border-color:white; border-radius:10px;">
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="estado_civil"> ESTADO CIVIL:</label>
                            <input class="form-control" id="estado_civil" wire:model="estado_civil" disabled
                                style=" border-color:white; border-radius:10px;">
                        </div>

                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="es_extranjero">TIPO C.I.:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <select wire:model.live="es_extranjero" name="es_extranjero" id="es_extranjero"
                                class="form-control @error('es_extranjero') is-invalid @enderror select2"
                                wire:change='esExtranjeroFunction()' style="width: 100%; border-radius:10px;"
                                tabindex="-1" required>
                                <option selected=" " disabled>Seleccione..
                                </option>
                                <option value="1">EXTRANJERO</option>
                                <option value="0">NACIONAL</option>
                            </select>
                        </div>
                    </div>
                    @if ($es_extranjero == false)
                        <div class="row">
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="id_dep_nacimiento">DEPARTAMENTO NACIMIENTO:<spam style="color:#cb4335;">
                                        <b>*</b>
                                    </spam>
                                </label>
                                <select wire:model.live="id_dep_nacimiento" name="id_dep_nacimiento"
                                    id="id_dep_nacimiento"
                                    class="form-control select2 @error('id_dep_nacimiento') is-invalid @enderror"
                                    style="width: 100%;  border-radius:10px;" tabindex="-1" required
                                    wire:change='ObtenerProvincia()'>
                                    <option selected=" ">Departamento de nacimiento </option>
                                    @foreach ($departamento as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="id_pro_nacimiento">PROVINCIA NACIMIENTO:<spam style="color:#cb4335;"><b>*</b>
                                    </spam>
                                </label>
                                <select wire:model.live="id_pro_nacimiento" name="id_pro_nacimiento"
                                    id="id_pro_nacimiento"
                                    class="form-control @error('id_pro_nacimiento') is-invalid @enderror select2"
                                    style="width: 100%; border-radius:10px;" tabindex="-1" required
                                    wire:change='ObtenerMunicipio()'>
                                    <option selected=" ">Provincia de nacimiento </option>
                                    @foreach ($this->provincias as $pro)
                                        <option value="{{ $pro->id }}">{{ $pro->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="id_mun_nacimiento">MUNICIPIO NACIMIENTO:<spam style="color:#cb4335;"><b>*</b>
                                    </spam>
                                </label>
                                <select wire:model.live="id_mun_nacimiento" id="id_mun_nacimiento"
                                    class="form-control @error('id_mun_nacimiento') is-invalid @enderror  select2"
                                    style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                    <option selected=" ">Municipio de nacimiento </option>
                                    @if ($this->municipios)
                                        @foreach ($this->municipios as $mun)
                                            <option value="{{ $mun->id }}">{{ $mun->nombre }}</option>
                                        @endforeach
                                    @else
                                        SIN DATOS
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
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
                                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                    <label type="number" for="registro_universitario"> REGISTRO DE UNIVERSITARIO:<spam
                                            style="color:#cb4335;"><b>*</b></spam></label>
                                    <input class="form-control @error('registro_universitario') is-invalid @enderror"
                                        id="registro_universitario" wire:model.live=registro_universitario
                                        style=" border-radius:10px;">
                                    @error('registro_universitario')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    <label for="gestion_ingreso">GESTION DE
                                        INGRESO:<spam style="color:#cb4335;"><b>*</b></spam></label>
                                    <input type="number"
                                        class="form-control @error('gestion_ingreso') is-invalid @enderror"
                                        id="gestion_ingreso" wire:model.live=gestion_ingreso style="  border-radius:10px;"
                                        min="1900" max="{{ date('Y') }}">
                                    @error('gestion_ingreso')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="semestre_actual"> SEMESTRE
                                        ACTUAL:<spam style="color:#cb4335;"><b>*</b></spam></label>
                                    <select wire:model.live="semestre_actual" style="border-radius:10px"
                                        class="select2 form-control @error('semestre_actual') is-invalid @enderror">
                                        <option value=" ">Seleccione un
                                            semestre </option>
                                        <option value="PRIMERO">PRIMERO </option>
                                        <option value="SEGUNDO">SEGUNDO </option>
                                        <option value="TERCERO">TERCERO </option>
                                        <option value="CUARTO">CUARTO </option>
                                        <option value="QUINTO"> QUINTO </option>
                                        <option value="SEXTO">SEXTO </option>
                                        <option value="SEPTIMO">SEPTIMO</option>
                                        <option value="OCTAVO">OCTAVO </option>
                                        <option value="NOVENO">NOVENO </option>
                                        <option value="DECIMO">DECIMO </option>
                                        <option value="EGRESADO">EGRESADO </option>
                                    </select>
                                    @error('semestre_actual')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="correo_institucional"> CORREO
                                        INSTITUCIONAL:<spam style="color:#cb4335;"><b>*</b></spam> </label>
                                    <input class="form-control @error('correo_institucional') is-invalid @enderror"
                                        id="correo_institucional" wire:model.live=correo_institucional
                                        style="border-radius:10px;">
                                    @error('correo_institucional')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="carrera"> CARRERA:<spam style="color:#cb4335;"><b>*</b></spam></label>
                                    <select wire:model.live="id_carrera" id="carrera"
                                        class="form-control @error('id_carrera') is-invalid @enderror"
                                        style="border-radius:10px" tabindex="-1" required
                                        wire:change="obtenerFacultad()">
                                        <option selected="selected">Seleccione la
                                            carrera</option>
                                        @foreach ($carrera as $car)
                                            <option value="{{ $car->id }}">
                                                {{ $car->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_carrera')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="facultad"> FACULTAD:<spam style="color:#cb4335;"><b>*</b></spam></label>
                                    <input class="form-control  @error('facultad') is-invalid @enderror" id="facultad"
                                        wire:model.live="facultad" disabled
                                        style=" border-color:white; border-radius:10px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nombre_referencia"> NOMBRE DE
                                        REFERENCIA:<spam style="color:#cb4335;"><b>*</b></spam></label>
                                    <input class="form-control @error('nombre_referencia') is-invalid @enderror"
                                        name="nombre_referencia" id="nombre_referencia" wire:model.live=nombre_referencia
                                        style="border-radius:10px;" oninput="this.value = this.value.toUpperCase()">
                                    @error('nombre_referencia')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    <label for="telefono_referencia"> TEL. / CEL. DE
                                        REFERENCIA:<spam style="color:#cb4335;"><b>*</b></spam> </label>
                                    <input class="form-control @error('telefono_referencia') is-invalid @enderror"
                                        name="telefono_referencia" id="telefono_referencia"
                                        wire:model.live=telefono_referencia style="border-radius:10px;">
                                    @error('telefono_referencia')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <label for="grupo_sanguineo"> TIPO DE SANGRE:<spam style="color:#106faf;"><b>
                                                (opcional)</b>
                                        </spam></label>
                                    <select wire:model="grupo_sanguineo" id="grupo_sanguineo"
                                        class="select2 form-control" style="border-radius:10px;">
                                        <option value="" disabled>Seleccione un tipo.. </option>
                                        <option value="A Rh+ ">A Rh+ </option>
                                        <option value="A Rh-">A Rh- </option>
                                        <option value="B Rh+">B Rh+ </option>
                                        <option value="B Rh-"> B Rh- </option>
                                        <option value="AB Rh+">AB Rh+ </option>
                                        <option value="AB Rh-">AB Rh- </option>
                                        <option value="O Rh+">O Rh+ </option>
                                        <option value="O Rh-">O Rh- </option>
                                        <option value="NINGUNO">NINGUNO </option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="telefono"> CELULAR: <spam style="color:#cb4335;"><b>*</b>
                                        </spam></label>
                                    <input class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                                        id="telefono" wire:model.live=telefono style="border-radius:10px;">
                                    @error('telefono')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-9 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="zonaId">ZONA: </label>
                                    <select wire:model="zonaId" id="zonaId"
                                        class="form-control @error('zonaId') is-invalid @enderror  select2 "
                                        style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                        <option value="">Selecciona la zona
                                        </option>
                                        @foreach ($zonasSelect as $zona)
                                            <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('zonaId')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="nro_vivienda"> NRO VIVIENDA:<spam style="color:#cb4335;"><b>*</b></spam>
                                    </label>
                                    <input class="form-control @error('nro_vivienda') is-invalid @enderror"
                                        name="nro_vivienda" id="nro_vivienda" wire:model.live=nro_vivienda
                                        style="border-radius:10px;">
                                    @error('nro_vivienda')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="direccion"> DIRECCION:<spam style="color:#cb4335;"><b>*</b></spam></label>
                                    <input class="form-control @error('direccion') is-invalid @enderror" name="direccion"
                                        id="direccion" wire:model.live=direccion style="border-radius:10px;"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('direccion')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="alergias"> ALERGIAS: </label>
                                    <input class="form-control" id="alergias" wire:model=alergias
                                        style="border-radius:10px;" oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="border: none; background-color: white;">
            <div id="cargando" style="display: none;">
                <x-cargando-dos />
            </div>
            <x-cancel-button wire:click="cancelarEstudiante"> CANCELAR</x-cancel-button>
            <x-save-button onclick="actualizarEst()">GUARDAR</x-save-button>
        </div>
    @endcan

</div>
<!-- funncion -->
<script>
    function actualizarEst() {
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
                document.getElementById('cargando').style.display = 'block';
                Livewire.dispatch('actualizarEstudiante');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }

    document.addEventListener('estudianteRegistrado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente.",
            icon: "success"
        });
    });

    document.addEventListener('estudianteCancelado', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "Se cancelo el registro",
            icon: "error"

        });
    });

    document.getElementById('formFile').addEventListener('change', function() {
        Swal.fire({
            title: "¡ADVERTENCIA!",
            text: "DEBE SUBIR UNA FOTO CON FONDO BLANCO. RECUERDA SUBIR UNA FOTO REAL DE LO CONTRARIO SU REGISTRO SERA ANULADO Y NO PODRÁ AFILIARSE MAS.",
            icon: "warning",
            confirmButtonText: 'OK'
        });
    });
</script>
