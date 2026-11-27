<div>
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4 style="color: black">ARCHIVO CLÍNICO - <b>LISTA AGENDAS POR ASEGURADO</b></h4>


    </div>
    @can('archivo_clinico.agendas')
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card card-comun p-2">
                    <div class="cabecera text-center">
                        <b>
                            LISTA DE AGENDAS</b>
                    </div>

                    <br><br>

                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" wire:ignore>
                            <label for="matriculaSearch">INTRODUZCA NUMERO DE MATRICULA: <b
                                    style="color:#cb4335;">*</b></label>
                            <input type="text" class="form-control input-comun" wire:model="matriculaSearch"
                                maxlength="9" minlength="9" pattern="\d{9}" required style="text-transform: uppercase;"
                                placeholder="Ej:. 001122ABC">
                            @error('matriculaSearch')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($aseguradosLista === 1)
                            <div class="col-lg-10">
                                <div style="height: 420px; overflow-y: auto;">
                                    <table class="tabla-celeste" style="width: 100%;">
                                        <thead align="center">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">MATRICULA</th>
                                                <th scope="col">ASEGURADO</th>
                                                <th scope="col">TIPO ASEGURADO</th>
                                                <th scope="col">OPCIONES</th>

                                            </tr>
                                        </thead>
                                        <tbody style="text-align:center;">
                                            @if ($asegurados)
                                                @foreach ($asegurados as $key => $asegurado)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $asegurado->matricula_seguro }}</td>
                                                        <td align="left">{{ $asegurado->p_apellido }}
                                                            {{ $asegurado->s_apellido }}
                                                            {{ $asegurado->nombres }}</td>
                                                        <td align="left">
                                                            {{ $asegurado->tipoAseguradoPersona->tipo_asegurado }}</td>
                                                        <td><button class="btn btn-primary" type="submit"
                                                                wire:click="buscarAsegurado({{ $asegurado->id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                                    height="25" fill="currentColor"
                                                                    class="bi bi-check-square" viewBox="0 0 16 16">
                                                                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1
                                                                        1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0
                                                                        0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2
                                                                        2 0 0 0-2-2H2z" />
                                                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.992
                                                                        4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75
                                                                        0 1 1 1.06-1.06l1.94 1.94 3.647-4.293z" />
                                                                </svg>



                                                            </button></td>

                                                    </tr>
                                                @endforeach
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div><br>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pr-2">
                                <button class="btn btn-success" type="button" wire:click="asegurado">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85
                                                                     a1 1 0 0 0 1.415-1.415l-3.85-3.85zm-5.242 1.656
                                                                     a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z" />
                                    </svg> BUSCAR ASEGURADO </button>
                            </div>
                        </div>
                    </div>
                    @if ($encontrado === 1)
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                {{-- Loader mientras busca --}}
                                <div wire:loading class="alert alert-info py-2">
                                    Buscando asegurado, por favor espera…
                                </div>

                                {{-- Resultado --}}

                                <div class="tabla-celeste">
                                    <table class="table table-sm table-striped table-hover align-middle ">
                                        <thead class="table-primary" align="center">
                                            <tr>
                                                <th style="width: 60px;">#</th>
                                                <th>ASEGURADO</th>
                                                <th>ESPECIALIDAD</th>
                                                <th>ESPECIALISTA</th>
                                                <th>HORA PROGRAMADA</th>
                                                <th>FECHA PROGRAMADA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($aseguradosEncontrados as $i => $agenda)
                                                <tr>
                                                    <td align="center">{{ $loop->iteration }}</td>
                                                    <td> {{ $agenda->personaAgenda->p_apellido }}
                                                        {{ $agenda->personaAgenda->s_apellido }}{{ $agenda->personaAgenda->nombres }}
                                                    </td>
                                                    <td>{{ $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad }}
                                                    </td>

                                                    <td>
                                                        {{ $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido }}
                                                        {{ $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido }}
                                                        {{ $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres }}
                                                    </td>
                                                    <td align="center">
                                                        {{ \Carbon\Carbon::parse($agenda->hora_agenda)->format('H:i') }}
                                                    </td>
                                                    <td align="right">
                                                        {{ \Carbon\Carbon::parse($agenda->fecha_agenda)->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endcan



</div>
<script>
    document.addEventListener("PersonaEncontrada", function(event) {

        Swal.fire({
            title: "<b>Persona Encontrada</b>",
            text: `Se seleccionó correctamente al asegurado(a)`,
            icon: "success",
        });
    });
    document.addEventListener("AgendasEncontradas", function(event) {

        Swal.fire({
            title: "<b>Agendas</b>",
            text: `Se Encontró  las agendas`,
            icon: "success",
        });
    });
    document.addEventListener("noSeEncontro", function(event) {

        Swal.fire({
            title: "<b>No Existen Agendas</b>",
            text: `No se Encontraron agendas del Asegurado Seleccionado!`,
            icon: "warning",
        });
    });
</script>
