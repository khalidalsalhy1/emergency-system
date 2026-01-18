<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>لوحة التحكم | نظام الإسعاف</title>
  
  
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/plugins/fontawesome-free/css/all.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/dist/css/adminlte.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/fonts/SansPro/SansPro.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/mycustomstyle.css')); ?>">

  <?php echo $__env->yieldContent('css_custom'); ?> 
  
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">

  <?php echo $__env->make('layouts.includes.admin_navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

  <?php echo $__env->make('layouts.includes.admin_sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="content-wrapper">
    
    <div class="content">
      <div class="container-fluid">
        
        <?php echo $__env->yieldContent('content'); ?> 
      </div></div>
    </div>
  <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>الاعدادات</h5>
    </div>
  </aside>
  <footer class="main-footer">

  </footer>
</div>
<script src="<?php echo e(asset('assets/admin/plugins/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/admin/dist/js/adminlte.min.js')); ?>"></script>

<?php echo $__env->yieldContent('scripts'); ?> 

</body>
</html>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/layouts/admin.blade.php ENDPATH**/ ?>