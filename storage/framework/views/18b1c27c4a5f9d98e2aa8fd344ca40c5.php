



<?php $__env->startSection('title', 'تفاصيل سجل الطلب #' . $requestStatusHistory->id); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-eye"></i> تفاصيل سجل الطلب #<?php echo e($requestStatusHistory->id); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">بيانات سجل حالة الطلب</h3>
                </div>
                <div class="card-body">
                    
                    <?php
                        // مصفوفات المطابقة الموحدة للتعريب والألوان
                        $statusMapping = [
                            'pending' => 'قيد الانتظار',
                            'in_progress' => 'قيد التنفيذ',
                            'completed' => 'مكتملة',
                            'canceled' => 'ملغاة',
                        ];
                        $statusClasses = [
                            'pending' => 'badge-danger',
                            'in_progress' => 'badge-success',
                            'completed' => 'badge-primary',
                            'canceled' => 'badge-secondary',
                        ];
                    ?>

                    <div class="row">
                        
                        <div class="col-md-6">
                            <strong><i class="fas fa-ambulance mr-1"></i> رقم الطلب المرتبط</strong>
                            <p class="text-dark">
                                <a href="<?php echo e(route('admin.emergency_requests.show', $requestStatusHistory->emergencyRequest->id)); ?>" class="text-dark font-weight-bold">
                                    #<?php echo e($requestStatusHistory->emergencyRequest->id); ?>

                                </a>
                            </p>
                            <hr>
                        </div>

                        
                        <div class="col-md-6">
                            <strong><i class="fas fa-sync-alt mr-1"></i> الحالة الجديدة</strong>
                            <p>
                                <span class="badge <?php echo e($statusClasses[$requestStatusHistory->status] ?? 'badge-info'); ?> badge-lg">
                                    <?php echo e($statusMapping[$requestStatusHistory->status] ?? $requestStatusHistory->status); ?>

                                </span>
                            </p>
                            <hr>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <strong><i class="fas fa-user-tag mr-1"></i> تم التغيير بواسطة</strong>
                            <p class="text-dark"><?php echo e($requestStatusHistory->changedBy->full_name ?? 'النظام/المريض'); ?></p>
                            <hr>
                        </div>

                        
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> تاريخ ووقت التغيير</strong>
                            <p class="text-dark">
                                <?php echo e(($requestStatusHistory->changed_at ?? $requestStatusHistory->created_at) ? ($requestStatusHistory->changed_at ?? $requestStatusHistory->created_at)->format('Y-m-d H:i:s') : 'غير مسجل'); ?>

                            </p>
                            <hr>
                        </div>

                        
                        <div class="col-12">
                            <strong><i class="fas fa-clipboard-list mr-1"></i> ملاحظات/سبب التغيير</strong>
                            <p class="text-dark"><?php echo e($requestStatusHistory->reason ?? 'لا يوجد سبب محدد مسجل.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('admin.request_history.index')); ?>" class="btn btn-default">
                        <i class="fas fa-arrow-right"></i> العودة إلى القائمة
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/request_history/show.blade.php ENDPATH**/ ?>