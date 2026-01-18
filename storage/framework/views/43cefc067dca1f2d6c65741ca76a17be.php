



<?php $__env->startSection('title', 'إدارة التقييمات'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-star"></i> إدارة التقييمات</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">جميع التقييمات والملاحظات من المستخدمين</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>قيمة التقييم</th>
                                    <th>المستخدم</th>
                                    <th>المستشفى المُقيّمة</th>
                                    <th>طلب الطوارئ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?php echo e($feedback->rating); ?> / 5
                                            </span>
                                        </td>
                                        <td><?php echo e($feedback->user->full_name ?? 'مستخدم محذوف'); ?></td>
                                        <td><?php echo e($feedback->hospital->hospital_name ?? 'غير محدد'); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.emergency_requests.show', $feedback->emergencyRequest->id)); ?>">
                                                #<?php echo e($feedback->emergencyRequest->id); ?>

                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.ratings.show', $feedback->id)); ?>" class="btn btn-xs btn-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                            <form action="<?php echo e(route('admin.ratings.destroy', $feedback->id)); ?>" method="POST" style="display:inline-block;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')" title="حذف">
                                                    <i class="fas fa-trash"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">لا يوجد تقييمات أو ملاحظات حتى الآن.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    <?php echo e($feedbacks->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/feedback/index.blade.php ENDPATH**/ ?>