<div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('super-admin-only')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('Administracion.dashboard.administracion.usuarios-dashboard');

$__html = app('livewire')->mount($__name, $__params, 'lw-3903989005-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('super-admin-only')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('Administracion.dashboard.aportes.aportes-dashboard-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-3903989005-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('super-admin-only')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('Administracion.dashboard.afiliacion.afiliacion-dashboard-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-3903989005-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('super-admin-only')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('Administracion.dashboard.vigencia.vigencia-dashboard-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-3903989005-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('super-admin-only')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('Administracion.dashboard.plataforma.plataforma-dashboard-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-3903989005-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>

    <!-- vista de asegurados afiliados rol pasado: index.informaciones-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('super-admin-only')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('Administracion.dashboard.comunicados.comunicados-dashboard-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-3903989005-5', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>
    <br>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.agenda_web')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('Administracion.dashboard.plataforma.agenda-asegurado');

$__html = app('livewire')->mount($__name, $__params, 'lw-3903989005-6', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>
    <br> <br>
</div>

<!-- Modificar boostrap 5 a 4 -->
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/dashboard/dashboard-estadisticas.blade.php ENDPATH**/ ?>