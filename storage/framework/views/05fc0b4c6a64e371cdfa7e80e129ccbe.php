

 

<?php $__env->startSection('title', 'مراقبة طلبات الطوارئ'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><i class="fas fa-ambulance"></i> طلبات الطوارئ الواردة</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <?php if(session('warning')): ?>
                <div class="alert alert-warning"><?php echo e(session('warning')); ?></div>
            <?php endif; ?>

            
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">خيارات البحث والفلترة</h3>
                </div>
                <div class="card-body">
                    
                    <form action="<?php echo e(route('hospital.requests.index')); ?>" method="GET">
                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        
                                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                            <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                                                <?php echo e(match($status) {
                                                    'pending' => 'قيد الانتظار',
                                                    'accepted' => 'مقبولة',
                                                    'dispatched' => 'أُرسلت',
                                                    'arrived' => 'وصلت',
                                                    'completed' => 'مكتملة',
                                                    'canceled' => 'ملغاة',
                                                    default => $status,
                                                }); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mb-3"><i class="fas fa-filter"></i> فلترة</button>
                                
                                <a href="<?php echo e(route('hospital.requests.index')); ?>" class="btn btn-secondary mb-3 mr-2">إعادة تعيين</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة الطلبات (<?php echo e($requests->total()); ?> طلب)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>تاريخ الطلب</th>
                                    <th>نوع الطوارئ</th>
                                    <th>المريض</th>
                                    <th>نوع الطلب</th> 
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        // يمكن تعريف دوال مساعدة لتعيين لون الصف هنا بناءً على الحالة
                                        $rowClass = ''; 
                                        if ($request->status === 'pending') {
                                            $rowClass = 'table-warning';
                                        } elseif ($request->status === 'canceled') {
                                            $rowClass = 'table-danger text-muted';
                                        } 
                                    ?>
                                    <tr class="<?php echo e($rowClass); ?>">
                                        <td><?php echo e($request->id); ?></td>
                                        <td><?php echo e($request->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            
                                            <?php echo e($request->injuryType->name ?? 'غير محدد'); ?> 
                                        </td>
                                        <td><?php echo e($request->patient->full_name ?? 'مستخدم محذوف'); ?></td>
                                        <td>
                                            <?php if($request->request_type === 'DISPATCH'): ?>
                                                <span class="badge badge-danger">إرسال فريق</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">إشعار فقط</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            
                                            <?php echo $__env->make('hospital_admin.emergency_requests.partials.status_badge', ['status' => $request->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </td>
                                        <td>
                                            
                                            <a href="<?php echo e(route('hospital.requests.show', $request->id)); ?>" class="btn btn-xs btn-info" title="التفاصيل والتدخل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                            
                                            
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد طلبات طوارئ حالياً.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="card-footer clearfix">
                    <?php echo e($requests->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.hospital', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/hospital_admin/emergency_requests/index.blade.php ENDPATH**/ ?>