<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>تسجيل الدخول | نظام الإسعاف والطوارئ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  
  <link href="https://fonts.googleapis.com/css2?family=Harmattan:wght@400;700&display=swap" rel="stylesheet">
  
  
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/plugins/fontawesome-free/css/all.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/fonts/ionicons/2.0.1/css/ionicons.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/admin/dist/css/adminlte.min.css')); ?>">

  
  <style>
    /* تطبيق خط Harmattan على كامل الصفحة */
    body {
        font-family: 'Harmattan', sans-serif !important;
    }
    
    /* 1. خلفية متدرجة: الرمادي الفضي / الأزرق الفاتح (الخيار 3) */
    .login-page {
        /* تدرج هادئ وجميل بألوان فاتحة */
        background: linear-gradient(135deg, #b0c4de 0%, #e0ffff 100%); 
        min-height: 100vh; 
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* 2. تعديل صندوق تسجيل الدخول (Login Box) */
    .login-box, .register-box {
        background: rgba(255, 255, 255, 0.98); 
        border-radius: 12px; /* حواف منحنية */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); /* ظل أهدأ يناسب الخلفية الفاتحة */
        padding: 10px;
        max-width: 400px; 
        width: 90%;
    }
    /* 3. تعديل العنوان ليتناسب مع الخلفية الفاتحة (لون أزرق داكن/أخضر) */
    .login-logo a {
        color: #004d40 !important; 
        font-size: 2.2rem;
        text-shadow: none; 
    }
    /* 4. تعديل مظهر زر الدخول */
    .btn-primary {
        background-color: #007bff !important; 
        border-color: #007bff !important;
        font-weight: 700; /* تحديد وزن الخط لـ Harmattan */
    }
  </style>

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:void(0)"><b>نظام</b> الإسعاف والطوارئ</a>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">تسجيل الدخول لإدارة النظام</p>
      
      
      <?php if($errors->any()): ?>
          <div class="alert alert-danger" role="alert">
              <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php echo e($error); ?><br>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
      <?php endif; ?>
      
      
      <form method="POST" action="<?php echo e(route('admin.login.post')); ?>">
        <?php echo csrf_field(); ?> 
        
        <div class="input-group mb-3">
          <input type="text" name="phone" class="form-control" placeholder="رقم الهاتف" value="<?php echo e(old('phone')); ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span> 
            </div>
          </div>
        </div>
        
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="كلمة المرور">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">تسجيل الدخول</button>
          </div>
        </div>
      </form>

      </div>
    </div>
</div>
<script src="<?php echo e(asset('assets/admin/plugins/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/admin/dist/js/adminlte.min.js')); ?>"></script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>