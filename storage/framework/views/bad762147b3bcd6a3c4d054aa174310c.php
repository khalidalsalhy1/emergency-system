
<?php
    // مصفوفة ترجمة الحالات للعربية
    $statusMapping = [
        'pending' => 'قيد الانتظار',
        'in_progress' => 'قيد التنفيذ',
        'completed' => 'مكتملة',
        'canceled' => 'ملغاة',
    ];

    // تحديد لون البادج ولون الصف (الظل الخفيف) بناءً على الحالة
    switch($status) {
        case 'pending':
            $badgeClass = 'badge-danger';    // البادج أحمر
            $rowClass = 'table-danger';      // الصف ظل أحمر
            break;
        case 'in_progress':
            $badgeClass = 'badge-warning';   // البادج أصفر
            $rowClass = 'table-warning';     // الصف ظل أصفر
            break;
        case 'completed':
            $badgeClass = 'badge-success';   // البادج أخضر
            $rowClass = 'table-success';     // الصف ظل أخضر
            break;
        case 'canceled':
            $badgeClass = 'badge-secondary'; // البادج رمادي
            $rowClass = 'table-secondary';   // الصف ظل رمادي
            break;
        default:
            $badgeClass = 'badge-info';
            $rowClass = '';
            break;
    }

    // نص الحالة بالعربية
    $displayStatus = $statusMapping[$status] ?? ucfirst(str_replace('_', ' ', $status));
?>



<?php if(isset($row) && $row === true): ?>
    
    <?php echo e($rowClass); ?>

<?php else: ?>
    
    <span class="badge <?php echo e($badgeClass); ?> shadow-sm px-2 py-1">
        <?php echo e($displayStatus); ?>

    </span>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/emergency_requests/partials/status_badge.blade.php ENDPATH**/ ?>