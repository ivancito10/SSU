<x-ver-modal id="verModalSuspensionHorario" labelledby="verModalSuspensionHorarioLabel"
    title="DETALLE DE SUSPENSION DE HORARIO">
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2 card-comun">
                <div class="cabecera text-center">
                    <b>ESPECIALISTA</b>
                </div>
                <div style="height: 0.5rem;"></div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <!-- FOTOGRAFIA -->
                            <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" alt="ESPECIALISTA"
                                style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display:flex; justify-content: center; font-size: 22px">
                            <br>
                            @if ($permanente)
                                <span style="color: #2874A6;"><b>PERMANENTE</b></span>
                            @else
                                <span style="color: #DC7633;"><b>EVENTUAL</b></span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>NOMBRE COMPLETO:</strong>
                            <p class="p-comun-disabled p-1">{{ $nombres }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>CARNET: </strong>
                            <p class="p-comun-disabled p-1">{{ $carnet }} </p>
                        </div>
                    </div>
                    <div class ="row">
                        <div class="col-lg-12 col-md-7 col-sm-7 col-xs-7">
                            <strong>ESPECIALIDAD:</strong>
                            <p class="p-comun-disabled p-1">{{ $especialidad }} </p>
                        </div>
                        <div class="col-lg-12 col-md-5 col-sm-5 col-xs-5">
                            <strong>GRADO ACADEMICO:</strong>
                            <p class="p-comun-disabled p-1">{{ $grado_academico }} </p>
                        </div>
                    </div>
                    {{--
                    @if (!$permanente)
                        <div class ="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong>FECHA CONTRATO:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ \Carbon\Carbon::parse($fecha_contrato_inicio)->Format('d /m/ Y') }} <b>-</b>
                                    {{ \Carbon\Carbon::parse($fecha_contrato_fin)->Format('d /m/ Y') }}</p>
                            </div>
                        </div>
                    @endif --}}

                    {{-- @if ($switchMotivo)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <x-ver-button wire:click="AbrirModal">Ver Horario</x-ver-button>
                                    @include('livewire.Plataforma.suspension-horario.modalVerAgenda')
                                </div>
                            </div>
                        @endif --}}
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2 card-comun">
                <div class="row mb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <strong><b>SOLICITUD: </b> </strong>
                        @if ($estado)
                            <span class="h3" style="color: green; "><b>ACTIVO</b></span>
                        @else
                            <span class="h3" style="color: red"><b>INACTIVO</b></span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                        <strong>FECHA DE SUSPENSIÓN:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ \Carbon\Carbon::parse($fecha_suspension)->Format('d /m/ Y') }}</p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                        <strong>TURNO: </strong>
                        <p class="p-1 p-comun-disabled">{{ $turno }}</p>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <strong>MOTIVO SUSPENSIÓN:</strong>
                        <p class="p-1 p-comun-disabled">{{ $motivoSuspension }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="observacion_ver"> OBSERVACIONES: </label>
                        <textarea class="form-control p-comun-disabled" id="observacion_ver" wire:model="observacion" style=" resize: none; "
                            disabled></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
                        <strong>RANGO DE HORARIOS SUSPENDIDOS:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $rango }} Hrs.</p>
                    </div>
                </div>
                @if ($switchPersona)
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <strong>DATOS ASEGURADO SUSPENDIDO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $nombrePersona }}</p>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <strong>MATRICULA:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $matriculaPersona }}</p>
                        </div>
                    </div>
                @endif
            </div>
            @can('super-admin-only')
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $nombreCreador }}
                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong><br>
                                    {{ $created_at }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    @if ($switchAnulado)
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                            <div class="p-2 card card-borde mb-2 h-100">
                                <h6 class="mb-3 text-danger font-weight-bold border-bottom pb-1">
                                    <i class="fas fa-check-circle mr-1"></i> ANULADO POR
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li>
                                        <strong>Nombre de usuario:</strong>
                                        {{ $nombreAnula }}
                                    </li>
                                    <li>
                                        <strong>Fecha de anulación:</strong><br>
                                        {{ $updated_at }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            @endcan
        </div>
    </div>
</x-ver-modal>
<script>
    window.addEventListener('modalVerSuspension', event => {
        $('#verModalSuspensionHorario').modal('show');
    });
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
