<x-ver-modal id="verGrupoFamiliarAfiliado" labelledby="verModalAfiliadoLabel" title=" GRUPO FAMILIAR DEL AFILIADO">
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="card-body pt-0 ">

                    <div class="row d-flex flex-column align-items-center">
                        <br>
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail"
                            style="width:200px; height:200px; object-fit:cover; object-position: top;"
                            alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 35px">
                            <p><span style="color: green;"><b>VIGENTE</b></span>
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <span><b>EDAD :</b> {{ $edad }} años</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE COMPLETO:</strong>
                            <p class="p-1" id="nombre"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $nombre_completo }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> C.I.:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $ci }} </p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> MATRICULA:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $matricula }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <strong>
                                FECHA DE NACIMIENTO: </strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $fecha_nacimiento }}</p>

                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <strong>SEXO:</strong>
                            @if ($sexo == 'F')
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                    FEMENINO </p>
                            @else
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                    MASCULINO </p>
                            @endif
                        </div>
                    </div>
                    <!--                     <div class="row ">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> CORREO </strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $correo }}</p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- Aqui se muestra el grupo familiar -->
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
                        @if ($datosFamiliar['id'] != $idPersona)
                            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="background-color:#f2f8fd"
                                    id="headingFamiliar{{ $key }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-outline btn-block text-left collapsed" type="button"
                                            style="color: #1b5989" data-toggle="collapse"
                                            data-target="#collapseFamiliar{{ $key }}" aria-expanded="false"
                                            aria-controls="collapseFamiliar{{ $key }}">
                                            {{ $datosFamiliar['nombres'] }} {{ $datosFamiliar['p_apellido'] }}
                                            {{ $datosFamiliar['s_apellido'] }}
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
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row ">
                                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> NOMBRE COMPLETO: </strong>
                                                        <p class="p-1"
                                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar['nombres'] }}
                                                            {{ $datosFamiliar['p_apellido'] }}
                                                            {{ $datosFamiliar['s_apellido'] }}
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> CARNET:</strong>
                                                        <p class="p-1"
                                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar['ci'] }} @if ($datosFamiliar['complemento'] != null)
                                                                - {{ $datosFamiliar['complemento'] }}
                                                            @endif
                                                        </p>
                                                    </div>

                                                    <div class='col-lg-1 col-md-4 col-sm-6 col-xs-12'>
                                                        <strong>EXP:</strong>
                                                        <p class="p-1"
                                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar['expedido'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> MATRICULA: </strong>
                                                        <p class="p-1"
                                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar['matricula_seguro'] }}</p>

                                                    </div>
                                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> FECHA NACIMIENTO: </strong>
                                                        <p class="p-1"
                                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar['fecha_nacimiento'] }}</p>
                                                    </div>
                                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                                        <strong>SEXO</strong>
                                                        @if ($datosFamiliar['sexo'] == 'F')
                                                            <p class="p-1"
                                                                style="background-color:#e5e8e8;
                                                        padding-rigth:1;
                                                        border-color:white;
                                                        border-radius:10px;">
                                                                FEMENINO
                                                            </p>
                                                        @else
                                                            <p class="p-1"
                                                                style="background-color:#e5e8e8;
                                                        padding-rigth:1;
                                                        border-color:white;
                                                        border-radius:10px;">
                                                                MASCULINO
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> ESTADO CIVIL:</strong>
                                                        <p class="p-1"
                                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar['estado_civil'] }}
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <strong> TIPO DE ASEGURADO: </strong>
                                                        <p class="p-1"
                                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                                            {{ $datosFamiliar['tipo_asegurado'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                @if ($count_grupo == 1)
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
                @endif
            </div>
        </div>
    </div>
</x-ver-modal>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.addEventListener('modalGrupoFamiliarAfliado', event => {
        $('#verGrupoFamiliarAfiliado').modal('show');
    });
</script>
