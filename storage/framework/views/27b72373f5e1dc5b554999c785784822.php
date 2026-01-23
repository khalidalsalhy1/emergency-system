



<?php $__env->startSection('title', 'تعديل بيانات المريض: ' . $patient->full_name); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between align-items-center" style="direction: rtl;">
        <h1><i class="fas fa-user-edit ml-2"></i> تعديل بيانات المريض: <?php echo e($patient->full_name); ?></h1>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* إجبار اللون الأصفر على التبويب النشط في AdminLTE */
    .card-warning.card-outline #patientTabs .nav-link.active {
        background-color: #ffc107 !important; /* لون Warning الأصفر */
        color: #1f2d3d !important;           /* لون النص غامق للوضوح */
        font-weight: bold;
        border: none !important;
    }

    /* تحسين شكل التبويبات غير النشطة */
    #patientTabs .nav-link {
        color: #495057;
        background-color: #f8f9fa;
        margin-left: 5px;
        border: 1px solid #dee2e6;
    }

    /* محاذاة أيقونات التبويبات لليمين */
    #patientTabs .nav-link i {
        margin-left: 5px;
        margin-right: 0px;
    }

    /* توحيد اتجاه المحتوى داخل التبويبات */
    .tab-pane {
        direction: rtl;
        text-align: right;
    }

    /* تصحيح اتجاه حقول النموذج */
    .form-group label {
        display: block;
        width: 100%;
        text-align: right;
    }
</style>

<div class="container-fluid">
    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible shadow-sm" style="direction: rtl; text-align: right;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> يرجى تصحيح الأخطاء التالية:</h5>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger shadow-sm" style="direction: rtl; text-align: right;"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card card-warning card-outline shadow">
        <form action="<?php echo e(route('admin.patients.update', $patient)); ?>" method="POST" novalidate>
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <?php
                $record = $patient->medicalRecord;
            ?>

<div class="card-header p-2">
    <ul class="nav nav-pills" id="patientTabs" role="tablist" style="direction: rtl; padding-right: 0; display: flex; flex-wrap: wrap;">
        <li class="nav-item">
            <a class="nav-link active" id="user-tab" data-toggle="tab" href="#userData" role="tab">
                <i class="fas fa-id-card ml-1"></i> البيانات الأساسية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="medical-tab" data-toggle="tab" href="#medicalData" role="tab">
                <i class="fas fa-file-medical ml-1"></i> السجل الطبي
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="diseases-tab" data-toggle="tab" href="#chronicDiseases" role="tab">
                <i class="fas fa-procedures ml-1"></i> الأمراض المزمنة
            </a>
        </li>
    </ul>
</div>

            <div class="card-body" style="direction: rtl; text-align: right;">
                <div class="tab-content" id="patientTabsContent">
                    
                    
                    <div class="tab-pane fade show active" id="userData" role="tabpanel">
                        <div class="row pt-3">
                            <div class="col-md-6 form-group">
                                <label for="full_name">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('full_name', $patient->full_name)); ?>" required>
                                <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('phone', $patient->phone)); ?>" required>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email">البريد الإلكتروني (اختياري)</label>
                                <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email', $patient->email)); ?>">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="national_id">الهوية الوطنية (اختياري)</label>
                                <input type="text" name="national_id" id="national_id" class="form-control <?php $__errorArgs = ['national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('national_id', $patient->national_id)); ?>">
                                <?php $__errorArgs = ['national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password">كلمة المرور (اتركها فارغة لعدم التغيير)</label>
                                <input type="password" name="password" id="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password_confirmation">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="status">حالة الحساب <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="active" <?php echo e(old('status', $patient->status) == 'active' ? 'selected' : ''); ?>>نشط</option>
                                    <option value="inactive" <?php echo e(old('status', $patient->status) == 'inactive' ? 'selected' : ''); ?>>غير نشط/محظور</option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="medicalData" role="tabpanel">
                        <?php
                            $birthDateValue = null;
                            if ($record && $record->birth_date) {
                                try {
                                    $birthDateValue = \Carbon\Carbon::parse($record->birth_date)->format('Y-m-d');
                                } catch (\Exception $e) {}
                            }
                            $currentGender = $record->gender ?? '';
                            $currentBloodType = $record->blood_type ?? '';
                        ?>
                        <div class="row pt-3">
                            <div class="col-md-4 form-group">
                                <label for="birth_date">تاريخ الميلاد</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('birth_date', $birthDateValue)); ?>">
                                <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="gender">الجنس</label>
                                <select name="gender" id="gender" class="form-control <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">-- اختر --</option>
                                    <option value="Male" <?php echo e(old('gender', $currentGender) == 'Male' ? 'selected' : ''); ?>>ذكر</option>
                                    <option value="Female" <?php echo e(old('gender', $currentGender) == 'Female' ? 'selected' : ''); ?>>أنثى</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="blood_type">فصيلة الدم</label>
                                <select name="blood_type" id="blood_type" class="form-control <?php $__errorArgs = ['blood_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">-- اختر --</option>
                                    <?php $__currentLoopData = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type); ?>" <?php echo e(old('blood_type', $currentBloodType) == $type ? 'selected' : ''); ?>><?php echo e($type); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="emergency_contact">رقم التواصل في حالة الطوارئ</label>
                                <input type="text" name="emergency_contact" id="emergency_contact" class="form-control <?php $__errorArgs = ['emergency_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('emergency_contact', $record->emergency_contact ?? '')); ?>">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="medical_history">تاريخ طبي سابق (عمليات، إصابات خطيرة)</label>
                                <textarea name="medical_history" id="medical_history" class="form-control" rows="3"><?php echo e(old('medical_history', $record->medical_history ?? '')); ?></textarea>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="allergies">حساسيات معروفة</label>
                                <textarea name="allergies" id="allergies" class="form-control" rows="3"><?php echo e(old('allergies', $record->allergies ?? '')); ?></textarea>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="current_medications">الأدوية الحالية</label>
                                <textarea name="current_medications" id="current_medications" class="form-control" rows="3"><?php echo e(old('current_medications', $record->current_medications ?? '')); ?></textarea>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="notes">ملاحظات إضافية</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo e(old('notes', $record->notes ?? '')); ?></textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="chronicDiseases" role="tabpanel">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <label>الأمراض المزمنة (اختياري):</label>
                                <div class="row px-2">
                                    <?php $__empty_1 = true; $__currentLoopData = $diseases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disease): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox text-right" style="padding-right: 1.5rem; padding-left: 0;">
                                                <?php
                                                    $checked = in_array($disease->id, old('diseases_ids', $patientDiseases));
                                                ?>
                                                <input class="custom-control-input" type="checkbox" name="diseases_ids[]" value="<?php echo e($disease->id); ?>" id="disease_<?php echo e($disease->id); ?>" 
                                                    <?php echo e($checked ? 'checked' : ''); ?>>
                                                <label class="custom-control-label font-weight-normal" for="disease_<?php echo e($disease->id); ?>" style="padding-right: 1.8rem; padding-left: 0; text-align: right; display: inline-block;">
                                                    <?php echo e($disease->disease_name); ?>

                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-md-12 text-muted">لا يوجد أمراض مزمنة معرفة.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <a href="<?php echo e(route('admin.patients.index')); ?>" class="btn btn-secondary mr-2">إلغاء</a>
                <button type="submit" class="btn btn-warning px-5 shadow font-weight-bold">
                    <i class="fas fa-save ml-1"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/patients/edit.blade.php ENDPATH**/ ?>