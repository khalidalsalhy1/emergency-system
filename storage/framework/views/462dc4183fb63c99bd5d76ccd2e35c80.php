

 

<?php $__env->startSection('title', 'تعديل مستشفى: ' . $hospital->hospital_name); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-edit"></i> تعديل بيانات المستشفى</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> يرجى تصحيح الأخطاء التالية:</h5>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div class="card card-warning shadow  ">
        <div class="card-header py-3 d-flex justify-content-between align-items-center ">
            <h3 class="card-title font-weight-bold text-dark m-0" >
                تعديل البيانات ل: <?php echo e($hospital->hospital_name); ?>

            </h3>
        </div>

        <form action="<?php echo e(route('admin.hospitals.update', $hospital->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-md-5 border-left">
                        <h4 class="text-dark mb-3"><i class="fas fa-id-card"></i> المعلومات الأساسية</h4>
                        
                        <div class="form-group">
                            <label for="hospital_name">اسم المستشفى <span class="text-danger">*</span></label>
                            <input type="text" name="hospital_name" id="hospital_name" class="form-control" required value="<?php echo e(old('hospital_name', $hospital->hospital_name)); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">المدينة <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control" required value="<?php echo e(old('city', $hospital->city)); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="district">المديرية <span class="text-danger">*</span></label>
                                    <input type="text" name="district" id="district" class="form-control" required value="<?php echo e(old('district', $hospital->district)); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">رقم الهاتف (عام) <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" class="form-control" required value="<?php echo e(old('phone', $hospital->phone)); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_number">رقم الطوارئ <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-danger text-white"><i class="fas fa-ambulance"></i></span>
                                        </div>
                                        <input type="text" name="emergency_number" id="emergency_number" class="form-control" required value="<?php echo e(old('emergency_number', $hospital->emergency_number)); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email', $hospital->email)); ?>">
                        </div>

                        <hr>
                        <h4 class="text-dark mb-3"><i class="fas fa-map-marked-alt"></i> إحداثيات الموقع الجغرافي</h4>
                        
                        <div class="p-2 mb-3 border rounded bg-light shadow-sm">
                            <label class="small font-weight-bold text-muted">تحديث سريع للإحداثيات (لصق من الخرائط):</label>
                            <input type="text" id="quick_paste" class="form-control form-control-sm border-warning" placeholder="مثال: 15.36, 44.19" onchange="processPaste(this.value)">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label for="lat_input">خط العرض (Lat)</label>
                                <input type="text" name="latitude" id="lat_input" class="form-control border-warning" required value="<?php echo e(old('latitude', optional($hospital->location)->latitude)); ?>">
                            </div>
                            <div class="col-6">
                                <label for="lng_input">خط الطول (Lng)</label>
                                <input type="text" name="longitude" id="lng_input" class="form-control border-warning" required value="<?php echo e(old('longitude', optional($hospital->location)->longitude)); ?>">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="address">العنوان التفصيلي</label>
                            <input type="text" name="address" id="address" class="form-control" value="<?php echo e(old('address', optional($hospital->location)->address)); ?>">
                        </div>
                    </div>

                    
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>موقع المستشفى الحالي على الخريطة:</label>
                        </div>

                        <div class="map-container shadow-sm" style="width: 100%; height: 500px; border: 3px solid #ffc107; border-radius: 8px; overflow: hidden;">
                            <iframe 
                                id="hospital_map_frame"
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0;" 
                                src="https://maps.google.com/maps?q=<?php echo e(optional($hospital->location)->latitude); ?>,<?php echo e(optional($hospital->location)->longitude); ?>&hl=ar&z=16&output=embed">
                            </iframe>
                        </div>
                        <button type="button" onclick="loadMapFrame()" class="btn btn-outline-warning btn-sm btn-block mt-2 font-weight-bold">
                            <i class="fas fa-sync-alt"></i> إعادة تحميل الخريطة بناءً على الإحداثيات
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light">
                
                <button type="submit" class="btn btn-warning px-5 shadow font-weight-bold text-dark">
                    <i class="fas fa-save mr-1"></i> حفظ التعديلات
                </button>
                <a href="<?php echo e(route('admin.hospitals.index')); ?>" class="btn btn-default float-left font-weight-bold">إلغاء</a>
            </div>
        </form>
    </div>
</div>

<script>
    // تنفيذ تحميل الخريطة عند فتح الصفحة مباشرة
    window.onload = function() {
        loadMapFrame();
    };

    function processPaste(value) {
        if (!value) return;
        var parts = value.split(',');
        if (parts.length >= 2) {
            document.getElementById('lat_input').value = parts[0].trim();
            document.getElementById('lng_input').value = parts[1].trim();
            loadMapFrame();
            document.getElementById('quick_paste').value = '';
        }
    }

    function loadMapFrame() {
        var lat = document.getElementById('lat_input').value;
        var lng = document.getElementById('lng_input').value;
        var frame = document.getElementById('hospital_map_frame');
        if(lat && lng) {
            frame.src = "https://maps.google.com/maps?q=" + lat + "," + lng + "&hl=ar&z=16&output=embed";
        }
    }

    document.getElementById('lat_input').addEventListener('change', loadMapFrame);
    document.getElementById('lng_input').addEventListener('change', loadMapFrame);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/hospitals/edit.blade.php ENDPATH**/ ?>