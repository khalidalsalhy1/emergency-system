

<?php switch($status):
    case ('pending'): ?>
        <?php $class = 'badge-danger'; ?> 
        <?php break; ?>

    <?php case ('in_progress'): ?>
        <?php $class = 'badge-warning'; ?> 
        <?php break; ?>

    <?php case ('completed'): ?>
        <?php $class = 'badge-success'; ?> 
        <?php break; ?>

    <?php case ('canceled'): ?>
        <?php $class = 'badge-secondary'; ?> 
        <?php break; ?>

    <?php default: ?>
        <?php $class = 'badge-info'; ?>
<?php endswitch; ?>

<span class="badge <?php echo e($class); ?>">
    <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

</span>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/emergency_requests/partials/status_badge.blade.php ENDPATH**/ ?>