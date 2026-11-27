<!--MODAL PARA VER -->
<div>
    <x-ver-modal id="vistaModal" labelledby="vistaModalLabel" title="VISTA DE INSTITUCIÓN REGISTRADA ">
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <p style="background-color:#1a5276; color:white; border-radius:10px; ">ACTUAL INSTITUCIÓN </p>
                            </div>
                        </div>
                        <div class="row">
                             
                                
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <p style="font-size:19px; color: #283747 ;"><b><em> {{$nombre}}</em> </b></p>
                                </div>
                            
                        </div>
                         
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>NIT: </b> {{ $nit }} </span>

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span> <b>NÚMERO DE EMPLEADOR: </b> {{ $num_empleador }} </span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b> ACRÓNIMO: </b>{{ $acronimo }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b> ACTIVIDAD EMPLEADOR: </b>{{ $actividad_empleador }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b> TIPO DE APORTANTE: </b>{{ $tipoclaseaportante }} </span>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b> CÓDIGO DE COMERCIO: </b>{{ $tipo_comercio }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b> FECHA DE AFILIACIÓN: </b>{{ $fecha_afiliacion }} </span>
                            </div>

                        </div>
                        <br>

                        <div class="row">
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                                <spam><b>ES TGN: </b></spam>
                                @if ($tgn == true)
                                    <spam style="color: #145a32;"><b>SI</b></spam>
                                @else
                                    <spam style="color: #c0392b;"><b>NO</b></spam>
                                @endif
                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12' style="text-align:left;">

                                <spam><b>ESTADO: </b></spam>
                                @if ($estado == true)
                                    <spam style="color: #145a32;"><b>ACTIVO</b></spam>
                                @else
                                    <spam style="color: #c0392b;"><b>INACTIVO</b></spam>
                                @endif
                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                                <spam><b>ES DE TIPO: </b></spam>
                                @if ($tipo_institucion == true)
                                    <spam style="color: black;">INSTITUCIÓN</spam>
                                @else
                                    <spam style="color: black;">EMPRESA</spam>
                                @endif
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                                <spam><b>ES: </b></spam>
                                @if ($publica == true)
                                    <spam style="color:black;">PÚBLICA</spam>
                                @else
                                    <spam style="color:black;">PRIVADA</spam>
                                @endif

                            </div>

                            



                        </div>
                        <br>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <button class="btn btn-warning" wire:click='abrir_file_nit'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
                                        <path
                                            d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
                                    </svg>
                                    <b> ABRIR NIT</b></button>
                            </div>

                            

                        </div>

                    </div>
                </div>

            </div>
            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em> Datos
                                                Referenciales.</em> </spam>
                                    </b>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                style="background-color:white ; ">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span><b>DIRECCIÓN: </b> {{ $direccion }} </span>

                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b>NÚMERO DE PUERTA: </b>{{ $numero }} </span>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b>ZONA: </b>{{ $zona }} </span>

                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b>REFERENCIA DE LA DIRECCIÓN:
                                            </b>{{ $direccion_referencia }} </span>

                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                        style="text-align:left;">
                                        <p style="background-color:white; color:#1f618d;" class="h5">
                                            <b>
                                                <spam style="text-decoration:underline;"><em>
                                                        Contactos.</em> </spam>
                                            </b>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span><b>1er. TÉLEFONO: </b> {{ $telefono }} </span>

                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span><b>2do. TÉLEFONO: </b> {{ $telefono_dos }} </span>

                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b>CORREO ELECTRÓNICO: </b> {{ $correo }} </span>
                                    </div>
                                </div>
                                <br>
                                <div class="row">

                                    <!--    MODAL PARA VER LA UBICACION -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                        style="text-align:right;">
                                        <x-ver-button wire:click="AbrirModalUbicacionInstitucion"><b>UBICACIÓN </b>
                                            </x-ver-dos-button>

                                            @include('livewire.Aportes.institucion.modalUbicacionInstitucion')
                                    </div>
                                </div>

                            </div>
                        </div>
                           
                        
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"> <em> Datos del Representante Legal <em> </spam>
                                    </b>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b> NOMBRE COMPLETO: </b> {{ $nombres }} {{ $p_apellido }}
                                    {{ $s_apellido }} </span>

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span> <b>CARNET: </b> {{ $ci }} {{ $complemento }}</span>

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span> <b>FECHA DE NACIMIENTO: </b> {{ $fecha_nacimiento_m }} </span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <button class="btn btn-info" wire:click='abrir_carnet'> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                        <path
                                            d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                    </svg>
                                    <b> ABRIR CARNET</b></button>
                            </div>

                        </div>

                    </div>


                </div>

            </div>




        </div>

    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalAbrirInstitucion', event => {
        $('#vistaModal').modal('show');
    });
</script>
