



<?php $__env->startSection('title', 'إدارة المواقع الجغرافية'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-map-marked-alt"></i> إدارة المواقع الجغرافية</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة بجميع المواقع المسجلة</h3>
                    
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان التقديري</th>
                                    <th>مرتبط بمستشفى</th>
                                    <th>مسجل للمريض</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($location->address ?? 'لا يوجد عنوان'); ?></td>
                                        <td>
                                            <?php if($location->hospital): ?>
                                                <span class="badge badge-info"><?php echo e($location->hospital->hospital_name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">لا يوجد</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($location->user): ?>
                                                <span class="text-dark"><?php echo e($location->user->full_name ?? $location->user->name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">لا يوجد</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                
                                                <a href="<?php echo e(route('admin.locations.show', $location->id)); ?>" class="btn btn-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                
                                                <?php if(!$location->user_id && $location->emergencyRequests->isEmpty()): ?>
                                                    
                                                    <a href="<?php echo e(route('admin.locations.edit', $location->id)); ?>" class="btn btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    
                                                    <form action="<?php echo e(route('admin.locations.destroy', $location->id)); ?>" method="POST" style="display:inline-block;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الموقع؟')" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">لا يوجد مواقع جغرافية مسجلة حاليًا.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    <?php echo e($locations->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/locations/index.blade.php ENDPATH**/ ?>