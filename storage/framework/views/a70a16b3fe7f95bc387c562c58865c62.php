

 

<?php $__env->startSection('title', 'تفاصيل مستشفى: ' . $hospital->hospital_name); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-hospital"></i> تفاصيل ومراجعة مستشفى: <?php echo e($hospital->hospital_name); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        
        <?php if(session('success')): ?>
            <div class="col-12 alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        
        
        <div class="col-md-7">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">بيانات المستشفى العامة</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-6 border-right">
                            <h4><i class="fas fa-info-circle text-primary"></i> معلومات التواصل</h4>
                            <p><strong>اسم المستشفى:</strong> <?php echo e($hospital->hospital_name); ?></p>
                            <p><strong>رقم الهاتف:</strong> <?php echo e($hospital->phone); ?></p>
                            <p><strong>رقم الطوارئ:</strong> <span class="text-danger font-weight-bold"><?php echo e($hospital->emergency_number ?? 'غير متوفر'); ?></span></p>
                            <p><strong>البريد الإلكتروني:</strong> <?php echo e($hospital->email ?? 'لا يوجد'); ?></p>
                            <hr>
                            
                            <h4><i class="fas fa-chart-line text-success"></i> إحصائيات سريعة</h4>
                            <p><strong>إجمالي الطلبات:</strong> <span class="badge badge-info"><?php echo e($hospital->emergency_requests_count); ?> طلب</span></p>
                            <p><strong>تاريخ الإضافة:</strong> <?php echo e($hospital->created_at->format('Y-m-d')); ?></p>
                        </div>

                        
                        <div class="col-md-6">
                            <h4><i class="fas fa-map-marked-alt text-info"></i> الموقع الإداري</h4>
                            <p><strong>المدينة:</strong> <span class="badge badge-secondary"><?php echo e($hospital->city ?? 'غير محدد'); ?></span></p>
                            <p><strong>العنوان الوصفي:</strong> <?php echo e($hospital->location->address ?? 'لا يوجد عنوان مسجل'); ?></p>
                            
                            <hr>
                            
                            <h4><i class="fas fa-align-left text-secondary"></i> الوصف/ملاحظات</h4>
                            <p class="text-muted"><?php echo e($hospital->description ?? 'لا يوجد وصف مضاف لهذا المستشفى.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('admin.hospitals.edit', $hospital->id)); ?>" class="btn btn-warning shadow-sm">
                        <i class="fas fa-edit"></i> تعديل بيانات المستشفى
                    </a>
                </div>
            </div>

            
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users-cog"></i> المسؤولين المرتبطين (Admins)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $hospital->admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($admin->full_name); ?></td>
                                    <td><?php echo e($admin->email); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($admin->status == 'active' ? 'success' : 'danger'); ?>">
                                            <?php echo e($admin->status == 'active' ? 'نشط' : 'غير نشط'); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="p-3 text-center text-muted">لا يوجد مسؤولين مرتبطين بهذا المستشفى حالياً.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-5">
            
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> الموقع الجغرافي</h3>
                </div>
                <div class="card-body p-0">
                    <?php if($hospital->location): ?>
                        <div class="p-2 border-bottom bg-light text-sm">
                            <span class="d-block"><strong>الإحداثيات:</strong> <?php echo e($hospital->location->latitude); ?>, <?php echo e($hospital->location->longitude); ?></span>
                        </div>
                        
                        
                        <div style="width: 100%; height: 400px;">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0;" 
                                src="https://maps.google.com/maps?q=<?php echo e($hospital->location->latitude); ?>,<?php echo e($hospital->location->longitude); ?>&hl=ar&z=16&output=embed">
                            </iframe>
                        </div>
                        
                        <div class="p-2">
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e($hospital->location->latitude); ?>,<?php echo e($hospital->location->longitude); ?>" 
                               target="_blank" class="btn btn-primary btn-sm btn-block shadow-sm">
                                <i class="fas fa-external-link-alt"></i> فتح الموقع في خرائط جوجل
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="p-5 text-center text-danger font-weight-bold">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p>بيانات الموقع الجغرافي (الإحداثيات) غير متوفرة.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> إجراءات إضافية</h3>
                </div>
                <div class="card-body">
                    <a href="<?php echo e(route('admin.hospitals.index')); ?>" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> العودة لقائمة المستشفيات
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/hospitals/show.blade.php ENDPATH**/ ?>