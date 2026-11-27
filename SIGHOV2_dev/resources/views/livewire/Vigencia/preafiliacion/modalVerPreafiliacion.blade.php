<x-ver-dos-modal id="modalVerPreafiliacion" labelledby="modalVerPreafiliacionLabel" title="VER PREAFILIACIÓN">
    <div class="row">

        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
            <div class="card p-2 card-comun">
                <div class="card cabecera text-center">
                    <b>DATOS PERSONALES</b>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong style="font-size:90%">EDAD:</strong>
                        <span class="h5 font-weight-bold">{{ $edadPersona }} años</span>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $nombreCompleto }}</p>
                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong> CARNET:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $carnet }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong>SEXO:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $sexo }} </p>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong>
                            FECHA DE NACIMIENTO: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ \Carbon\Carbon::parse($fechaNacimiento)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-8 col-md-12 col-sm-12 col-12">
            <div class="card p-2 card-comun">
                <div class="card cabecera text-center">
                    <b>DATOS DE LA PREAFILIACIÓN</b>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                        <strong> NRO. PREAF.: </strong>
                        <p class="p-1 p-comun-disabled">
                            PRE-{{ $nroPreafiliacion }}/{{ \Carbon\Carbon::now()->format('Y') }}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                        <strong>FECHA INICIO: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                        <strong>FECHA INICIO: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y')  }}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                        <strong>VALIDEZ: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $dias }} DÍAS
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                        <strong>FECHA SOL.: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ \Carbon\Carbon::parse($fechaRegistro)->format('d/m/Y') }}
                        </p>
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <strong>NRO. DOC. RESP.: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $nroDocRespaldo }}
                        </p>
                    </div>

                    <div class="col-lg-9 col-md-12 col-sm-12 col-12">
                        <strong>TIPO DE PREAFILIACIÓN:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $tipoPreafiliacion }}
                        </p>
                    </div>

                </div>


                @if ($tipoPreafiliacion === 'PREAFILIACION TITULAR')
                    <div class="row mt-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <strong>INSTITUCIÓN DE PREAFILIADO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $nombreInstitucion }}
                            </p>
                        </div>
                    </div>
                @endif


                @if ($tipoPreafiliacion === 'PREAFILIACION BENEFICIARIO')
                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <div class="card cabecera text-center">
                                <b>DATOS DEL TITULAR DEPENDIENTE</b>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                            <strong>MATRICULA:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $matriculaTitular }}
                            </p>
                        </div>

                        <div class="col-lg-9 col-md-12 col-sm-12 col-12">
                            <strong>NOMBRE COMPLETO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $nombreTitular }}
                            </p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <strong>INSTITUCIÓN:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $nombreInstitucion }}
                            </p>
                        </div>
                    </div>
                @endif

                @if ($tipoPreafiliacion === 'PREAFILIACION TITULAR - APORTANTE VOLUNTARIO')
                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <div class="card cabecera text-center">
                                <b> DATOS DE LA INSTITUCIÓN DEL PREAFILIADO</b>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label for="nombreInstitucion">INSTITUCIÓN: </label>
                            <input class="form-control" id="nombreInstitucion" wire:model="nombreInstitucion"
                                style=" height: 30px; " disabled>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong>OBSERVACIONES</strong>
                        <!--<textarea class="form-control" id="observacion" wire:model=observacion style=" " disabled row="2"> </textarea> -->
                        <div id="observacion-content"
                            style="border: 1px solid #ccc; padding: 10px; height: 100px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                            {!! $observaciones !!}
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</x-ver-dos-modal>

<script>
    window.addEventListener('verModalPreafiliacion', event => {
        $('#modalVerPreafiliacion').modal('show');
    });
</script>
