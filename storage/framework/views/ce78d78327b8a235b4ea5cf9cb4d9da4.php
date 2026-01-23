



<?php $__env->startSection('title', 'تعديل مرض: ' . $disease->disease_name); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-edit"></i> تعديل بيانات المرض المزمن</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">تعديل بيانات المرض: <?php echo e($disease->disease_name); ?></h3>
                </div>
                
                <form action="<?php echo e(route('admin.diseases.update', $disease->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        
                        
                        <div class="form-group">
                            <label for="disease_name">اسم المرض</label>
                            <input type="text" name="disease_name" id="disease_name" 
                                   class="form-control <?php $__errorArgs = ['disease_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('disease_name', $disease->disease_name)); ?>" placeholder="مثال: داء السكري، ارتفاع ضغط الدم" required>
                            <?php $__errorArgs = ['disease_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="form-group">
                            <label for="description">وصف موجز (اختياري)</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      placeholder="وصف مختصر للمرض وخطورته."><?php echo e(old('description', $disease->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">حفظ التعديلات</button>
                        <a href="<?php echo e(route('admin.diseases.index')); ?>" class="btn btn-default float-right">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/diseases/edit.blade.php ENDPATH**/ ?>