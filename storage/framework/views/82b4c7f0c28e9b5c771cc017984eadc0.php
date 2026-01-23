<?php use Illuminate\Support\Facades\Auth; ?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand-link">
      <img src="<?php echo e(asset('assets/admin/dist/img/AdminLTELogo.png')); ?>" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">نظام الإدارة</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo e(asset('assets/admin/dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2" alt="صورة المستخدم">
        </div>
        <div class="info">
          
          <a href="#" class="d-block"><?php echo e(Auth::user()->full_name ?? Auth::user()->name ?? 'مدير النظام'); ?></a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                لوحة الإحصائيات والأداء
              </p>
            </a>
          </li>
          
          
          <li class="nav-header">إدارة النظام</li>
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.emergency_requests.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.emergency_requests.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-ambulance"></i>
              <p>طلبات الطوارئ</p>
            </a>
          </li>

          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.notifications.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.notifications.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-bell"></i>
              <p>سجل الإشعارات</p>
            </a>
          </li>
          
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.system_logs.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.system_logs.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>سجل النظام (الأمني)</p>
            </a>
          </li>

          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.request_history.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.request_history.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-history"></i>
              <p>سجل حالة الطلبات</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.hospitals.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.hospitals.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-hospital"></i>
              <p>إدارة المستشفيات</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.hospital_admins.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.hospital_admins.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>مسؤولي المستشفيات</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.patients.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.patients.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>إدارة المرضى</p>
            </a>
          </li>
          
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.locations.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.locations.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-map-marked-alt"></i>
              <p>إدارة المواقع الجغرافية</p>
            </a>
          </li>
          
          <!-- 
          <li class="nav-item">
            <a href="<?php echo e(route('admin.health_guides.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.health_guides.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-book-medical"></i>
              <p>إدارة الإرشادات الصحية</p>
            </a>
          </li> -->
          
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.diseases.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.diseases.*') ? 'active' : ''); ?>">
              
              <i class="nav-icon fas fa-heartbeat"></i> 
              <p>إدارة الأمراض المزمنة</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.injury_types.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.injury_types.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-diagnoses"></i>
              <p>أنواع الإصابات</p>
            </a>
          </li>

          
          <li class="nav-item">
            <a href="<?php echo e(route('admin.feedback.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.feedback.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-star"></i>
              <p>إدارة التقييمات</p>
            </a>
          </li>
          


        </ul>
      </nav>
      </div>
    </aside>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/layouts/includes/admin_sidebar.blade.php ENDPATH**/ ?>