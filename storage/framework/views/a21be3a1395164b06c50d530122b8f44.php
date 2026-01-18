

 

<?php $__env->startSection('title', 'تفاصيل طلب الطوارئ #' . $emergencyRequest->id); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-search-location"></i> تفاصيل ومتابعة طلب الطوارئ #<?php echo e($emergencyRequest->id); ?></h1>
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
            $statusMapping = [
                'pending' => 'معلق/جديد',
                'accepted' => 'تم القبول',
                'dispatched' => 'أُرسل الفريق',
                'arrived' => 'وصل الفريق',
                'completed' => 'مكتمل',
                'canceled' => 'ملغي',
            ];
            $requestTypeMapping = [
                'DISPATCH' => 'طلب إرسال إسعاف',
                'NOTIFY' => 'إبلاغ/إشعار بحالة',
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
                            <p><strong>الاسم:</strong> <?php echo e($emergencyRequest->patient->full_name ?? 'مستخدم محذوف'); ?></p>
                            <p><strong>الهاتف:</strong> <?php echo e($emergencyRequest->patient->phone ?? 'غير متوفر'); ?></p>
                            
                            
                            <?php if($emergencyRequest->patient && $emergencyRequest->patient->diseases->isNotEmpty()): ?>
                                <p><strong>أمراض مزمنة:</strong> 
                                    <?php $__currentLoopData = $emergencyRequest->patient->diseases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disease): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge badge-danger"><?php echo e($disease->disease_name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </p>
                            <?php endif; ?>
                            <hr>
                            
                            <h4><i class="fas fa-file-medical-alt"></i> السجل الطبي</h4>
                            <?php if($emergencyRequest->patient && $emergencyRequest->patient->medicalRecord): ?>
                                <?php $record = $emergencyRequest->patient->medicalRecord; ?>
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
                            
                            <p><strong>نوع الإصابة:</strong> <?php echo e($emergencyRequest->injuryType->name ?? 'غير محدد'); ?></p>
                            
                            <p><strong>وصف المريض:</strong> <?php echo e($emergencyRequest->description ?? 'لا يوجد وصف'); ?></p>
                            
                            <p>
                                <strong>الحالة الحالية:</strong> 
                                <?php echo $__env->make('hospital_admin.emergency_requests.partials.status_badge', ['status' => $emergencyRequest->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </p>
                            
                            <hr>

                            
                            <?php if($emergencyRequest->rejection_reason): ?> 
                                <p class="text-danger"><strong>سبب الإلغاء/الرفض:</strong> <?php echo e($emergencyRequest->rejection_reason); ?></p>
                                <hr>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-5">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">تحديث حالة الطلب</h3>
                </div>
                <form action="<?php echo e(route('hospital.requests.update_status', $emergencyRequest->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        
                        <?php if($emergencyRequest->status === 'completed' || $emergencyRequest->status === 'canceled'): ?>
                            <div class="alert alert-info">هذا الطلب في حالة نهائية (<?php echo e($statusMapping[$emergencyRequest->status] ?? $emergencyRequest->status); ?>). لا يمكن تحديث حالته.</div>
                        <?php elseif(empty($allowedTransitions)): ?>
                             <div class="alert alert-warning">لا توجد حالات متاحة للتحديث من الحالة الحالية (<?php echo e($statusMapping[$emergencyRequest->status] ?? $emergencyRequest->status); ?>).</div>
                        <?php else: ?>
                            
                            <div class="form-group">
                                <label for="status">الحالة التالية</label>
                                <select name="status" id="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">-- اختر الحالة الجديدة --</option>
                                    <?php $__currentLoopData = $allowedTransitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>">
                                            <?php echo e($statusMapping[$status] ?? ucfirst(str_replace('_', ' ', $status))); ?>

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
                            
                            
                            <div class="form-group" id="reason-field" style="display: none;">
                                <label for="rejection_reason">سبب إلغاء الطلب</label>
                                <textarea name="rejection_reason" id="rejection_reason" class="form-control <?php $__errorArgs = ['rejection_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2"><?php echo e(old('rejection_reason')); ?></textarea>
                                <?php $__errorArgs = ['rejection_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    <div class="card-footer">
                        <?php if(!($emergencyRequest->status === 'completed' || $emergencyRequest->status === 'canceled') && !empty($allowedTransitions)): ?>
                            <button type="submit" class="btn btn-warning float-right">تحديث الحالة</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="col-md-12">
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
                                        <?php echo e($statusMapping[$history->status] ?? ucfirst(str_replace('_', ' ', $history->status))); ?>

                                        <span class="badge badge-secondary float-right"><?php echo e($history->created_at->format('Y-m-d H:i:s')); ?></span>
                                    </span>
                                    <span class="product-description">
                                        <strong>بواسطة:</strong> <?php echo e($history->changedBy->full_name ?? 'النظام/المريض'); ?>

                                        <?php if($history->reason): ?>
                                            | <strong>الملاحظات:</strong> <?php echo e($history->reason); ?>

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

        
        <div class="col-md-12 mt-3">
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-map-marked-alt"></i> موقع الحالة وتفاصيل العنوان</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 border-right">
                             <h5><i class="fas fa-info-circle"></i> معلومات العنوان</h5>
                             <?php if($emergencyRequest->location): ?>
                                <p class="mb-1"><strong>الإحداثيات:</strong> <?php echo e($emergencyRequest->location->latitude); ?>, <?php echo e($emergencyRequest->location->longitude); ?></p>
                                <p><strong>العنوان التوضيحي:</strong> <?php echo e($emergencyRequest->location->address ?? 'غير متوفر'); ?></p>
                                <hr>
                                
                                <div class="form-group">
                                    <label class="text-primary"><i class="fas fa-copy"></i> رابط الموقع  :</label>
                                    <input type="text" class="form-control" readonly 
                                           value="https://www.google.com/maps?q=<?php echo e($emergencyRequest->location->latitude); ?>,<?php echo e($emergencyRequest->location->longitude); ?>" 
                                           style="background-color: #f8f9fa; border: 1px solid #007bff; font-weight: bold; color: #007bff;">
                                </div>
                                <a href="https://www.google.com/maps?q=<?php echo e($emergencyRequest->location->latitude); ?>,<?php echo e($emergencyRequest->location->longitude); ?>" 
                                   target="_blank" class="btn btn-success btn-block mt-3 shadow-sm">
                                   <i class="fas fa-external-link-alt"></i> فتح في تطبيق الخرائط
                                </a>
                            <?php else: ?>
                                <p class="text-danger">بيانات الموقع غير متوفرة لهذا الطلب.</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <?php if($emergencyRequest->location): ?>
                                <div id="map-container" style="height: 350px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                                    <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                                        src="https://maps.google.com/maps?q=<?php echo e($emergencyRequest->location->latitude); ?>,<?php echo e($emergencyRequest->location->longitude); ?>&hl=ar&z=15&output=embed" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center" style="height: 350px; background-color: #f8f9fa;">
                                    <p class="text-muted">الخريطة غير متاحة.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('status');
            const reasonField = document.getElementById('reason-field');

            function toggleReasonField() {
                if (!statusSelect) return;
                // حالة الإلغاء هي 'canceled'
                if (statusSelect.value === 'canceled') {
                    reasonField.style.display = 'block';
                } else {
                    reasonField.style.display = 'none';
                }
            }

            // الاستماع للتغييرات
            if (statusSelect) {
                statusSelect.addEventListener('change', toggleReasonField);
                // تنفيذ الدالة عند تحميل الصفحة للحفاظ على حالة الـ old()
                toggleReasonField(); 
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.hospital', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/hospital_admin/emergency_requests/show.blade.php ENDPATH**/ ?>