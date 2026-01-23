

 

<?php $__env->startSection('title', 'تفاصيل طلب الطوارئ #' . $emergencyRequest->id); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-search-location"></i> تفاصيل ومراجعة طلب الطوارئ #<?php echo e($emergencyRequest->id); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        
        <?php if(session('success')): ?>
            <div class="col-12 alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="col-12 alert alert-danger">
                يرجى تصحيح الأخطاء التالية قبل المتابعة:
                <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
        <?php endif; ?>
        
        
        <?php
            $requestTypeMapping = [
                'DISPATCH' => 'طلب إرسال إسعاف',
                'NOTIFY' => 'إبلاغ/بحالة طارئة ',
            ];
            $displayRequestType = $requestTypeMapping[$emergencyRequest->request_type] ?? 'غير معروف';
        ?>

        
        <div class="col-md-7">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">بيانات الطلب والمريض</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-6">
                            <h4><i class="fas fa-user-injured"></i> المريض</h4>
                            <p><strong>الاسم:</strong> <?php echo e($emergencyRequest->user->full_name ?? 'مستخدم محذوف'); ?></p>
                            <p><strong>الهاتف:</strong> <?php echo e($emergencyRequest->user->phone ?? 'غير متوفر'); ?></p>
                            <hr>
                            
                            <h4><i class="fas fa-file-medical-alt"></i> السجل الطبي</h4>
                            <?php if($emergencyRequest->user && $emergencyRequest->user->medicalRecord): ?>
                                <?php $record = $emergencyRequest->user->medicalRecord; ?>
                                <p><strong>فصيلة الدم:</strong> <?php echo e($record->blood_type ?? 'غير محدد'); ?></p>
                                <p><strong>حساسيات:</strong> <?php echo e($record->allergies ?? 'لا توجد'); ?></p>
                                <p><strong>أدوية حالية:</strong> <?php echo e($record->current_medications ?? 'لا توجد'); ?></p>
                            <?php else: ?>
                                <p class="text-danger">السجل الطبي غير متوفر لهذا المريض.</p>
                            <?php endif; ?>
                        </div>

                        
                        <div class="col-md-6">
                            <h4><i class="fas fa-clipboard-list"></i> تفاصيل الطوارئ</h4>
                            
                            <p><strong>نوع الطلب:</strong> <span class="badge badge-primary"><?php echo e($displayRequestType); ?></span></p>

                            <p><strong>تاريخ الإنشاء:</strong> <?php echo e($emergencyRequest->created_at->format('Y-m-d H:i')); ?></p>
                            
                            <p><strong>نوع الإصابة:</strong> <?php echo e($emergencyRequest->injuryType->injury_name ?? 'غير محدد'); ?></p>
                            
                            <p><strong>وصف المريض:</strong> <?php echo e($emergencyRequest->description ?? 'لا يوجد وصف'); ?></p>
                            
                            
<p><strong>الحالة الحالية:</strong> 
    <?php echo $__env->make('admin.emergency_requests.partials.status_badge', ['status' => $emergencyRequest->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</p>
                            <p><strong>المستشفى المسند:</strong> <?php echo e($emergencyRequest->hospital->hospital_name ?? 'لم يتم الإسناد'); ?></p>
                            
                            <hr>

                            <?php if($emergencyRequest->rejection_reason): ?> 
                                <p class="text-danger"><strong>سبب الرفض النهائي:</strong> <?php echo e($emergencyRequest->rejection_reason); ?></p>
                                <hr>
                            <?php endif; ?>
                            
                            <?php 
                                $lastHistory = $emergencyRequest->statusHistory->first(); 
                            ?>
                            <?php if($lastHistory && $lastHistory->reason && $lastHistory->reason !== 'Admin manual update'): ?>
                                <div class="alert alert-info p-2 mt-2">
                                    <strong>آخر ملاحظة إدارية:</strong> <?php echo e($lastHistory->reason); ?>

                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-5">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">التدخل الإداري وتحديث الحالة</h3>
                </div>
                <form action="<?php echo e(route('admin.emergency_requests.update', $emergencyRequest->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status">تغيير الحالة يدوياً</label>
                            <select name="status" id="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__currentLoopData = $allowedStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $translatedStatus = $statusMapping[$status] ?? ucfirst(str_replace('_', ' ', $status)); ?>
                                    <option value="<?php echo e($status); ?>" <?php echo e(old('status', $emergencyRequest->status) == $status ? 'selected' : ''); ?>>
                                        <?php echo e($translatedStatus); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="hospital_id">إعادة إسناد إلى مستشفى آخر</label>
                            <select name="hospital_id" id="hospital_id" class="form-control <?php $__errorArgs = ['hospital_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">لا تغيير</option>
                                <?php $__currentLoopData = $hospitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hospital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($hospital->id); ?>" <?php echo e(old('hospital_id', $emergencyRequest->hospital_id) == $hospital->id ? 'selected' : ''); ?>>
                                        <?php echo e($hospital->hospital_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['hospital_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="reason">سبب التعديل الإداري (يظهر في سجل التاريخ)</label>
                            <textarea name="reason" id="reason" class="form-control <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2"><?php echo e(old('reason')); ?></textarea>
                            <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning float-right">تطبيق التعديل الإداري</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="col-md-7">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history"></i> سجل تغييرات حالة الطلب</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <?php $__empty_1 = true; $__currentLoopData = $emergencyRequest->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="item">
                                <div class="product-info">
                                    <span class="product-title">
                                        
                                        <?php echo e($statusMapping[$history->status] ?? $history->status); ?>

                                        <span class="badge badge-secondary float-right"><?php echo e($history->created_at->format('Y-m-d H:i:s')); ?></span>
                                    </span>
                                    <span class="product-description">
                                        <strong>بواسطة:</strong> <?php echo e($history->changedBy->full_name ?? 'النظام/المريض'); ?>

                                        <?php if($history->reason): ?>
                                            | <strong>السبب/الملاحظات:</strong> <?php echo e($history->reason); ?>

                                        <?php endif; ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                             <p class="p-3 text-center">لا يوجد سجل تاريخ لهذا الطلب بعد.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> موقع الطوارئ</h3>
                </div>
                <div class="card-body p-0">
                    <?php if($emergencyRequest->location): ?>
                        <div class="p-2 border-bottom bg-light">
                            <small class="d-block"><strong>العنوان:</strong> <?php echo e($emergencyRequest->location->address ?? 'غير متوفر'); ?></small>
                            <small class="d-block"><strong>الإحداثيات:</strong> <?php echo e($emergencyRequest->location->latitude); ?>, <?php echo e($emergencyRequest->location->longitude); ?></small>
                        </div>
                        
                        <div style="width: 100%; height: 350px;">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0;" 
                                src="https://maps.google.com/maps?q=<?php echo e($emergencyRequest->location->latitude); ?>,<?php echo e($emergencyRequest->location->longitude); ?>&hl=ar&z=17&output=embed">
                            </iframe>
                        </div>
                        
                        <div class="p-2">
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e($emergencyRequest->location->latitude); ?>,<?php echo e($emergencyRequest->location->longitude); ?>" 
                               target="_blank" class="btn btn-primary btn-sm btn-block">
                                <i class="fas fa-external-link-alt"></i> الانتقال إلى خرائط جوجل
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="p-5 text-center">
                            <p class="text-danger font-weight-bold">بيانات الموقع غير متوفرة لهذا الطلب.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/emergency_requests/show.blade.php ENDPATH**/ ?>