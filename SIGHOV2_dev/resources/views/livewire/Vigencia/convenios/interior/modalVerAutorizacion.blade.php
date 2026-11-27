<x-ver-dos-modal id="verAut" labelledby="verAutLabel" title=" CONVENIO DEL INTERIOR ">
    <div class="row">

        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    ASEGURADO DEL INTERIOR</p>
                <div class="row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto_nombre) ? 'data:image/jpg;base64,' . $foto_nombre : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto_nombre }}">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <span class="h5 font-weight-bold">{{ $edadPersona }} años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula_seguro }} </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_asegurado }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1" id="nombre"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $nombreCompleto }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong> CARNET:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $ci }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong>SEXO:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $sexo }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>
                            FECHA DE NACIMIENTO: </strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ \Carbon\Carbon::parse($fecha_nacimiento)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="@can('super-admin-only') col-lg-8 @else col-lg-10 @endcan col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2">

                <p style="background-color:#1a5276; color:white;text-align:center;">
                    DATOS DEL CONVENIO DEL INTERIOR</p>
                <div class="card-body">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <spam><b>SOLICTUD: </b> </spam>

                        @if ($this->estado_validacion == 'VALIDADO')
                            <p class="h3" style="color: green;">VALIDADO</p>
                        @elseif($this->estado_validacion == 'PENDIENTE')
                            <p class="h3 parpadeo" style="color: orange;">PENDIENTE</p>
                        @elseif($this->estado_validacion == 'ANULADO')
                            <p class="h3 parpadeo" style="color: red;">ANULADO</p>
                        @endif

                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_hoja_de_ruta"> NRO. HOJA RUTA:</label>
                            <input class="form-control" id="nro_hoja_de_ruta" wire:model=nro_hoja_de_ruta disabled
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud_hr"> FECHA SOLICITUD:</label>
                            <input class="form-control" id="fecha_solicitud_hr" disabled wire:model="fecha_solicitud_hr"
                                type="date" style=" height: 30px; ">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="cod_cite_int"> NRO. CITE INT:</label>
                            <input class="form-control" id="cod_cite_int" disabled wire:model=cod_cite_int
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="codInte"> COD INTERIOR:</label>
                            <input class="form-control" id="codInte" disabled wire:model=codInte
                                style=" height: 30px; ">
                        </div>
                    </div>

                    <div class="row mb-2">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <p class='h6'><b>VALIDEZ:</b></p>
                        </div>

                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_inicio"> DESDE: </label>
                            <input class="form-control" id="fecha_inicio" type='date' disabled
                                wire:model=fecha_inicio style=" height: 30px;">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> HASTA: </label>
                            <input class="form-control" id="fecha_fin" type='date'disabled wire:model=fecha_fin
                                style=" height: 30px;">

                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="diasVigencia"> DÍAS VIGENCIA: </label>
                            <input class="form-control" id="diasVigencia" type='number' disabled
                                wire:model=diasVigencia style=" height: 30px;">

                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_seguro"> INSTITUCIÓN ORIGEN: </label>
                            <input class="form-control" id="nombre_seguro" disabled wire:model=nombre_seguro
                                style=" height: 30px;">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>

                            <div class="accordion" id="accordionEspecialidades">
                                <div class="card">
                                    <div class="card-header" id="headingEspecialidades"
                                        style="background-color: #3E6CA7;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-white" type="button"
                                                data-toggle="collapse" data-target="#collapseEspecialidades"
                                                aria-expanded="false" aria-controls="collapseEspecialidades">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                </svg> ESPECIALIDAD SOLICITADAS
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseEspecialidades" class="collapse"
                                        aria-labelledby="headingEspecialidades"
                                        data-parent="#accordionEspecialidades">

                                        <ul class="list-group list-group-flush">
                                            @if ($especialidades != null)
                                                @foreach ($especialidades as $especialidad)
                                                    <li class="list-group-item">{{ $especialidad->especialidad }}
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item"><b>NO SE REGISTRARON ESPECIALIDADES</b>
                                                </li>

                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <div class="accordion" id="accordionServicios">
                                <div class="card">
                                    <div class="card-header" id="headingServicios"
                                        style="background-color: #3E6CA7;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-white" type="button"
                                                data-toggle="collapse" data-target="#collapseServicios"
                                                aria-expanded="false" aria-controls="collapseServicios">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                </svg> SERVICIOS SOLICITADOS
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseServicios" class="collapse" aria-labelledby="headingServicios"
                                        data-parent="#accordionServicios">

                                        <ul class="list-group list-group-flush">
                                            @if ($servicios != null)
                                                @foreach ($servicios as $servicio)
                                                    <li class="list-group-item">{{ $servicio->servicio }}</li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item"><b>NO SE REGISTRARON SERVICIOS</b>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <spam><b>MEDICAMENTOS LINAME:</b>
                                @if ($medicamentos_liname == true)
                                    <b style="color:#1e8449 ;"> SI</b>
                                @else
                                    <b style="color:#cb4335;"> NO</b>
                                @endif
                            </spam>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <spam><b>MEDICAMENTOS EXTRALINAME:</b>
                                @if ($medicamentos_extraliname == true)
                                    <b style="color:#1e8449 ;"> SI</b>
                                @else
                                    <b style="color:#cb4335;"> NO</b>
                                @endif
                            </spam>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="otras_limitaciones">OTRAS LIMITACIONES:
                            </label>
                            <!--<textarea class="form-control" id="otras_limitaciones" wire:model=otras_limitaciones style=" " disabled
                                row="2"> </textarea> -->
                            <div id="observacion-content"
                                style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                {!! $limitaciones_ai !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                            <label for="observacion"> OBSERVACIONES: </label>
                            <!--<textarea class="form-control" id="observacion" wire:model=observacion style=" " disabled row="2"> </textarea> -->
                            <div id="observacion-content"
                                style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                {!! $observacion_ai !!}
                            </div>

                        </div>


                    </div>


                    {{-- @if ($solicitantesAgregados != null)
                      <div class="row mb-2">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="card">
                                  <div class="card-body">
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
                                                      <th scope="col">NRO</th>
                                                      <th scope="col">NOMBRE COMPLETO</th>
                                                      <th scope="col">MATRICULA</th>
                                                      <th scope="col">CARNET</th>
                                                      <th scope="col">PARENTESCO</th>
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
                                                              {{ $personaList->p_apellido . ' ' . $personaList->s_apellido . ' ' . $personaList->nombres }}
                                                          </td>
                                                          <td align="center" style="padding: 4px;">
                                                              {{ $personaList->matricula_seguro }}</td>
                                                          <td align="center" style="padding: 4px;">
                                                              {{ $personaList->complemento !== null ? $personaList->ci . '-' . $personaList->complemento : $personaList->ci }}
                                                          </td>
                                                          <td align="center" style="padding: 4px;">
                                                              {{ $personaList->tipo_asegurado }}</td>
                                                      </tr>
                                                  @endforeach

                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  @endif --}}

                </div>




                <p style="background-color:#1a5276; color:white;text-align:center;">
                    DATOS REFERENCIALES DEL ASEGURADO DEL INTERIOR</p>
                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="telefono"> TELÉFONO:</label>
                            <input class="form-control" id="telefono" wire:model="telefono" disabled
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="telefono_referencia"> TELÉFONO REF.:</label>
                            <input class="form-control" id="telefono_referencia" disabled
                                wire:model="telefono_referencia" style=" height: 30px; ">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="direccion_referencia"> DIRECCIÓN DE REF. DEL ASEGURADO DEL
                                INTERIOR:</label>
                            <input class="form-control" id="direccion_referencia" disabled
                                wire:model="direccion_referencia" style=" height: 30px; ">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="grupo_sanguineo"> GRUPO SANGUINEO:</label>
                            <input class="form-control" id="grupo_sanguineo" disabled wire:model="grupo_sanguineo"
                                style=" height: 30px; ">
                        </div>

                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="alergias"> ALERGIAS:</label>
                            <input class="form-control" id="alergias" disabled wire:model="alergias"
                                style=" height: 30px; ">
                        </div>
                    </div>

                </div>



            </div>

        </div>

        @can('super-admin-only')

            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <div class="row mb-2">
                    @if ($personaAnulado == null && $personaVal != null)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#E08108; color:white">
                                    <p class='h6'>REGISTRADO POR:</p>
                                </div>
                                <div class="card-body">
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p>{{ $usuarioNombre }}</p>

                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> EN FECHA </label>
                                            <br>
                                            <p>{{ \Carbon\Carbon::parse($created_at)->format('d/m/Y H:i:s') }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#268749; color:white">
                                    <p class='h6'>VALIDADO POR:</p>
                                </div>
                                <div class="card-body">
                                    @if ($personaVal == null)
                                    @else
                                        <div class='row'>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <p>{{ $personaVal }}</p>

                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="persona" style='color:black;'> EN FECHA </label>
                                                <br>
                                                <p>{{ \Carbon\Carbon::parse($fecha_validacion)->format('d/m/Y H:i:s') }}
                                                </p>

                                            </div>
                                        </div>
                                    @endif




                                </div>
                            </div>
                        </div>
                    @elseif($personaVal == null && $personaAnulado != null)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#E08108; color:white">
                                    <p class='h6'>REGISTRADO POR:</p>
                                </div>
                                <div class="card-body">
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p>{{ $usuarioNombre }}</p>

                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> EN FECHA </label>
                                            <br>
                                            <p>{{ \Carbon\Carbon::parse($created_at)->format('d/m/Y H:i:s') }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#C11F0F; color:white">
                                    <p class='h6'>ANULADO POR:</p>
                                </div>
                                <div class="card-body">
                                    <div class='row'>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> POR</label>
                                            <br>
                                            <p>{{ $personaAnulado }}</p>

                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> EN FECHA </label>
                                            <br>
                                            <p>{{ \Carbon\Carbon::parse($fecha_anulacion)->format('d/m/Y H:i:s') }}</p>

                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="observacion" style='color:black;'> MOTIVO DE ANULACIÓN </label>
                                            <br>
                                            <p>{{ $motivo_anulacion }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($personaVal == null && $personaAnulado == null)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#E08108; color:white">
                                    <p class='h6'>REGISTRADO POR:</p>
                                </div>
                                <div class="card-body">
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p>{{ $usuarioNombre }}</p>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> EN FECHA </label>
                                            <br>
                                            <p>{{ \Carbon\Carbon::parse($created_at)->format('d/m/Y H:i:s') }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endcan
    </div>



</x-ver-dos-modal>


<script>
    window.addEventListener('modalVerAut', event => {

        $('#verAut').modal('show');

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
