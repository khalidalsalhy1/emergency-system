

<?php $__env->startSection('title', 'تفاصيل سجل النظام: ' . $log->id); ?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">تفاصيل سجل النظام #<?php echo e($log->id); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.system_logs.index')); ?>">سجل النظام</a></li>
                        <li class="breadcrumb-item active">تفاصيل</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> معلومات السجل الأساسية</h3>
                            <div class="card-tools">
                                <span class="badge badge-primary"><?php echo e($log->action); ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <strong><i class="fas fa-calendar-alt mr-1"></i> تاريخ ووقت الحدث:</strong>
                                    <p class="text-muted"><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?> (<?php echo e($log->created_at->diffForHumans()); ?>)</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <strong><i class="fas fa-user-shield mr-1"></i> المستخدم الذي قام بالحدث:</strong>
                                    <p class="text-muted">
                                        <?php echo e($log->user->full_name ?? $log->user->name ?? 'مستخدم محذوف'); ?> (ID: <?php echo e($log->user_id); ?>)
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <strong><i class="fas fa-file-alt mr-1"></i> التفاصيل الكاملة (Details):</strong>
                            <pre class="bg-light p-3 rounded" style="white-space: pre-wrap; word-wrap: break-word;"><?php echo e($log->details); ?></pre>
                            
                            
                            <?php if(@json_decode($log->details) !== null): ?>
                                <strong><i class="fas fa-code mr-1"></i> التفاصيل المنسقة (JSON):</strong>
                                <pre class="bg-light p-3 rounded"><code class="json"><?php echo e(json_encode(json_decode($log->details, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></code></pre>
                            <?php endif; ?>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/system_logs/show.blade.php ENDPATH**/ ?>