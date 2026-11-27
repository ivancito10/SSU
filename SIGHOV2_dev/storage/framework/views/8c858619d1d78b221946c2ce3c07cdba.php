<tr
    class="<?php echo e(data_get($theme, 'table.trBodyClass')); ?>"
    style="<?php echo e(data_get($theme, 'table.trBodyStyle')); ?>"
>
    <th
        class="<?php echo e(data_get($theme, 'table.tdBodyEmptyClass')); ?>"
        style="<?php echo e(data_get($theme, 'table.tdBodyEmptyStyle')); ?>"
        colspan="<?php echo e(($checkbox ? 1 : 0) + count($columns) + (data_get($setUp, 'detail.showCollapseIcon') ? 1 : 0)); ?>"
    >
            <?php echo $this->processNoDataLabel(); ?>

    </th>
</tr>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\vendor\power-components\livewire-powergrid\src\Providers/../../resources/views/components/table/th-empty.blade.php ENDPATH**/ ?>