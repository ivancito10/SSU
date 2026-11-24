<aside class="control-sidebar control-sidebar-<?php echo e(config('adminlte.right_sidebar_theme')); ?>">
    <?php echo $__env->yieldContent('right-sidebar'); ?>
    <!-- aqui ira el componente livewire-->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.dashboard.barra-derecha.barra-derecha-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-1766245021-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</aside>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/vendor/adminlte/partials/sidebar/right-sidebar.blade.php ENDPATH**/ ?>