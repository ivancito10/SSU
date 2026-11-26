<div>
    <?php if (isset($component)) { $__componentOriginal540536fc77dea9e18870880c919e698a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal540536fc77dea9e18870880c919e698a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-small-sin-footer','data' => ['id' => 'modalReporteInyecciones','labelledby' => 'modalReporteInyeccionesLabel','title' => ' REPORTE EXCEL DE INYECCIONES','wire:ignore.self' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal-small-sin-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalReporteInyecciones','labelledby' => 'modalReporteInyeccionesLabel','title' => ' REPORTE EXCEL DE INYECCIONES','wire:ignore.self' => true]); ?>

        <div class="row">
            <div class="col-12" style="text-align:left;">
                <div class="card cabecera">
                    <b> FILTROS DEL REPORTE</b>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mt-2">
                <label for="reporteFechaInicio">FECHA DESDE:</label>
                <input type="date" id="reporteFechaInicio"
                    class="form-control input-comun <?php $__errorArgs = ['reporteFechaInicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    wire:model="reporteFechaInicio" max="<?php echo e(now()->format('Y-m-d')); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['reporteFechaInicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><b><?php echo e($message); ?></b></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mt-2">
                <label for="reporteFechaFin">FECHA HASTA:</label>
                <input type="date" id="reporteFechaFin"
                    class="form-control input-comun <?php $__errorArgs = ['reporteFechaFin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    wire:model="reporteFechaFin" max="<?php echo e(now()->format('Y-m-d')); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['reporteFechaFin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><b><?php echo e($message); ?></b></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-12 mt-2">
                <label for="reporteEstado">ESTADO:</label>
                <select id="reporteEstado" class="form-control input-comun" wire:model="reporteEstado">
                    <option value="TODOS">TODOS</option>
                    <option value="ACTIVOS">SOLO ACTIVOS</option>
                    <option value="ANULADOS">SOLO ANULADOS</option>
                </select>
            </div>

            <div class="col-12 mt-2">
                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>
                    Si deja las fechas vacías se exportarán todos los registros.</small>
            </div>
        </div>

        <div class="modal-footer pt-3 pb-0 px-0" style="border-top: none !important;">
            <?php if (isset($component)) { $__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561 = $attributes; } ?>
<?php $component = App\View\Components\CancelButton::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('cancel-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\CancelButton::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-dismiss' => 'modal']); ?>CANCELAR <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561)): ?>
<?php $attributes = $__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561; ?>
<?php unset($__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561)): ?>
<?php $component = $__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561; ?>
<?php unset($__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal9c23d2621cd1bc4c240085ffb164f70b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.reportes-button','data' => ['wire:click' => 'exportarExcel','wire:loading.attr' => 'disabled','wire:target' => 'exportarExcel']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('reportes-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'exportarExcel','wire:loading.attr' => 'disabled','wire:target' => 'exportarExcel']); ?>
                <span wire:loading.remove wire:target="exportarExcel">DESCARGAR EXCEL</span>
                <span wire:loading wire:target="exportarExcel"><i class="fas fa-spinner fa-spin"></i> GENERANDO...</span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b)): ?>
<?php $attributes = $__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b; ?>
<?php unset($__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9c23d2621cd1bc4c240085ffb164f70b)): ?>
<?php $component = $__componentOriginal9c23d2621cd1bc4c240085ffb164f70b; ?>
<?php unset($__componentOriginal9c23d2621cd1bc4c240085ffb164f70b); ?>
<?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal540536fc77dea9e18870880c919e698a)): ?>
<?php $attributes = $__attributesOriginal540536fc77dea9e18870880c919e698a; ?>
<?php unset($__attributesOriginal540536fc77dea9e18870880c919e698a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal540536fc77dea9e18870880c919e698a)): ?>
<?php $component = $__componentOriginal540536fc77dea9e18870880c919e698a; ?>
<?php unset($__componentOriginal540536fc77dea9e18870880c919e698a); ?>
<?php endif; ?>
</div>
<script>
    window.addEventListener('abrirModalReporteInyecciones', () => {
        $('#modalReporteInyecciones').modal('show');
    });
    window.addEventListener('cerrarModalReporteInyecciones', () => {
        $('#modalReporteInyecciones').modal('hide');
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/livewire/enfermeria/inyectables/modalReporteExcel.blade.php ENDPATH**/ ?>