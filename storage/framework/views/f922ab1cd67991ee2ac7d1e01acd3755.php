 

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إدارة المستشفيات</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                    <li class="breadcrumb-item active">المستشفيات</li>
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
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> تم!</h5>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> خطأ!</h5>
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="card card-primary card-outline shadow">
                    <div class="card-header border-0">
                        <h3 class="card-title">قائمة المستشفيات المسجلة</h3>
                        
                        <div class="card-tools d-flex align-items-center">
                            
                            <form action="<?php echo e(route('admin.hospitals.index')); ?>" method="GET" class="form-inline ml-3 mr-4">
                                <div class="input-group input-group-sm border rounded">
                                    <input class="form-control form-control-navbar border-0" type="search" name="keyword" 
                                           placeholder="بحث باسم أو مدينة أو هاتف" aria-label="Search" 
                                           value="<?php echo e(request('keyword')); ?>"> 
                                    
                                    <div class="input-group-append">
                                        <button class="btn btn-navbar bg-white border-0" type="submit">
                                            <i class="fas fa-search text-primary"></i>
                                        </button>
                                        <?php if(request()->filled('keyword')): ?>
                                            <a href="<?php echo e(route('admin.hospitals.index')); ?>" class="btn btn-navbar bg-white border-0">
                                                <i class="fas fa-times text-danger"></i> 
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                            
                            
                            <a href="<?php echo e(route('admin.hospitals.create')); ?>" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                                <i class="fas fa-plus-circle"></i> إضافة مستشفى جديد
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <?php if($hospitals->isEmpty()): ?>
                            <div class="p-5 text-center">
                                <div class="alert alert-info d-inline-block shadow-sm px-5">
                                    <?php if(request()->filled('keyword')): ?>
                                        <i class="fas fa-search-minus fa-2x d-block mb-2"></i> لا توجد نتائج مطابقة لـ "<?php echo e(request('keyword')); ?>".
                                    <?php else: ?>
                                        <i class="fas fa-hospital fa-2x d-block mb-2"></i> لا توجد مستشفيات مسجلة حالياً.
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="bg-light border-top">
                                    <tr>
                                        <th style="width: 60px" class="text-center">#</th>
                                        <th>اسم المستشفى</th>
                                        <th>رقم الهاتف</th>
                                        <th>رقم الطوارئ</th>
                                        <th>المدينة</th>
                                        <th class="text-center" style="min-width: 180px">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $hospitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hospital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            <?php echo e($loop->iteration + ($hospitals->perPage() * ($hospitals->currentPage() - 1))); ?>

                                        </td>
                                        <td class="font-weight-bold"><?php echo e($hospital->hospital_name); ?></td>
                                        <td><?php echo e($hospital->phone); ?></td>
                                        <td><span class="text-danger font-weight-bold"><?php echo e($hospital->emergency_number ?? '-'); ?></span></td>
                                        <td><span class="badge badge-info px-2 py-1"><?php echo e($hospital->city ?? 'غير محدد'); ?></span></td>
                                        <td class="text-center">
                                            
                                            
                                            
                                            <a href="<?php echo e(route('admin.hospitals.show', $hospital->id)); ?>" 
                                               class="btn btn-info btn-sm shadow-sm mr-1" 
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            
                                            <a href="<?php echo e(route('admin.hospitals.edit', $hospital->id)); ?>" 
                                                class="btn btn-xs btn-warning btn-sm" 
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm shadow-sm delete-hospital" 
                                                    data-id="<?php echo e($hospital->id); ?>" 
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>

                    <?php if($hospitals->hasPages()): ?>
                        <div class="card-footer clearfix bg-white border-top">
                            <div class="float-right">
                                <?php echo e($hospitals->links('pagination::bootstrap-4')); ?>

                            </div>
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
    $(document).ready(function() {
        const deleteForm = document.getElementById('deleteForm');
        
        $('.delete-hospital').on('click', function(e) {
            e.preventDefault(); 
            const hospitalId = $(this).data('id'); 
            
            if (confirm('هل أنت متأكد من حذف هذا المستشفى؟ سيتم حذف كافة البيانات والمسؤولين المرتبطين به.')) {
                const deleteUrl = "<?php echo e(route('admin.hospitals.destroy', ['hospital' => ':id'])); ?>";
                deleteForm.action = deleteUrl.replace(':id', hospitalId);
                deleteForm.submit();
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/hospitals/index.blade.php ENDPATH**/ ?>