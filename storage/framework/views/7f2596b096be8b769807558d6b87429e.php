 

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إدارة أنواع الإصابات</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                    <li class="breadcrumb-item active">أنواع الإصابات</li>
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
                        <h3 class="card-title">قائمة أنواع الإصابات</h3>
                        <div class="card-tools">
                            <a href="<?php echo e(route('admin.injury_types.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> إضافة نوع جديد
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if($injuryTypes->isEmpty()): ?>
                            <div class="alert alert-info text-center">
                                لا توجد أنواع إصابات مسجلة حالياً.
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>اسم الإصابة</th>
                                        <th>الوصف</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $injuryTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $injuryType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration + ($injuryTypes->perPage() * ($injuryTypes->currentPage() - 1))); ?>.</td>
                                        <td><?php echo e($injuryType->injury_name); ?></td>
                                        <td><?php echo e(Str::limit($injuryType->description, 50) ?? '-'); ?></td>
                                        <td>
                                            
                                            <a href="<?php echo e(route('admin.injury_types.edit', $injuryType->id)); ?>" class="btn btn-warning btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-danger btn-sm delete-injury-type" data-id="<?php echo e($injuryType->id); ?>" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($injuryTypes->hasPages()): ?>
                        <div class="card-footer clearfix">
                            <?php echo e($injuryTypes->links('pagination::bootstrap-4')); ?>

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
    // كود JavaScript لتفعيل زر الحذف (باستخدام jQuery)
    $(document).ready(function() {
        
        const deleteForm = document.getElementById('deleteForm');
        
        $('.delete-injury-type').on('click', function(e) {
            e.preventDefault(); 

            const injuryTypeId = $(this).data('id'); 
            
            if (confirm('هل أنت متأكد من أنك تريد حذف هذا النوع من الإصابات؟ قد يؤثر على بيانات الطوارئ المرتبطة.')) {
                // بناء مسار الحذف الديناميكي
                const deleteUrl = "<?php echo e(route('admin.injury_types.destroy', ['injuryType' => ':id'])); ?>";
                deleteForm.action = deleteUrl.replace(':id', injuryTypeId);
                
                // إرسال طلب DELETE
                deleteForm.submit();
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/injury_types/index.blade.php ENDPATH**/ ?>