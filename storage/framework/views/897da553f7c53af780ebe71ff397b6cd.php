



<?php $__env->startSection('title', 'سجل حالة الطلبات'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-history"></i> سجل حالة الطلبات</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">جميع التغييرات في حالات طلبات الطوارئ</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>رقم السجل</th>
                                    <th>رقم الطلب</th>
                                    <th>الحالة الجديدة</th>
                                    <th>تم التغيير بواسطة</th>
                                    <th>تاريخ التغيير</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($history->id); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.emergency_requests.show', $history->emergencyRequest->id)); ?>">
                                                #<?php echo e($history->emergencyRequest->id); ?>

                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo e($history->isCompleted() ? 'success' : ($history->isPending() ? 'warning' : 'info')); ?>">
                                                <?php echo e($history->status); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($history->changedBy->full_name ?? 'غير محدد'); ?></td>
                                        
                                        <td>
                                            <?php echo e(($history->changed_at ?? $history->created_at) ? ($history->changed_at ?? $history->created_at)->format('Y-m-d H:i') : 'غير مسجل'); ?>

                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.request_history.show', $history->id)); ?>" class="btn btn-xs btn-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">لا يوجد سجل لتغيير حالات الطلبات حتى الآن.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    <?php echo e($histories->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/request_history/index.blade.php ENDPATH**/ ?>