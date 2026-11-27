<div>

    <div class="card-body">
        @livewire('afiliacion.examen-medico.examen-medico-register')
    </div>
    <div class="card">
        <div class="header" style="background-color:#283747">
            <h4 style="text-align:center; color:white"><b>EXÁMENES MÉDICOS</b></h4>
        </div>
        <div class="card-body">
            @livewire('afiliacion.examen-medico.examen-medico-table')


            <!--Modal para editar datos de asegurado -->
            @include('livewire.Afiliacion.examen-medico.modalVerAgenda')
            
        </div>
    </div>

</div>
