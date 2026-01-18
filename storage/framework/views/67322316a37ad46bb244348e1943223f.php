 

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إدارة مسؤولي المستشفيات</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                    <li class="breadcrumb-item active">مسؤولو المستشفيات</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
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
                        <h3 class="card-title">قائمة مسؤولي المستشفيات</h3>
                        <div class="card-tools">
                            <a href="<?php echo e(route('admin.hospital_admins.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> إضافة مسؤول جديد
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if($hospitalAdmins->isEmpty()): ?>
                            <div class="alert alert-info text-center">
                                لا توجد حسابات لمسؤولي المستشفيات مسجلة حالياً.
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>الاسم الكامل</th>
                                        <th>المستشفى المرتبط</th>
                                        <th>رقم الهاتف</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $hospitalAdmins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration + ($hospitalAdmins->perPage() * ($hospitalAdmins->currentPage() - 1))); ?>.</td>
                                        <td><?php echo e($admin->full_name); ?></td>
                                        <td><?php echo e($admin->hospital->hospital_name ?? 'غير مرتبط'); ?></td>
                                        <td><?php echo e($admin->phone); ?></td>
                                        <td><?php echo e($admin->email ?? '-'); ?></td>
                                        <td>
                                            <?php if($admin->status === 'active'): ?>
                                                <span class="badge badge-success">نشط</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">غير نشط</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.hospital_admins.edit', $admin->id)); ?>" class="btn btn-warning btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-admin" data-id="<?php echo e($admin->id); ?>" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <?php if($hospitalAdmins->hasPages()): ?>
                        <div class="card-footer clearfix">
                            <?php echo e($hospitalAdmins->links('pagination::bootstrap-4')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    
    <form id="deleteForm" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // كود JavaScript لتفعيل زر الحذف
    $(document).ready(function() {
        const deleteForm = document.getElementById('deleteForm');
        
        $('.delete-admin').on('click', function(e) {
            e.preventDefault(); 

            const adminId = $(this).data('id'); 
            
            if (confirm('هل أنت متأكد من أنك تريد حذف هذا المسؤول؟ سيتم حذفه حذفاً ناعماً. لا يمكن التراجع عن هذه العملية.')) {
                // بناء مسار الحذف الديناميكي
                const deleteUrl = "<?php echo e(route('admin.hospital_admins.destroy', ['hospital_admin' => ':id'])); ?>";
                deleteForm.action = deleteUrl.replace(':id', adminId);
                
                // إرسال طلب DELETE
                deleteForm.submit();
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/hospital_admins/index.blade.php ENDPATH**/ ?>