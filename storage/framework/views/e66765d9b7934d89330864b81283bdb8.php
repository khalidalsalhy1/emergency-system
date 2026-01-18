

<?php switch($status):
    case ('pending'): ?>
        <?php $class = 'badge-warning'; ?> 
        <?php break; ?>

    <?php case ('accepted'): ?>
        <?php $class = 'badge-primary'; ?> 
        <?php break; ?>

    <?php case ('dispatched'): ?>
        <?php $class = 'badge-info'; ?> 
        <?php break; ?>
        
    <?php case ('arrived'): ?>
        <?php $class = 'badge-info'; ?> 
        <?php break; ?>

    <?php case ('completed'): ?>
        <?php $class = 'badge-success'; ?> 
        <?php break; ?>

    <?php case ('canceled'): ?>
        <?php $class = 'badge-secondary'; ?> 
        <?php break; ?>

    <?php default: ?>
        <?php $class = 'badge-light'; ?>
<?php endswitch; ?>

<span class="badge <?php echo e($class); ?>">
    <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

</span>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/hospital_admin/emergency_requests/partials/status_badge.blade.php ENDPATH**/ ?>