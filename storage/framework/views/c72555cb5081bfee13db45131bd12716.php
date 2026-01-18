



<?php $__env->startSection('title', 'إدارة الإرشادات الصحية'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-book-medical"></i> إدارة الإرشادات الصحية</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة الإرشادات الصحية</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.health_guides.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة إرشاد جديد
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>العنوان</th>
                                    <th>التصنيف</th>
                                    
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $guides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($guide->title); ?></td>
                                        <td><span class="badge badge-secondary"><?php echo e($guide->category ?? 'عام'); ?></span></td>
                                        
                                        
                                        
                                        <td><?php echo e($guide->created_at->format('Y-m-d')); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('admin.health_guides.show', $guide->id)); ?>" class="btn btn-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.health_guides.edit', $guide->id)); ?>" class="btn btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin.health_guides.destroy', $guide->id)); ?>" method="POST" style="display:inline-block;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الإرشاد؟')" title="حذف">
                                                        <i class="fas fa-trash"></i> 
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        
                                        <td colspan="5" class="text-center">لا يوجد إرشادات صحية مسجلة حاليًا.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    <?php echo e($guides->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/health_guides/index.blade.php ENDPATH**/ ?>