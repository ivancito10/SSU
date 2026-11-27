<x-ver-modal id="verGrupoFamiliar" labelledby="verGrupoFamiliarLabel" title="GRUPO FAMILIAR">
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:{{ $color }}; color:white;text-align:center; border-radius:6px">
                    TITULAR</p>
                <div class="row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" style="width:200px; height:200px; object-fit:cover; object-position: top;"
                        alt="FTP.{{ $foto }}">
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
                        <span class="h5 font-weight-bold">{{ $edad }} años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                        {{-- <span class=" font-weight-bold h5"> {{ $codigoTitular }} </span> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold">{{ $tipoAporte }} {{ $permanente }}
                        </span>
                    </div>
                </div>
                @if ($tipoAporte == 'INSTITUCIONAL')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <span class=" font-weight-bold h5">{{ $nombreInsPatronal }}
                            </span>
                        </div>
                    </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1" id="nombre"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $nombre }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong> CARNET:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $carnet }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong>SEXO:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $sexo }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                        <strong>
                            FECHA DE NACIMIENTO: </strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $fecha_nacimiento_literal }}</p>
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                        <strong>
                            TELEFONO: </strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $telefono }}</p>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> CORREO: </strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $correo }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pruebita  -->
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="accordion" id="accordionDatosFamiliar">
                @if ($grupoFamiliarDatos == [])
                    <div style="height: 50vh;">
                        <h2
                            style=" text-align: center;
                     
                    color: #c0392b; /* Un rojo más suave y accesible */
                    font-family: Arial, sans-serif;
                    font-size: 18px;
                    background-color: #f5c6cb;
                    padding: 20px;
                    border-radius: 5px;">
                            EL AFILIADO NO CUENTA CON UN REGISTRO DE GRUPO FAMILIAR
                        </h2>
                    </div>
                @else
                    @foreach ($grupoFamiliarDatos as $key => $datosFamiliar)
                        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                            <div class="card-header" style="background-color:#f2f8fd"
                                id="headingFamiliar{{ $key }}">
                                <h2 class="mb-0">
                                    <button
                                        class="btn btn-outline btn-block text-left collapsed  d-flex justify-content-between align-items-center"
                                        type="button" style="color: #1b5989" data-toggle="collapse"
                                        data-target="#collapseFamiliar{{ $key }}" aria-expanded="false"
                                        aria-controls="collapseFamiliar{{ $key }}">
                                        {{ optional($datosFamiliar->beneficiarioPersona)->p_apellido . ' ' . optional($datosFamiliar->beneficiarioPersona)->s_apellido . ' ' . $datosFamiliar->beneficiarioPersona->nombres }}
                                        -
                                        {{ $datosFamiliar->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFamiliar{{ $key }}" class="collapse"
                                aria-labelledby="headingFamiliar{{ $key }}"
                                data-parent="#accordionDatosFamiliar">
                                <div class="card-body">

                                    <div class="row ">
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row ">

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    {{-- <img src="{{ '' . $datosFamiliar->foto_nombre }}"
                                                        alt="Imagen Base64" class="img-thumbnail" width="200"
                                                        height="200"> --}}
                                                    <img src="{{ !empty($datosFamiliar->foto_nombre) ? 'data:image/jpg;base64,' . $datosFamiliar->foto_nombre : asset('images/default_afiliacion.jpg') }}"
                                                        class="img-thumbnail"
                                                        style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                                        alt="FTP.{{ $datosFamiliar->foto_nombre }}">
                                                </div>
                                            </div>

                                            <div class="row ">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center ">
                                                    <strong style="font-size:90%">MATRICULA :</strong>
                                                    <span class=" font-weight-bold " style="color:#186b9b; ">
                                                        {{ $datosFamiliar->beneficiarioPersona->matricula_seguro }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <strong> PARENTESCO: </strong>
                                                    <p class="p-1"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                        {{ $datosFamiliar->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado }}
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row ">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <strong> NOMBRE COMPLETO: </strong>
                                                    <p class="p-1"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                        {{ optional($datosFamiliar->beneficiarioPersona)->p_apellido . ' ' . optional($datosFamiliar->beneficiarioPersona)->s_apellido . ' ' . $datosFamiliar->beneficiarioPersona->nombres }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if ($datosFamiliar->beneficiarioPersona->complemento)
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> CARNET:</strong>
                                                        <p class="p-1"
                                                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar->beneficiarioPersona->ci }} -
                                                            {{ $datosFamiliar->beneficiarioPersona->complemento }} </p>
                                                    </div>
                                                @else
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> CARNET:</strong>
                                                        <p class="p-1"
                                                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar->beneficiarioPersona->ci }} </p>
                                                    </div>
                                                @endif
                                                <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
                                                    <strong>EXP.:</strong>
                                                    <p class="p-1"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                        {{ $datosFamiliar->beneficiarioPersona->departamentoPersona->sigla ?? null }}
                                                    </p>

                                                </div>
                                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <strong>SEXO:</strong>
                                                    <p class="p-1"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                        {{ $datosFamiliar->beneficiarioPersona->sexo_texto ?? null }}
                                                    </p>
                                                </div>

                                            </div>


                                            <div class="row ">
                                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                    <label for="fecha_nacimiento"> FECHA NACIMIENTO: </label>

                                                    <input class="form-control" id="fecha_nacimiento"
                                                        value="{{ $datosFamiliar->beneficiarioPersona->fecha_nacimiento }}"
                                                        type="date"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                                                        disabled>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                    <strong> ESTADO CIVIL:</strong>
                                                    <p class="p-1"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                        {{ $datosFamiliar->beneficiarioPersona->estadoCivilPersona->nombre }}
                                                    </p>
                                                </div>

                                            </div>
                                            <div class="row ">
                                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                                    <strong> CORREO ELECTRONICO: </strong>
                                                    <p class="p-1"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                        {{ $datosFamiliar->correo }}</p>
                                                </div>
                                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <strong> TELEFONO: </strong>
                                                    <p class="p-1"
                                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                        {{ $datosFamiliar->telefono }}</p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>


        <!-- Pruebita  -->

    </div>
</x-ver-modal>

<script>
    window.addEventListener('modalGrupoFamiliar', event => {

        $('#verGrupoFamiliar').modal('show');
    });
</script>
