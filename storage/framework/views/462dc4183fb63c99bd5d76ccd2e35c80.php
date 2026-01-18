 

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                
                <h1 class="m-0 text-dark">تعديل المستشفى: <?php echo e($hospital->hospital_name); ?></h1>
            </div><div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.hospitals.index')); ?>">المستشفيات</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </div></div></div></div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning"> 
                    <div class="card-header">
                        <h3 class="card-title">تعديل بيانات المستشفى والموقع</h3>
                    </div>
                    
                    
                    <form action="<?php echo e(route('admin.hospitals.update', $hospital->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?> 
                        
                        <div class="card-body">
                            
                            
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            
                            <fieldset class="mb-4 p-3 border">
                                <legend class="w-auto px-2">معلومات المستشفى الأساسية</legend>
                                <div class="form-group">
                                    <label for="hospital_name">اسم المستشفى</label>
                                    <input type="text" name="hospital_name" class="form-control" id="hospital_name" 
                                           value="<?php echo e(old('hospital_name', $hospital->hospital_name)); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">رقم الهاتف الأساسي</label>
                                    <input type="text" name="phone" class="form-control" id="phone" 
                                           value="<?php echo e(old('phone', $hospital->phone)); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="emergency_number">رقم الطوارئ</label>
                                    <input type="text" name="emergency_number" class="form-control" id="emergency_number" 
                                           value="<?php echo e(old('emergency_number', $hospital->emergency_number)); ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="city">المدينة</label>
                                        <input type="text" name="city" class="form-control" id="city" 
                                               value="<?php echo e(old('city', $hospital->city)); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="district">المنطقة</label>
                                        <input type="text" name="district" class="form-control" id="district" 
                                               value="<?php echo e(old('district', $hospital->district)); ?>">
                                    </div>
                                </div>
                            </fieldset>

                            
                            <fieldset class="mb-4 p-3 border">
                                <legend class="w-auto px-2">بيانات الموقع الجغرافي</legend>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="latitude">خط العرض (Latitude)</label>
                                        <input type="text" name="latitude" class="form-control" id="latitude" 
                                               
                                               value="<?php echo e(old('latitude', optional($hospital->location)->latitude)); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="longitude">خط الطول (Longitude)</label>
                                        <input type="text" name="longitude" class="form-control" id="longitude" 
                                               
                                               value="<?php echo e(old('longitude', optional($hospital->location)->longitude)); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">العنوان التفصيلي</label>
                                    <textarea name="address" class="form-control" id="address"><?php echo e(old('address', optional($hospital->location)->address)); ?></textarea>
                                </div>
                            </fieldset>
                            
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-edit"></i> تحديث البيانات</button>
                            <a href="<?php echo e(route('admin.hospitals.index')); ?>" class="btn btn-default float-left">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/hospitals/edit.blade.php ENDPATH**/ ?>