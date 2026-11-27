<?php $__env->startSection('title', 'SIGHO V2'); ?>

<?php $__env->startSection('content_header'); ?>
    <div
        style="
        background: linear-gradient(to right, #f0f4f8,rgb(214, 214, 221));
        padding: 20px 20px;
        border-radius: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 10000px;
        margin: 0 auto;
    ">
        <h1 style="font-size: 2.2em; margin-bottom: 10px;">
            <span style="color: #1f2937;">Bienvenido al</span><br>
            <strong style="color: #2471a3;">Seguro Social Universitario</strong>
            <span style="color: #cb4335;">La Paz</span>
        </h1>
        <p style="color: #6b7280; font-size: 1em; margin-top: 10px;">
            Sistema de Gestión Hospitalaria <strong>SIGHOv2</strong>
        </p>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <!-- <p>Welcome to this beautiful admin panel.</p>-->


    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.dashboard.dashboard-estadisticas');

$__html = app('livewire')->mount($__name, $__params, 'lw-3031626161-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.notificaciones.notificaciones-detalles');

$__html = app('livewire')->mount($__name, $__params, 'lw-3031626161-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.cambio-contrasenia.cambio-contrasenia-inicio');

$__html = app('livewire')->mount($__name, $__params, 'lw-3031626161-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <!-- Para bloquear la pantalla si la UFV no esta actualizada del dia -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.agenda_plataforma')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('aportes.ufv.alertaufv.alerta-ufv-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-3031626161-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>


    <?php app("livewire")->forceAssetInjection(); ?><div x-persist="<?php echo e('player'); ?>">
        <audio src="<?php echo e(asset('sound/BienvenidaSistema.mp3')); ?>" autoplay></audio>
    </div>

    <?php echo $__env->make('numeros_emergencia', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_top_nav_left'); ?>
    <!-- Botón de emergencias -->
    <li class="nav-item me-3">
        <a href="#" class="nav-link" title="Números de emergencia" data-toggle="modal" data-target="#modalEmergencia">
            <i class="fas fa-phone-volume text-danger fa-2x"></i>
        </a>
    </li>

    <!-- Nuevo botón que redirige a una ruta -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.agenda_web')): ?>
        <li class="nav-item me-3">
            <a id="botonAgendamientoWeb" href="<?php echo e(route('plataforma.agenda-web.index')); ?>"
                class="btn btn-primary d-flex align-items-center px-3 py-2 shadow-lg rounded-pill fw-bold animate__animated animate__pulse animate__infinite"
                title="Agendamiento Web">
                <span>Agendamiento Web</span>
            </a>
        </li>
    <?php endif; ?>
    <h3 class="card-title ml-2" style="margin-top:10px;">
        <b class="text-info d-none d-sm-inline">SEGURO SOCIAL UNIVERSITARIO</b>
        <b class="text-info d-none d-sm-none">SSU</b>
        <b class="text-danger d-none d-sm-inline">LA PAZ</b>
    </h3>
    <style>
        .card-title {
            font-size: 1.3rem;
        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1rem;
            }
        }
    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_top_nav_right'); ?>

    <div class="d-none d-sm-inline">
        
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "ed0394bd-c7ad-488d-a461-4125ccbb5411",
                //   appId: "dd52755f-15c1-4bac-9863-984174a3a0fc",

            });
        });
    </script>
    <script src="<?php echo e(asset('js/bloqueos.js')); ?>"></script>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('botonAgendamientoWeb');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    btn.classList.add('disabled');
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.7';
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/dashboard.blade.php ENDPATH**/ ?>