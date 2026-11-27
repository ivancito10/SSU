<div>
    <x-ver-modal id="verAsignacionModal" labelledby="verAsignacionModalLabel" title="SERVICIO DE ESPECIALIDADES">
        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <spam><b>ESPECIALIDAD:</b> </spam>
                        <spam> {{ $especialidad }}</spam>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <spam> <b>SIGLA: </b></spam>
                        <spam>{{ $sigla }} </spam>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2"><b>Puede habilitar o inhabilitar los siguientes servicios.</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background-color:#2471a3 ; color:white; text-align:center;">
                                <tr>
                                    <th scope="col" width="20% ">SERVICIO</th>
                                    <th scope="col" width="10%">DURACIÓN</th>
                                    <th scope="col" width="20%" class="text-center ">INICIO</th>
                                    <th scope="col" width="20%" class="text-center ">FIN </th>
                                    <th scope="col" width="20%">ESTADO</th>
                                    <th scope="col" width="10%" class="text-center ">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody style="text-align:center;">
                                @foreach ($serviciosSelected as $dato)
                                    <tr>
                                        <td>{{ $dato->nombreServicio }} </td>
                                        <td>
                                            @if ($dato->permanente == true)
                                                <p style="color:#1a5276 "> PERMANENTE </p>
                                            @else($dato->permanente==false)
                                                <p style="color:#e67e22 ">TEMPORAL </p>
                                            @endif
                                        </td>

                                        @if ($dato->permanente === false)
                                            <td class="text-center ">
                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->format('d/m/Y') }} </td>
                                            <td class="text-center ">
                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->format('d/m/Y') }} </td>
                                        @else
                                            <td colspan="2" align="center"> PERMANENTE </td>
                                        @endif

                                        <td>
                                            @if ($dato->estado == true)
                                                <p style="color:#196f3d "> ACTIVO </p>
                                            @else($dato->estado==false)
                                                <p style="color:#c0392b">INACTIVO </p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($dato->estado == true)
                                                <x-deshabilitar-button
                                                    wire:click="habilitarServicio({{ $dato->id }})" />
                                            @else
                                                <x-habilitar-button
                                                    wire:click="deshabilitarServicio({{ $dato->id }}, {{ $dato->id_especialidad }}, {{ $dato->id_servicio }})" />
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-ver-modal>
</div>

<script>
    window.addEventListener('modalVerAsignacionEspecialidad', event => {
        $('#verAsignacionModal').modal('show');
    });

    // HABILITAR EL SERVICIO
    document.addEventListener('servicioHabilitado', function() {
        Swal.fire({
            title: "Servicio Habilitado!",
            text: " Se habilito el servicio",
            icon: "success",
            timer: 10000,
        });
        $('#verAsignacionModal').modal('hide');
    });

    // DESHABILITAR EL SERVICIO
    document.addEventListener('servicioDeshabilitado', function() {
        Swal.fire({
            title: "Servicio Deshabilitado!",
            text: " Se deshabilito el servicio",
            icon: "error",
            timer: 10000,
        });
        $('#verAsignacionModal').modal('hide');
    });

    document.addEventListener('servicioHabilitadoError', function() {
        Swal.fire({
            title: "Existe un registro previo activo",
            text: "No se puede habilitar el servicio",
            icon: "error",
        });
        $('#verAsignacionModal').modal('hide');
    });
</script>
