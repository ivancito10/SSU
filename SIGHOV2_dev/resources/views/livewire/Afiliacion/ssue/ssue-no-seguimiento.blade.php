<div>
    @if ($tipo == 'BENEFICIARIO')
        <div class="d-flex justify-content-center">
            <div class="card p-3"
                style="border-radius: 15px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); width: 70%; background-color: #ffffff;">
                <div class="card-header p-3"
                    style="background-color: #bfe4f8; text-align: center; border-radius: 10px; font-weight: bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#fab32d"
                        class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    <span style="color: #333; font-size: 1.2rem;">¡No puede ver el seguimiento porque usted está
                        asegurado!</span>
                </div>
                <div class="card-body mt-3">
                    <p style="font-size: 1rem; color: #333;">
                        Actualmente usted está registrado como <strong>{{ $tipo }}</strong> VIGENTE.
                    </p>
                    <p style="font-size: 1rem; color: #555;">
                        Cualquier error en su registro, contáctese con el encargado de afiliaciones para resolverlo.
                    </p>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-end" style="border: none;">
            <x-atras-button wire:click="atras" class="mx-2">SALIR</x-atras-button>
        </div>
    @elseif($tipo == 'ESTUDIANTE')
        <div class="d-flex justify-content-center">
            <div class="card p-2 " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); width:60% ">
                <div class="card-header p-1" style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                        class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    <b> ¡ DEBE REALIZAR SU REGISTRO ! </b>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombres"> NOMBRES:</label>
                                <input class="form-control" id="nombres" disabled wire:model=nombres
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="p_apellido"> PRIMER APELLIDO:</label>
                                <input class="form-control" id="p_apellido" disabled wire:model=p_apellido
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="s_apellido"> SEGUNDO APELLIDO:</label>
                                <input class="form-control" id="s_apellido" disabled wire:model=s_apellido
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="ci"> CARNET:</label>
                                <input class="form-control" id="ci" disabled wire:model=ci
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="alert alert-warning text-center" role="alert">
                <strong>¡Atención!</strong> Vaya a la opción de <strong>Solicitud</strong> para registrar su afiliación.
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-end" style="border: none;">
            <x-atras-button wire:click="atras" class="mx-2">SALIR</x-atras-button>
            <x-siguiente-dos-button wire:click="seguimiento">Solicitud</x-siguiente-dos-button>
        </div>
    @else
        <div class="d-flex justify-content-center">
            <div class="card p-2 " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); width:60% ">
                <div class="card-header p-1" style="background-color:#bfe4f8;  text-align:center; border-radius:9px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                        class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    <b> ¡ DEBE REALIZAR SU REGISTRO ! </b>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombres"> NOMBRES:</label>
                                <input class="form-control" id="nombres" disabled wire:model=nombres
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="p_apellido"> PRIMER APELLIDO:</label>
                                <input class="form-control" id="p_apellido" disabled wire:model=p_apellido
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="s_apellido"> SEGUNDO APELLIDO:</label>
                                <input class="form-control" id="s_apellido" disabled wire:model=s_apellido
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="ci"> CARNET:</label>
                                <input class="form-control" id="ci" disabled wire:model=ci
                                    style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="alert alert-warning text-center" role="alert">
                <strong>¡Atención!</strong> Vaya a la opción de <strong>Solicitud</strong> para registrar su afiliación.
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-end" style="border: none;">
            <x-atras-button wire:click="atras" class="mx-2">SALIR</x-atras-button>
            <x-siguiente-dos-button wire:click="seguimiento">Solicitud</x-siguiente-dos-button>
        </div>
    @endif

</div>
