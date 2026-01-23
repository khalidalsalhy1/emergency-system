

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">تعديل نوع الإصابة: <?php echo e($injuryType->injury_name); ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.injury_types.index')); ?>">أنواع الإصابات</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">تعديل بيانات نوع الإصابة</h3>
                    </div>
                    
                    <form action="<?php echo e(route('admin.injury_types.update', $injuryType->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?> 
                        
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label for="injury_name">اسم الإصابة</label>
                                <input type="text" name="injury_name" class="form-control <?php $__errorArgs = ['injury_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="injury_name" 
                                    value="<?php echo e(old('injury_name', $injuryType->injury_name)); ?>" placeholder="مثال: حروق من الدرجة الأولى">
                                <?php $__errorArgs = ['injury_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="form-group">
                                <label for="description">الوصف (اختياري)</label>
                                <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" rows="3" placeholder="وصف موجز لنوع الإصابة"><?php echo e(old('description', $injuryType->description)); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">تحديث البيانات</button>
                            <a href="<?php echo e(route('admin.injury_types.index')); ?>" class="btn btn-default float-left">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/injury_types/edit.blade.php ENDPATH**/ ?>