@php use Illuminate\Support\Facades\Auth; @endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    {{-- 🌟 1. تعديل رابط وشعار لوحة التحكم ليعكس دور المستشفى 🌟 --}}
    <a href="{{ route('hospital.dashboard') }}" class="brand-link">
      <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png')}}" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">إدارة المستشفى</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="صورة المستخدم">
        </div>
        <div class="info">
          {{-- عرض اسم المستخدم الحالي --}}
          <a href="#" class="d-block">{{ Auth::user()->full_name ?? Auth::user()->name ?? 'مسؤول المستشفى' }}</a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          {{-- 🚨🚨 لوحة الإحصائيات والأداء (Dashboard) 🚨🚨 --}}
          <li class="nav-item">
            <a href="{{ route('hospital.dashboard') }}" class="nav-link {{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i> {{-- تم تغيير الأيقونة لتكون عامة للداشبورد --}}
              <p>
                لوحة الإحصائيات (مستشفى)
              </p>
            </a>
          </li>
          
          {{-- **** الروابط التشغيلية للمستشفى **** --}}
          <li class="nav-header">العمليات التشغيلية</li>
          
          {{-- 🚨🚨 إدارة طلبات الطوارئ الموجهة للمستشفى 🚨🚨 --}}
          {{-- بناءً على Route: hospital.requests.index --}}
          <li class="nav-item">
            <a href="{{ route('hospital.requests.index') }}" class="nav-link {{ request()->routeIs('hospital.requests.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-bell"></i>
              <p>طلبات الطوارئ الواردة</p>
            </a>
          </li>

          {{-- سجل الإشعارات الخاصة بالمسؤول --}}
          {{-- بناءً على Route: hospital.notifications.index --}}
          <li class="nav-item">
            <a href="{{ route('hospital.notifications.index') }}" class="nav-link {{ request()->routeIs('hospital.notifications.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-envelope"></i>
              <p>سجل الإشعارات</p>
            </a>
          </li>
          
          {{-- 🔑🔑 رابط تغيير كلمة المرور 🔑🔑 --}}
          {{-- بناءً على Route: hospital.profile.change_password --}}
          <li class="nav-item">
            <a href="{{ route('hospital.profile.change_password') }}" class="nav-link {{ request()->routeIs('hospital.profile.change_password') ? 'active' : '' }}">
              <i class="nav-icon fas fa-key"></i>
              <p>تغيير كلمة المرور</p>
            </a>
          </li>
          
       
       
       
       
       
       
       
       

        </ul>
      </nav>
      </div>
    </aside>
