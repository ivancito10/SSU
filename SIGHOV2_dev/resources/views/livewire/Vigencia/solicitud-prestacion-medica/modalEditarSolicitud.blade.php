<x-gran-modal id="editarSolicitud" labelledby="editarSolicitudLabel" title="MODIFICAR SOLICITUD PRESTACIÓN MÉDICA" saveAction="guardarSolicitud()">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); ">
                <!-- <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>ASEGURADO</b></p>
                </div> -->
                <div class="card-body">
                    <div class="card-body d-flex flex-column align-items-center">
                        <!--  Mostramos la foto del asegurado en base 64 -->
                        <img src="data:image/jpg;base64,{{ $this->foto }}" class="img-thumbnail" width="200"
                            height="200" alt="FTP">

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                        style="display: flex; justify-content: center; font-size: 35px">
                        @if ($this->estado_vigencia == 'VIGENTE')
                            <p style="color: green;"><b>VIGENTE</b></p>
                        @else
                            <p style="color: orange;"><b>CESANTE</b></p>
                        @endif

                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;" >
                            <p style="background-color:#3E6CA7; color:white">
                            <b>ASEGURADO</b></p>
                        </div>
                    </div>
                    

                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <spam styel="color:#616a6b ;"><b>MATRICULA: </b> </spam>
                            <spam>{{$matricula}} </spam>
                        </div>
                        
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <spam style="color:#21618c;"><b>TIPO: </b> </spam>
                            <spam>{{$tipo_asegurado}} </spam>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre"> NOMBRE COMPLETO:</label>
                            <input class="form-control" id="nombre" disabled wire:model=nombreCompleto
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="carnet"> CARNET :</label>
                            <input class="form-control" id="carnet" disabled wire:model=ci
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="carnet"> COMPL:</label>
                            <input class="form-control" id="carnet" disabled wire:model=complemento
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                    </div>
                    
                    
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="institucion"> INSTITUCIÓN:</label>
                            <textarea class="form-control" id="institucion" disabled wire:model=institucion 
                            style=" resize:none; background-color: #f8fcff;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>SOLICITUD DE PRESTACION MÉDICA</b></p>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                                <em   class="ml-2" ><b>Actualizar los siguientes datos si corresponden.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_hoja_de_ruta"> NRO DE HOJA DE RUTA:</label>
                            <input class="form-control" id="nro_hoja_de_ruta" wire:model=nro_hoja_de_ruta
                            oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud_hr"> FECHA SOLICITUD:</label>
                            <input class="form-control" id="fecha_solicitud_hr" type='date'
                                wire:model=fecha_solicitud_hr value="{{ $fecha_solicitud_hr }}">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="cod_cite"> CITE:</label>
                            <input class="form-control" id="cod_cite" disabled wire:model=cod_cite
                            style="background-color: #f8fcff;">
                        </div>
                    </div>


                    <div class="row mb-2">

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <strong>SEGURO DEL INTERIOR:</strong>
                            <select wire:model="id_seguro_interior" id="id_seguro_interior"
                                class="form-control select2 " style="width: 100%;" tabindex="-1" required>
                                <option selected="selected" value="{{ $id_seguro_interior }}">{{ $nombre_seguro }}
                                </option>
                                @foreach ($seguros as $seguro)
                                    <option value="{{ $seguro->id }}">{{ $seguro->nombre_seguro }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1" style="text-align:right;">
                            <br>
                            <strong>VALIDEZ:</strong>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label fro ="fecha_inicio">DESDE:</label>
                            <input class="form-control" id="fecha_inicio" type='date' wire:model=fecha_inicio>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label fro ="fecha_fin">HASTA:</label>
                            <input class="form-control" id="fecha_fin" type='date' wire:model=fecha_fin>
                        </div>

                        
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                                <em   class="ml-2" ><b>Ingresar las nuevas especialidades y servicios.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="especialidades">ESPECIALIDADES A SOLICITAR:</label>
                            <select wire:model="especialidades" id="especialidades" class="form-control select2 "
                                style="width: 100%" multiple="multiple" tabindex="-1" required wire:ignore>

                                @foreach ($especialidadesSelect as $especialidad)
                                    <option value="{{ $especialidad->id }}">{{ $especialidad->especialidad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="especialidades">SERVICIOS A  SOLICITAR:</label>
                            <select wire:model="servicios" id="servicios" class="form-control select2"
                                style="width: 100%;" multiple="multiple" tabindex="-1" required>

                                @foreach ($serviciosSelect as $servicio)
                                    <option value="{{ $servicio->id }}">{{ $servicio->servicio }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row mb-2">
                        
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <b>FARMACIA:</b>
                        </div>
                        

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <p class='h6'><b>MEDICAMENTOS LINAME:</b></p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <p class='h6'><b>MEDICAMENTOS EXTRALINAME:</b></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center;">

                            <div class="col-sm-12 d-flex align-items-center">
                                <label class="mr-6 text-info">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-info custom-switch-on-success">
                                    <input wire:model="medicamentos_liname" name="medicamentos_liname"
                                        type="checkbox" class="custom-control-input" id="medicamentos_liname">
                                    <label class="custom-control-label" for="medicamentos_liname"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-6 text-success">SI</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center;">

                            <div class="col-sm-12 d-flex align-items-center">
                                <label class="mr-6 text-info">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-info custom-switch-on-success">
                                    <input wire:model="medicamentos_extraliname" name="medicamentos_extraliname"
                                        type="checkbox" class="custom-control-input" id="medicamentos_extraliname">
                                    <label class="custom-control-label" for="medicamentos_extraliname"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-6 text-success">SI</label>
                            </div>
                        </div>
                        
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                                <em   class="ml-2" ><b>Agregar beneficiarios a la solicitud.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center;">
                            @include('livewire.Vigencia.solicitud-prestacion-medica.modalAgregarGrupoFamiliar')
                        </div>
                    </div>
                    <br>

                    @if ($solicitantesAgregados == [])
                    @else
                        <div class="row mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <!-- <div class="card"> -->
                                    <!-- <div class="card-header" style="background-color:#3f3e3e; color:white">GRUPO
                                        FAMILIAR AGREGADO A LA SOLICITUD</div>
                                    <div class="card-body"> -->
                                        <div class="row">
                                            <div class="col-lg-12" style="text-align:center;">
                                                <p style="background-color:#e5eff9  ; color:#1e5886; ">
                                                <b> AFILIADOS - GRUPO FAMILIAR AGREGADOS A LA SOLICTUD.</b> 
                                                </p>

                                            </div>

                                        </div>
                                        <div class='row'>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr align="center" style="background-color:#2471a3 ; color:white;">
                                                        <th scope="col">#</th>
                                                        <th scope="col">NOMBRE COMPLETO</th>
                                                        <th scope="col">MATRICULA</th>
                                                        <th scope="col">CARNET</th>
                                                        <th scope="col">PARENTESCO</th>
                                                        <th scope="col">ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $counter = 1;
                                                    @endphp
                                                    @foreach ($this->solicitantesAgregados as $personaList)
                                                        <tr style="padding: 15px;">
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $counter++ }}</td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['p_apellido'] . ' ' . $personaList['s_apellido'] . ' ' . $personaList['nombres'] }}
                                                            </td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['matricula_seguro'] }}</td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['complemento'] !== null ? $personaList['ci'] . '-' . $personaList['complemento'] : $personaList['ci'] }}
                                                            </td>
                                                            <td align="center" style="padding: 4px;">
                                                                {{ $personaList['tipo_asegurado'] }}</td>

                                                            <td align="center" style="padding: 4px;">
                                                                <div class="row">
                                                                    <!--    Boton Agregar o Quitar -->
                                                                    <div class="col-sm-12">
                                                                        <x-cancel-button
                                                                            onclick="quitarBeneficiario('{{ $personaList['p_apellido'] }} {{ $personaList['s_apellido'] }} {{ $personaList['nombres'] }}', '{{ $personaList['id'] }}')"></x-cancel-button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                   <!--  </div>
                                </div> -->
                            </div>
                        </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="otras_limitaciones" style="display: block; text-align: left;">OTRAS
                                LIMITACIONES:</label>
                            <textarea class="form-control" id="otras_limitaciones" wire:model='otras_limitaciones' class="swal2-input"
                                rows='4' oninput="this.value = this.value.toUpperCase()"></textarea>

                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="observacion" style="display: block; text-align: left;">OBSERVACIONES:</label>
                            <textarea class="form-control" id="observacion" wire:model='observacion' class="swal2-input" 
                            rows='4' oninput="this.value = this.value.toUpperCase()"></textarea>

                        </div>
                    </div>

                </div>
            </div>
                
        </div>
    </div>


</x-gran-modal>

<script>
    window.addEventListener('editarSolicitudModal', event => {

        $('#editarSolicitud').modal('show');
    });

    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#seguro_interior_id');
        $(select).select2();

        $(select).on('change', function(e) {
            var selectedValuesSeg = $(this).val();
            // Enviar los valores seleccionados al componente Livewire
            @this.set('seguro_interior_id', selectedValuesSeg);
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        var selectMultipleEsp = document.querySelector('#especialidades');
        $(selectMultipleEsp).select2({
            placeholder: 'Selecciona las especialidades'
        });

        $(selectMultipleEsp).on('change', function(e) {
            var selectedValuesEsp = $(this).val();
            // Enviar los valores seleccionados al componente Livewire
            @this.set('especialidades', selectedValuesEsp);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var selectMultipleServ = document.querySelector('#servicios');
        $(selectMultipleServ).select2({
            placeholder: 'Selecciona los servicios'
        });

        $(selectMultipleServ).on('change', function(e) {
            var selectedValuesServ = $(this).val();
            // Enviar los valores seleccionados al componente Livewire
            @this.set('servicios', selectedValuesServ);
        });
    });
    // guardar actualizar Dato
    function guardarSolicitud(persona, idPersona) {
            Swal.fire({
                title: '¿Modifica registro?',
                text: "Se modificarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('actualizarPrestacionMedica');
                } 
            });
        }

        
        document.addEventListener('actualizado', function() {
            Swal.fire({
                title: "Modificado!",
                text: "La solictud prestación médica fue modificada exitosamente.",
                icon: "success"

            });
        });

        document.addEventListener('noValidado', function() {
            Swal.fire({
                title: "No validado!",
                text: "Ocurrio un error.",
                icon: "error"

            });
        });
</script>
