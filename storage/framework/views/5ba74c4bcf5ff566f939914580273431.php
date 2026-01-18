 

<?php $__env->startSection('title', 'سجل إشعارات المستشفى'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            
            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('warning')): ?>
                <div class="alert alert-warning"><?php echo e(session('warning')); ?></div>
            <?php endif; ?>
            <?php if(session('info')): ?>
                <div class="alert alert-info"><?php echo e(session('info')); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إشعاراتي الواردة (<?php echo e($notifications->total() ?? 0); ?>)</h3>
                    <div class="card-tools">
                        
                        <form action="<?php echo e(route('hospital.notifications.markAllAsRead')); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('هل أنت متأكد من وضع علامة مقروء على جميع الإشعارات غير المقروءة؟')">
                                <i class="fas fa-check-double"></i> وضع الكل كمقروء
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 20%">العنوان</th>
                                <th>نص الرسالة</th>
                                <th style="width: 10%">الحالة</th>
                                <th style="width: 15%">تاريخ الإرسال</th>
                                <th style="width: 10%">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                
                                <tr <?php if(!$notification->is_read): ?> class="table-warning font-weight-bold" <?php endif; ?>>
                                    <td><?php echo e($notifications->firstItem() + $loop->index); ?></td>
                                    <td><?php echo e($notification->title); ?></td>
                                    <td>
                                        <?php if($notification->type === 'emergency' && is_string($notification->message)): ?>
                                            
                                            إشعار بلاغ طوارئ جديد (<?php echo e(\Illuminate\Support\Str::limit($notification->message, 80)); ?>)
                                        <?php else: ?>
                                            <?php echo e(\Illuminate\Support\Str::limit($notification->message, 120)); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($notification->is_read): ?>
                                            <span class="badge badge-success">مقروء</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">غير مقروء</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($notification->created_at->diffForHumans()); ?></td>
                                    <td>
                                        
                                        <form action="<?php echo e(route('hospital.notifications.update', $notification->id)); ?>" method="POST" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <button type="submit" 
                                                    class="btn btn-sm <?php if($notification->is_read): ?> btn-secondary disabled <?php else: ?> btn-primary <?php endif; ?>" 
                                                    <?php if($notification->is_read): ?> disabled <?php endif; ?>
                                                    title="عرض وتأكيد الإشعار">
                                                <i class="fas fa-eye"></i> عرض
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد إشعارات لعرضها حالياً.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <?php echo e($notifications->links()); ?>

                </div>
            </div>
            </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.hospital', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/hospital_admin/notifications/index.blade.php ENDPATH**/ ?>