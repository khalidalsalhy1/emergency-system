



<?php $__env->startSection('title', 'إدارة الأمراض المزمنة'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-virus"></i> إدارة الأمراض المزمنة</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة الأمراض المزمنة</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.diseases.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة مرض جديد
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>اسم المرض</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $diseases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disease): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($disease->id); ?></td>
                                        <td><?php echo e($disease->disease_name); ?></td>
                                        <td><?php echo e(Str::limit($disease->description, 70) ?? 'لا يوجد وصف'); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.diseases.edit', $disease->id)); ?>" class="btn btn-xs btn-warning" title="تعديل">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                            
                                            <button type="button" class="btn btn-xs btn-danger delete-btn" data-id="<?php echo e($disease->id); ?>" title="حذف دائم">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                            
                                            <form id="delete-form-<?php echo e($disease->id); ?>" 
                                                  action="<?php echo e(route('admin.diseases.destroy', $disease->id)); ?>" 
                                                  method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">لا توجد أمراض مزمنة مسجلة حالياً.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    <?php echo e($diseases->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // منطق حذف SweetAlert2
        $(document).ready(function() {
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var itemId = $(this).data('id');
                var formId = '#delete-form-' + itemId;
                
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن حذف هذا المرض!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، قم بالحذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(formId).submit();
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/diseases/index.blade.php ENDPATH**/ ?>