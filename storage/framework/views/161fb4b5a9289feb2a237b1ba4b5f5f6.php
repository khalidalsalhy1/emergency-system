 

<?php $__env->startSection('title', 'لوحة إحصائيات المستشفى ومؤشرات الأداء'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    
                    <h1 class="m-0 text-dark">لوحة إحصائيات مستشفى <?php echo e($dashboardStats['hospital_name'] ?? 'غير معروف'); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('hospital.dashboard')); ?>">الرئيسية</a></li>
                        <li class="breadcrumb-item active">إحصائيات المستشفى</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            
            
            
            
            <h3 class="mt-4 mb-3">
                <i class="fas fa-chart-line"></i> مؤشرات الأداء الحالية والكلية للمستشفى
            </h3>
            <div class="row">
                
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            
                            <h3><?php echo e($dashboardStats['total_assigned_requests'] ?? 0); ?></h3> 
                            <p>إجمالي الطلبات المُسندة (كلي)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                        <a href="<?php echo e(route('hospital.requests.index')); ?>" class="small-box-footer">
                            عرض جميع الطلبات <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                             
                             <h3><?php echo e($dashboardStats['in_progress_requests'] ?? 0); ?></h3>
                            <p>طلبات قيد المعالجة حالياً</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        
                        <a href="<?php echo e(route('hospital.requests.index', ['filter' => 'live_tracking'])); ?>" class="small-box-footer">
                            التتبع المباشر <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo e($dashboardStats['completed_requests'] ?? 0); ?></h3>
                            <p>إجمالي الطلبات المكتملة</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        
                        <a href="<?php echo e(route('hospital.requests.index', ['filter' => 'completed'])); ?>" class="small-box-footer">
                            تحليل الإنجاز <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo e($dashboardStats['today_requests'] ?? 0); ?></h3>
                            <p>طلبات الطوارئ الواردة اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        
                        <a href="<?php echo e(route('hospital.requests.index', ['filter' => 'today'])); ?>" class="small-box-footer">
                            عرض تفاصيل اليوم <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            
            
            
            
            
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.hospital', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/hospital_admin/dashboard.blade.php ENDPATH**/ ?>