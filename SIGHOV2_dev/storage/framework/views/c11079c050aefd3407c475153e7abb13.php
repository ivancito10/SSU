<div>
    <div class="input-group pr-4 mb-3" style="justify-content: space-between; align-items: center;">
        <h4 style="color:#212f3c; margin: 0; text-transform: uppercase;">
            ADMINISTRACIÓN - <b>MANUALES DEL SISTEMA (PDF)</b>
        </h4>
        <button type="button" class="btn btn-outline-success font-weight-bold" style="padding: 6px 14px;"
            onclick="$('#registrarManualModal').modal('show')" title="SUBIR MANUAL PDF">
            <i class="fas fa-plus-square mr-1"></i> <b>REGISTRAR</b>
        </button>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.manuales-file.manuales-table');

$__html = app('livewire')->mount($__name, $__params, 'lw-3983326470-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            <?php echo $__env->make('livewire.Administracion.manuales-file.modalRegistrarManual', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('livewire.Administracion.manuales-file.modalVerManual', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <div wire:loading wire:target="ver_manual,confirmar_estado_manual">
        <?php if (isset($component)) { $__componentOriginal4ecd79f71a8361a29327da669d3faedc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ecd79f71a8361a29327da669d3faedc = $attributes; } ?>
<?php $component = App\View\Components\CargandoDos::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('cargando-dos'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\CargandoDos::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ecd79f71a8361a29327da669d3faedc)): ?>
<?php $attributes = $__attributesOriginal4ecd79f71a8361a29327da669d3faedc; ?>
<?php unset($__attributesOriginal4ecd79f71a8361a29327da669d3faedc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ecd79f71a8361a29327da669d3faedc)): ?>
<?php $component = $__componentOriginal4ecd79f71a8361a29327da669d3faedc; ?>
<?php unset($__componentOriginal4ecd79f71a8361a29327da669d3faedc); ?>
<?php endif; ?>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('deshabilitar_manual', () => {
                Swal.fire({
                    title: '¿Deshabilitar manual?',
                    text: 'El manual dejará de estar visible como activo.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, deshabilitar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('confirmar_estado_manual');
                    }
                });
            });

            Livewire.on('habilitar_manual', () => {
                Swal.fire({
                    title: '¿Habilitar manual?',
                    text: 'El manual volverá a estar activo.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, habilitar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('confirmar_estado_manual');
                    }
                });
            });

            Livewire.on('guardado_manual', () => {
                Swal.fire({
                    title: '¡Manual registrado!',
                    text: 'El archivo PDF se subió al servidor FTP correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            });

            Livewire.on('manual_deshabilitado', () => {
                Swal.fire({
                    title: '¡Deshabilitado!',
                    text: 'Se modificó el estado del manual.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            Livewire.on('manual_habilitado', () => {
                Swal.fire({
                    title: '¡Habilitado!',
                    text: 'Se modificó el estado del manual.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            Livewire.on('alertaErrorManual', (data) => {
                const msg = data?.mensaje || (Array.isArray(data) ? data[0]?.mensaje : null) || 'Ocurrió un error inesperado.';
                Swal.fire({
                    title: 'Error',
                    text: msg,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        });
    </script>
</div>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/manuales-file/manuales-main.blade.php ENDPATH**/ ?>