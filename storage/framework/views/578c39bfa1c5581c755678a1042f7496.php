

 

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

            
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">خيارات البحث والفلترة</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.emergency_requests.index')); ?>" method="GET">
                        <div class="row">
                            
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        <?php $__currentLoopData = $allowedStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                                                <?php echo e(match($status) {
                                                    'pending' => 'قيد الانتظار',
                                                    'in_progress' => 'قيد المعالجة',
                                                    'completed' => 'مكتملة',
                                                    'canceled' => 'ملغاة',
                                                    default => $status,
                                                }); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hospital_id">المستشفى المسند إليه</label>
                                    <select name="hospital_id" id="hospital_id" class="form-control">
                                        <option value="">جميع المستشفيات</option>
                                        <?php $__currentLoopData = $hospitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hospital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($hospital->id); ?>" <?php echo e(request('hospital_id') == $hospital->id ? 'selected' : ''); ?>>
                                                <?php echo e($hospital->hospital_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_search">البحث باسم أو هاتف المستخدم</label>
                                    <input type="text" 
                                           name="user_search" 
                                           id="user_search" 
                                           class="form-control" 
                                           value="<?php echo e(request('user_search')); ?>" 
                                           placeholder="اسم المريض أو رقم الهاتف">
                                </div>
                            </div>
                            

                            
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mb-3"><i class="fas fa-filter"></i> فلترة/بحث</button>
                                <a href="<?php echo e(route('admin.emergency_requests.index')); ?>" class="btn btn-secondary mb-3 mr-2">إعادة تعيين</a>
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
                                    <th>المستشفى المسند</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="<?php echo e($request->status == 'pending' ? 'table-danger' : ''); ?>">
                                        <td><?php echo e($request->id); ?></td>
                                        <td><?php echo e($request->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            
                                            <?php echo e($request->injuryType->injury_name ?? 'غير محدد'); ?>

                                        </td>
                                        <td><?php echo e($request->user->full_name ?? 'مستخدم محذوف'); ?></td>
                                        <td>
                                            <?php if($request->hospital): ?>
                                                <?php echo e($request->hospital->hospital_name); ?>

                                            <?php else: ?>
                                                <span class="badge badge-warning">لم يتم الإسناد بعد</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            
                                            <?php echo $__env->make('admin.emergency_requests.partials.status_badge', ['status' => $request->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.emergency_requests.show', $request->id)); ?>" class="btn btn-xs btn-info" title="التفاصيل والتدخل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                            
                                            
                                            <button type="button" class="btn btn-xs btn-danger delete-btn" data-id="<?php echo e($request->id); ?>" title="حذف دائم">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                            
                                            <form id="delete-form-<?php echo e($request->id); ?>" 
                                                  action="<?php echo e(route('admin.emergency_requests.destroy', $request->id)); ?>" 
                                                  method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>

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



<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script>
        // هنا يمكنك إضافة كود JavaScript الخاص بتأكيد الحذف باستخدام SweetAlert2
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        text: "لن تتمكن من التراجع عن حذف طلب الطوارئ هذا!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'نعم، قم بالحذف!',
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + requestId).submit();
                        }
                    });
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/emergency_requests/index.blade.php ENDPATH**/ ?>