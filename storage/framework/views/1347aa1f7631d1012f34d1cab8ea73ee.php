<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link">الرئيسية</a>
      </li>
      
      <li class="nav-item d-none d-sm-inline-block">
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            تسجيل الخروج
        </a>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
        </form>
      </li>
    </ul>

    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="بحث" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <ul class="navbar-nav ml-auto">
      
      
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <?php
                // جلب عدد الإشعارات غير المقروءة للمدير الحالي
                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                                        ->where('is_read', 0)
                                                        ->count();
            ?>
    
            <?php if($unreadCount > 0): ?>
                <span class="badge badge-warning navbar-badge"><?php echo e($unreadCount); ?></span>
            <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
            <span class="dropdown-header"><?php echo e($unreadCount); ?> إشعار جديد</span>
            <div class="dropdown-divider"></div>
            
            <?php
                // جلب آخر 5 إشعارات غير مقروءة للعرض السريع
                $latestUnread = \App\Models\Notification::where('user_id', Auth::id())
                                                         ->where('is_read', 0)
                                                         ->latest()
                                                         ->take(5)
                                                         ->get();
            ?>
    
            <?php $__empty_1 = true; $__currentLoopData = $latestUnread; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                
                <a href="<?php echo e(route('admin.notifications.update', $notification->id)); ?>" class="dropdown-item">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo e(\Illuminate\Support\Str::limit($notification->title, 40)); ?>

                    <span class="float-right text-muted text-sm"><?php echo e($notification->created_at->diffForHumans()); ?></span>
                </a>
                <?php if(!$loop->last): ?>
                    <div class="dropdown-divider"></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <a href="#" class="dropdown-item text-center text-muted">لا توجد إشعارات جديدة</a>
            <?php endif; ?>
            
            <div class="dropdown-divider"></div>
            <a href="<?php echo e(route('admin.notifications.index')); ?>" class="dropdown-item dropdown-footer">عرض جميع الإشعارات</a>
        </div>
      </li>
      

      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
<?php /**PATH C:\xampp\htdocs\emergency_response_system\resources\views/layouts/includes/admin_navbar.blade.php ENDPATH**/ ?>