<div>
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4>FISIOTERAPIA - <b>LISTA DE FISIOTERAPEUTAS</b></h4>
        @can('fisioterapia.administrar')
            <x-agregar-button wire:click="crear" title="REGISTRAR FISIOTERAPEUTA">REGISTRAR</x-agregar-button>        
        @endcan
    </div>
    <div class="card-body pt-2 pr-0 pl-0">
        <div class="card-body">
            @livewire('fisioterapia.administracion.fisioterapeutas.fisioterapeutas-table')
            @include('livewire.fisioterapia.administracion.fisioterapeutas.modalFormularioHorario')
            @include('livewire.fisioterapia.administracion.fisioterapeutas.modalVerFisioterapeuta')
        </div>
    </div>
</div>

@script
<script>
    $wire.on('mostrar-formulario-horario', () => $('#formularioHorario').modal('show'));
    $wire.on('confirmar-horario', async () => {
        const respuesta = await Swal.fire({icon:'question', title:'¿Registrar horario semanal?', showCancelButton:true,
            confirmButtonText:'Sí, guardar', cancelButtonText:'Cancelar'});
        if (respuesta.isConfirmed) await $wire.guardarHorario();
    });
    $wire.on('horario-registrado', () => {
        $('#formularioHorario').modal('hide');
        Swal.fire('Horario semanal registrado', '', 'success');
    });
    $wire.on('abrir-ficha-fisioterapeuta', () => $('#fichaFisioterapeuta').modal('show'));
    $wire.on('confirmar-estado-fisioterapeuta', async ({ nombre, id, estado }) => {
        const respuesta = await Swal.fire({
            icon: 'warning', title: estado ? 'Rehabilitar Fisioterapeuta' : 'Deshabilitar Fisioterapeuta', text: nombre,
            showCancelButton: true, confirmButtonText: estado ? 'Sí, rehabilitar' : 'Sí, deshabilitar', cancelButtonText: 'Cancelar'
        });
        if (respuesta.isConfirmed) await $wire.confirmarEstado(id, estado);
    });
    $wire.on('estado-fisioterapeuta-guardado', ({ estado }) => Swal.fire(estado ? 'Fisioterapeuta rehabilitado' : 'Fisioterapeuta deshabilitado', '', 'success'));
    $wire.on('error-fisioterapeutas', () => Swal.fire('No se pudo completar la consulta u operación', 'Verifique la conexión con el servicio e intente nuevamente.', 'error'));
</script>
@endscript
