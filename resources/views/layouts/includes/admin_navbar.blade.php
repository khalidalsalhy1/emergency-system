<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">الرئيسية</a>
      </li>
      {{-- زر تسجيل الخروج --}}
      <li class="nav-item d-none d-sm-inline-block">
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            تسجيل الخروج
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
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
      
      {{-- 🔔🔔🔔 NEW: جرس الإشعارات 🔔🔔🔔 --}}
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            @php
                // جلب عدد الإشعارات غير المقروءة للمدير الحالي
                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                                        ->where('is_read', 0)
                                                        ->count();
            @endphp
    
            @if($unreadCount > 0)
                <span class="badge badge-warning navbar-badge">{{ $unreadCount }}</span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
            <span class="dropdown-header">{{ $unreadCount }} إشعار جديد</span>
            <div class="dropdown-divider"></div>
            
            @php
                // جلب آخر 5 إشعارات غير مقروءة للعرض السريع
                $latestUnread = \App\Models\Notification::where('user_id', Auth::id())
                                                         ->where('is_read', 0)
                                                         ->latest()
                                                         ->take(5)
                                                         ->get();
            @endphp
    
            @forelse($latestUnread as $notification)
                {{-- الرابط يوجه إلى تحديث الإشعار كـ مقروء ثم إعادة التوجيه إلى مصدره --}}
                <a href="{{ route('admin.notifications.update', $notification->id) }}" class="dropdown-item">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ \Illuminate\Support\Str::limit($notification->title, 40) }}
                    <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
                @if(!$loop->last)
                    <div class="dropdown-divider"></div>
                @endif
            @empty
                <a href="#" class="dropdown-item text-center text-muted">لا توجد إشعارات جديدة</a>
            @endforelse
            
            <div class="dropdown-divider"></div>
            <a href="{{ route('admin.notifications.index') }}" class="dropdown-item dropdown-footer">عرض جميع الإشعارات</a>
        </div>
      </li>
      {{-- 🔔🔔🔔 END NEW: جرس الإشعارات 🔔🔔🔔 --}}

      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
