<div>
    @if ($tipo == 'ESTUDIANTE')
        <div class="d-flex justify-content-center">
            <div class="card-comun p-2 ">
                <div class="card-header p-1" style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                        class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    <b> YA REALIZÓ SU REGISTRO </b>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class=" d-flex flex-column align-items-center">
                            <!-- Paso 4. Mostramos la foto en base 64 -->
                            <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <span class="h3 font-weight-bold">{{ $edad }}
                                años</span>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-8 col-sm-12 col-12">
                                <label for="nombreCompleto"> NOMBRE COMPLETO</label>
                                <input class="form-control" id="nombreCompleto" disabled wire:model=nombreCompleto
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                <label for="ci"> CARNET</label>
                                <input class="form-control" id="ci" disabled wire:model=ci
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-4">
                                <label for="dept_exp"> EXP.</label>
                                <input class="form-control" id="dept_exp" disabled wire:model=dept_exp
                                    style="background-color:#f2ff4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-4">
                                <label for="sexo"> SEXO</label>
                                <input class="form-control" id="sexo" disabled wire:model=sexo
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <label for="fecha_nacimiento"> FECHA DE NACIMIENTO</label>
                                <input class="form-control" id="fecha_nacimiento" disabled
                                    wire:model=fecha_nacimiento_var
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-6 col-6">
                                <label for="estado_civil"> ESTADO CIVIL</label>
                                <input class="form-control" id="estado_civil" disabled wire:model=estado_civil
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>
        <div class="d-flex justify-content-center">
            <div class="alert alert-warning text-center" role="alert">
                <strong>¡Atención!</strong> Vaya a la opción de <strong>Seguimiento</strong> para ver el
                proceso de su afiliación.
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-end" style="border: none;">
            <x-siguiente-dos-button wire:click="seguimiento">SEGUIMIENTO</x-siguiente-dos-button>
        </div>
    @elseif($tipo == 'BENEFICIARIO')
        <div class="d-flex justify-content-center">
            <div class="card p-2 " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); width:60% ">
                <div class="card-header p-1" style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                        class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    <b> YA SE ENCUENTRA ASEGURADO </b>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class=" d-flex flex-column align-items-center">
                            <!-- Paso 4. Mostramos la foto en base 64 -->
                            <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" width="250" height="250" alt="FTP.{{ $foto }}">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center ">
                            <strong>MATRICULA:</strong>
                            <span class="h6 font-weight-bold" style="color:#273746">{{ $matricula }}

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  text-center">
                            <span class="h5 font-weight-bold" style="color:#273746">{{ $edad }} años
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombreCompleto"> NOMBRE COMPLETO:</label>
                                <input class="form-control" id="nombreCompleto" disabled wire:model=nombreCompleto
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="ci"> CARNET:</label>
                                <input class="form-control" id="ci" disabled wire:model=ci
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <label for="dept_exp"> EXP.:</label>
                                <input class="form-control" id="dept_exp" disabled wire:model=dept_exp
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:</label>
                                <input class="form-control" id="fecha_nacimiento" disabled
                                    wire:model=fecha_nacimiento_var
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="sexo"> SEXO:</label>
                                <input class="form-control" id="sexo" disabled wire:model=sexo
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="estado_civil"> ESTADO CIVIL:</label>
                                <input class="form-control" id="estado_civil" disabled wire:model=estado_civil
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_afiliacion"> FECHA DE AFILIACIÓN:</label>
                                <input class="form-control" id="fecha_afiliacion" disabled wire:model=fecha_afiliacion
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="tipo_asegurado"> TIPO DE ASEGURADO:</label>
                                <input class="form-control" id="tipo_asegurado" disabled wire:model=tipo_asegurado
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="alert alert-warning text-center" role="alert">
                <strong>¡Atención!</strong> Usted ya está afiliado como <strong>Beneficiario.</strong> No puede realizar
                su afiliación.
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-end" style="border: none;">
            <x-atras-button wire:click="atras">SALIR</x-atras-button>

        </div>
    @endif
</div>
