

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">تعديل الموقع الجغرافي #<?php echo e($location->id); ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline shadow">
            <form action="<?php echo e(route('admin.locations.update', $location->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-5">
                            <fieldset class="mb-3 p-3 border rounded">
                                <legend class="w-auto px-2">بيانات الموقع</legend>
                                <div class="form-group">
                                    <label>العنوان الوصفي</label>
                                    <textarea name="address" class="form-control" rows="2" required><?php echo e(old('address', $location->address)); ?></textarea>
                                </div>
                            </fieldset>

                            
                            <div class="p-3 mb-3 border rounded bg-warning text-dark shadow-sm" style="opacity: 0.9;">
                                <label class="font-weight-bold"><i class="fas fa-paste"></i> لصق سريع لتحديث الإحداثيات:</label>
                                <input type="text" id="quick_paste" class="form-control" placeholder="الصق هنا (Lat, Lng) ثم اضغط Enter" onchange="processPaste(this.value)">
                                <small class="d-block mt-1 font-italic">سيقوم بتقسيم الإحداثيات وتحديث الخريطة تلقائياً.</small>
                            </div>

                            <fieldset class="mb-3 p-3 border rounded">
                                <legend class="w-auto px-2 text-primary">الموقع الجغرافي</legend>
                                <div class="row">
                                    <div class="col-6">
                                        <label>خط العرض (Lat)</label>
                                        <input type="text" id="lat_input" name="latitude" class="form-control" value="<?php echo e($location->latitude); ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <label>خط الطول (Lng)</label>
                                        <input type="text" id="lng_input" name="longitude" class="form-control" value="<?php echo e($location->longitude); ?>" required>
                                    </div>
                                </div>
                                <button type="button" onclick="refreshLocationMap();" class="btn btn-info btn-block mt-3 shadow-sm">
                                    <i class="fas fa-sync-alt"></i> تحديث يدوي للمعاينة
                                </button>
                            </fieldset>
                        </div>

                        
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>ابحث عن موقع جديد للمساعدة:</label>
                                <div class="input-group">
                                    <input type="text" id="map_query" class="form-control" placeholder="اكتب اسم المنطقة هنا...">
                                    <div class="input-group-append">
                                        <button type="button" onclick="goToGoogleSearch();" class="btn btn-primary">
                                            <i class="fas fa-search"></i> بحث في قوقل مابس
                                        </button>
                                    </div>
                                </div>
                            </div>

                            
                            <div style="width: 100%; height: 600px; border: 3px solid #ffc107; border-radius: 8px; overflow: hidden; background: #eee;">
                                <iframe 
                                    id="location_map_frame"
                                    width="100%" 
                                    height="100%" 
                                    frameborder="0" 
                                    src="about:blank"
                                    style="border:0;">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-warning btn-lg px-5 shadow font-weight-bold">حفظ التعديلات النهائية</button>
                    <a href="<?php echo e(route('admin.locations.index')); ?>" class="btn btn-default btn-lg float-left">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // دالة معالجة اللصق السريع
    function processPaste(value) {
        if (!value) return;
        var parts = value.split(',');
        if (parts.length >= 2) {
            document.getElementById('lat_input').value = parts[0].trim();
            document.getElementById('lng_input').value = parts[1].trim();
            refreshLocationMap();
            document.getElementById('quick_paste').value = '';
        } else {
            alert('يرجى التأكد من لصق الإحداثيات بشكل صحيح (رقمين بينهما فاصلة)');
        }
    }

    // دالة تحديث الخريطة
    function refreshLocationMap() {
        var lat = document.getElementById('lat_input').value;
        var lng = document.getElementById('lng_input').value;
        var frame = document.getElementById('location_map_frame');
        
        if(lat && lng) {
            frame.src = "https://maps.google.com/maps?q=" + lat + "," + lng + "&hl=ar&z=16&output=embed";
        }
    }

    // دالة البحث الخارجي
    function goToGoogleSearch() {
        var query = document.getElementById('map_query').value;
        if(query) {
            window.open("https://www.google.com/maps/search/" + encodeURIComponent(query), '_blank');
        }
    }

    // تشغيل الخريطة فور تحميل الصفحة بالإحداثيات القديمة
    window.onload = function() {
        refreshLocationMap();
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/locations/edit.blade.php ENDPATH**/ ?>