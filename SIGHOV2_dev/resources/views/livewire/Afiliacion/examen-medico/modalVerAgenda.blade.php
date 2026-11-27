<x-big-component id="modalAgenda" labelledby="modalAgendaLabel" title="AGENDA DEL BENEFICIARIO"
    saveAction="">

    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>TITULAR</b></div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 35px">
                            <label for="estado_vigencia">
                                @if ($estado_vigencia == 'VIGENTE')
                                    <span style="color: green;"><b>VIGENTE</b></span>
                                @elseif($estado_vigencia == 'CESANTE')
                                    <span style="color: yellow;"><b>CESANTE</b></span>
                                @elseif($estado_vigencia == 'NO VIGENTE')
                                    <span style="color: red;"><b>NO VIGENTE</b></span>
                                @endif
                            </label>
                            <br>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="matricula" style='color:#2874A6;'> MATRICULA</label>
                            <input class="form-control" name="matricula" id="matricula" wire:model="matricula_seguro_titular"
                                disabled>
                                <br>
                        </div>
                        <div class='col-lg-7 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ci">CARNET </label>
                            <input type="number" class="form-control" disabled  wire:model="ci_titular"
                                name="ci" id="ci" >
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="complemento_m">COMPLEMENTO </label>
                            <input type="number" class="form-control" disabled 
                                wire:model="complemento_titular" name="complemento_m" id="complemento_m"
                                >
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nombre">NOMBRE </label>
                            <input type="text" class="form-control" disabled 
                                wire:model="nombre_titular" name="nombre" id="nombre"
                                >
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_paterno">PRIMER APELLIDO </label>
                            <input type="text" class="form-control" disabled
                                wire:model="p_apellido_titular" name="ap_paterno" id="ap_paterno"
                                >
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_materno">SEGUNDO APELLIDO </label>
                            <input type="text" class="form-control" disabled 
                                wire:model="s_apellido_titular" name="ap_materno" id="ap_materno"
                                >
                        </div>
                        <div class='col-lg-8 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA DE NACIMIENTO </label>
                            <input type="text" class="form-control" disabled 
                                wire:model="fecha_nacimiento_titular" name="fecha_nacimiento"
                                 id="fecha_nacimiento">
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo_titular"> SEXO</label>
                            <br>
                            @if($sexo_titular == 'F' || 1 )
                                <span>FEMENINO</span>
                            @elseif($sexo_titular == 'M'||0)
                                <span>MASCULINO</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>BENEFICIARIO</b></div>
                
                <div class="card-body">
                <div class="row">
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="matricula" style='color:#BA4A00;'> MATRICULA</label>
                            <input class="form-control" name="matricula" id="matricula" wire:model="matricula_seguro_ben"
                                disabled>
                                <br>
                        </div>
                        
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nombre">NOMBRE </label>
                            <input type="text" class="form-control" disabled 
                                wire:model="nombre_ben" name="nombre" id="nombre"
                                >
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_paterno">PRIMER APELLIDO </label>
                            <input type="text" class="form-control" disabled
                                wire:model="p_apellido_ben" name="ap_paterno" id="ap_paterno"
                                >
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_materno">SEGUNDO APELLIDO </label>
                            <input type="text" class="form-control" disabled 
                                wire:model="s_apellido_ben" name="ap_materno" id="ap_materno"
                                >
                        </div>
                        <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ci">CARNET </label>
                            <input type="number" class="form-control" disabled  wire:model="ci_ben"
                                name="ci" id="ci" >
                        </div>
                        <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
                            <label for="complemento_m">COMPL. </label>
                            <input type="number" class="form-control" disabled 
                                wire:model="complemento_ben" name="complemento_m" id="complemento_m"
                                >
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA DE NACIMIENTO </label>
                            <input type="text" class="form-control" disabled 
                                wire:model="fecha_nacimiento_ben" name="fecha_nacimiento"
                                 id="fecha_nacimiento">
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo_ben"> SEXO</label>
                            <br>
                            @if($sexo_ben == 'F' || 1 )
                                <span>FEMENINO</span>
                            @elseif($sexo_ben == 'M'||0)
                                <span>MASCULINO</span>
                            @endif
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>DESCRIPCIÓN DE LA AGENDA</b></div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <label for="especialista">ESPECIALISTA</label>
                            <input type="text" class="form-control" disabled wire:model="especialista">
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <label for="especialidad">ESPECIALIDAD</label>
                            <input type="text" class="form-control" disabled wire:model="especialidad">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha">FECHA</label>
                            <input type="text" class="form-control" disabled wire:model="fecha">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="horario">HORARIO</label>
                            <input type="text" class="form-control" disabled wire:model="horario">
                        </div>
                    </div>
                </div>
            </div>
                    
        </div>


    </div>

    </x-big-component>
<script>
    window.addEventListener('modalVerAgendado', event => {
        $('#modalAgenda').modal('show');
    });

    
</script>