

<?php $__env->startSection('title', 'لوحة الإحصائيات ومؤشرات الأداء'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">لوحة الإحصائيات والأداء (مؤشرات رئيسية)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الإحصائيات</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            
            
            
            
            <h3 class="mt-4 mb-3">
                <i class="fas fa-chart-line"></i> مؤشرات الأداء اليومية (<?php echo e(now()->format('Y-m-d')); ?>)
            </h3>
            <div class="row">
                
                
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo e($totalRequestsToday); ?></h3>
                            <p>إجمالي طلبات الطوارئ اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        
                        <a href="<?php echo e(route('admin.emergency_requests.index', ['date' => now()->format('Y-m-d')])); ?>" class="small-box-footer">
                            عرض التفاصيل <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo e($inProgressRequestsToday); ?></h3>
                            <p>طلبات قيد المعالجة اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        
                        <a href="<?php echo e(route('admin.emergency_requests.index', ['status' => 'in_progress', 'date' => now()->format('Y-m-d')])); ?>" class="small-box-footer">
                            التتبع المباشر <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo e($pendingRequestsToday); ?></h3>
                            <p>بانتظار الإسناد اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        
                        <a href="<?php echo e(route('admin.emergency_requests.index', ['status' => 'pending', 'date' => now()->format('Y-m-d')])); ?>" class="small-box-footer">
                            مراجعة فورية <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            
            
            
            
            <h3 class="mt-4 mb-3">
                 <i class="fas fa-calendar-alt"></i> تحليل الأداء الشهري والإجمالي
            </h3>
            <div class="row">
                
                
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo e($totalCompletedRequests); ?></h3> 
                            <p>إجمالي الطلبات المكتملة (كلي)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        
                        <a href="<?php echo e(route('admin.emergency_requests.index', ['status' => 'completed'])); ?>" class="small-box-footer">
                            تحليل الإنجاز <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?php echo e($topInjuryMonthly->count ?? 0); ?></h3>
                            <p>أكثر إصابة شيوعاً (شهرياً): **<?php echo e($topInjuryMonthly->name ?? 'غير متوفر'); ?>**</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-briefcase-medical"></i>
                        </div>
                        
                        <a href="<?php echo e(route('admin.emergency_requests.index', ['injury_name' => $topInjuryMonthly->name ?? ''])); ?>" class="small-box-footer">
                            عرض التفاصيل <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3><?php echo e($mostRejectingHospital->count ?? 0); ?></h3>
                            <p>أكثر مستشفى رفضاً (شهرياً): **<?php echo e($mostRejectingHospital->name ?? 'غير متوفر'); ?>**</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hospital-alt"></i>
                        </div>
                        
                        <a href="<?php echo e(route('admin.emergency_requests.index', ['hospital_name' => $mostRejectingHospital->name ?? '', 'status' => 'canceled'])); ?>" class="small-box-footer">
                            مراجعة حالات الرفض <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            
            
            
            
            
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tachometer-alt"></i> تحليل متوسط زمن إكمال الطلبات حسب المستشفى (شهرياً)
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 45%;">المستشفى</th>
                                        <th style="width: 30%;">متوسط زمن الإكمال (س:د:ث)</th>
                                        <th style="width: 20%;">متوسط الثواني</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($lowestPerformingHospital): ?>
                                    <tr>
                                        <td colspan="4">
                                            <p class="text-danger mb-1 font-weight-bold">
                                                <i class="fas fa-exclamation-circle"></i> ملاحظة: المستشفى الأقل أداءً (أطول زمن إكمال) هو: 
                                                <span class="text-bold"><?php echo e($lowestPerformingHospital->hospital_name); ?></span> 
                                                بمتوسط زمن قدره: <span class="badge badge-danger"><?php echo e($lowestPerformingHospital->avg_completion_time); ?></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php $__empty_1 = true; $__currentLoopData = $hospitalPerformanceMonthly; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $performance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.emergency_requests.index', ['hospital_name' => $performance->hospital_name, 'status' => 'completed'])); ?>">
                                                <?php echo e($performance->hospital_name); ?>

                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-bold"><?php echo e($performance->avg_completion_time); ?></span>
                                        </td>
                                        <td>
                                            <?php echo e(round($performance->avg_seconds)); ?> ثانية
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">لا توجد طلبات مكتملة خلال الشهر الجاري لتحليل الأداء.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            

        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/stats/index.blade.php ENDPATH**/ ?>