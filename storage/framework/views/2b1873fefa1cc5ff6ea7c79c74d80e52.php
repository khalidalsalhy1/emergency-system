 

<?php $__env->startSection('title', 'إدارة المرضى'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">قائمة المرضى</h1>
        <a href="<?php echo e(route('admin.patients.create')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> إضافة مريض جديد
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>الاسم الكامل</th>
                            <th>الهاتف</th>
                            <th>البريد الإلكتروني</th>
                            <th>الهوية الوطنية</th>
                            <th>الحالة</th>
                            <th>فصيلة الدم</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($patient->full_name); ?></td>
                            <td><?php echo e($patient->phone); ?></td>
                            <td><?php echo e($patient->email ?? 'N/A'); ?></td>
                            <td><?php echo e($patient->national_id ?? 'N/A'); ?></td>
                            <td>
                                
                                <span class="badge badge-<?php echo e($patient->status === 'active' ? 'success' : 'danger'); ?>">
                                    <?php echo e($patient->status === 'active' ? 'نشط' : 'غير نشط'); ?>

                                </span>
                            </td>
                            <td><?php echo e($patient->medicalRecord->blood_type ?? 'N/A'); ?></td>
                            <td><?php echo e($patient->created_at->format('Y-m-d')); ?></td>
                            <td>
                                
                                <a href="<?php echo e(route('admin.patients.edit', $patient)); ?>" class="btn btn-primary btn-sm mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                
                                <form action="<?php echo e(route('admin.patients.destroy', $patient)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المريض؟');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm mx-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">لا يوجد مرضى مسجلين حالياً.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                <?php echo e($patients->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/patients/index.blade.php ENDPATH**/ ?>