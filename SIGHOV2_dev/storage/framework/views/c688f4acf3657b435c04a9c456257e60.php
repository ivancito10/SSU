<div>
    <div class="card-borde p-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card cabecera mb-3">
                    <b><i class="fas fa-book mr-1"></i> DATOS DEL MANUAL DEL SISTEMA</b>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <label for="moduloManual" class="font-weight-bold">MÓDULO: <span style="color:#cb4335;">*</span></label>
                <select id="moduloManual" class="form-control input-comun <?php $__errorArgs = ['modulo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="modulo">
                    <option value="">Seleccione un módulo...</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($mod); ?>"><?php echo e($mod); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['modulo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><b><?php echo e($message); ?></b></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <label for="versionManual" class="font-weight-bold">VERSIÓN: <span style="color:#cb4335;">*</span></label>
                <input type="text" id="versionManual" class="form-control input-comun <?php $__errorArgs = ['version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    wire:model="version" placeholder="Ej. 1.0">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><b><?php echo e($message); ?></b></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <label for="tituloManual" class="font-weight-bold">TÍTULO DEL MANUAL: <span style="color:#cb4335;">*</span></label>
                <input type="text" id="tituloManual" class="form-control input-comun <?php $__errorArgs = ['titulo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    wire:model="titulo" placeholder="Ej. Manual de Usuario - Módulo Farmacia">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['titulo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><b><?php echo e($message); ?></b></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <label for="descripcionManual" class="font-weight-bold">DESCRIPCIÓN:</label>
                <textarea id="descripcionManual" rows="2" class="form-control input-comun <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    wire:model="descripcion" placeholder="Breve detalle del contenido del manual (opcional)..."></textarea>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><b><?php echo e($message); ?></b></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                <label for="archivoPdfManual" class="font-weight-bold">
                    <i class="fas fa-file-pdf text-danger mr-1"></i> ARCHIVO PDF (MÁX. 20 MB): <span style="color:#cb4335;">*</span>
                </label>
                <input type="file" id="archivoPdfManual" accept="application/pdf,.pdf"
                    class="form-control-file rounded p-2 <?php $__errorArgs = ['archivo_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border border-danger is-invalid <?php else: ?> border <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    wire:model="archivo_pdf">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['archivo_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><b><?php echo e($message); ?></b></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                <div wire:loading wire:target="archivo_pdf" class="text-info small mt-1 font-weight-bold">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Cargando archivo PDF...
                </div>

                <!--[if BLOCK]><![endif]--><?php if($archivo_pdf): ?>
                    <div class="alert alert-light border mt-2 py-2 px-3 small mb-0">
                        <i class="fas fa-check-circle text-success mr-1"></i>
                        <b>Archivo listo:</b> <?php echo e($archivo_pdf->getClientOriginalName()); ?>

                        (<?php echo e(number_format($archivo_pdf->getSize() / 1024, 1)); ?> KB)
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>

    <div class="modal-footer pt-3 pb-0 px-0" style="border-top: none !important;">
        <button type="button" class="btn btn-outline-danger" wire:click="cancelar_registro_manual" data-dismiss="modal">
            <i class="fas fa-times-circle mr-1"></i> CANCELAR
        </button>
        <button type="button" class="btn btn-outline-success" wire:click="guardar_manual" wire:loading.attr="disabled" wire:target="guardar_manual,archivo_pdf">
            <span wire:loading.remove wire:target="guardar_manual"><i class="fas fa-save mr-1"></i> GUARDAR</span>
            <span wire:loading wire:target="guardar_manual"><i class="fas fa-spinner fa-spin mr-1"></i> SUBIENDO A FTP...</span>
        </button>
    </div>
</div>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/manuales-file/manuales-register.blade.php ENDPATH**/ ?>