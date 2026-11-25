<?php $__env->startSection('contenido'); ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.enfermeria')): ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('enfermeria.inyectables.inyectables-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-1109758061-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php else: ?>
    <p style="color: red; font-size: 25px;">
        <em><b>No tienes permisos para acceder a esta sección.</b></em>
    </p>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if(session('evento')): ?>
            let evento = "<?php echo e(session('evento')); ?>";
            if (evento === 'InyeccionRegistrada') {
                Swal.fire({
                    title: "¡Guardado!",
                    text: "La atención de enfermería se registró exitosamente.",
                    icon: "success"
                });
            } else if (evento === 'InyeccionCancelada') {
                Swal.fire({
                    title: "Cancelado",
                    text: "Se canceló el registro.",
                    icon: "error"
                });
            }
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('principal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/Enfermeria/Inyectables/index.blade.php ENDPATH**/ ?>