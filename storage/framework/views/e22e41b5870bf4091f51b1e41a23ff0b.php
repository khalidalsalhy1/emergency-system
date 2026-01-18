<?php use Illuminate\Support\Facades\Auth; ?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    
    <a href="<?php echo e(route('hospital.dashboard')); ?>" class="brand-link">
      <img src="<?php echo e(asset('assets/admin/dist/img/AdminLTELogo.png')); ?>" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">إدارة المستشفى</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo e(asset('assets/admin/dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2" alt="صورة المستخدم">
        </div>
        <div class="info">
          
          <a href="#" class="d-block"><?php echo e(Auth::user()->full_name ?? Auth::user()->name ?? 'مسؤول المستشفى'); ?></a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          
          <li class="nav-item">
            <a href="<?php echo e(route('hospital.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('hospital.dashboard') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i> 
              <p>
                لوحة الإحصائيات (مستشفى)
              </p>
            </a>
          </li>
          
          
          <li class="nav-header">العمليات التشغيلية</li>
          
          
          
          <li class="nav-item">
            <a href="<?php echo e(route('hospital.requests.index')); ?>" class="nav-link <?php echo e(request()->routeIs('hospital.requests.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-bell"></i>
              <p>طلبات الطوارئ الواردة</p>
            </a>
          </li>

          
          
          <li class="nav-item">
            <a href="<?php echo e(route('hospital.notifications.index')); ?>" class="nav-link <?php echo e(request()->routeIs('hospital.notifications.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-envelope"></i>
              <p>سجل الإشعارات</p>
            </a>
          </li>
          
          
          
          <li class="nav-item">
            <a href="<?php echo e(route('hospital.profile.change_password')); ?>" class="nav-link <?php echo e(request()->routeIs('hospital.profile.change_password') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-key"></i>
              <p>تغيير كلمة المرور</p>
            </a>
          </li>
          
       
       
       
       
       
       
       
       

        </ul>
      </nav>
      </div>
    </aside>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/layouts/includes/hospital_sidebar.blade.php ENDPATH**/ ?>