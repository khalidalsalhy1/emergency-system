<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>لوحة التحكم | نظام الإسعاف</title>
  
  {{-- **** الأصول (Assets) - لا تغيير عليها بناءً على طلبك **** --}}
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/SansPro/SansPro.min.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/mycustomstyle.css')}}">

  @yield('css_custom') 
  
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">

  @include('layouts.includes.admin_navbar') 

  @include('layouts.includes.admin_sidebar')

  <div class="content-wrapper">
    {{-- <div class="content-header">
      <div class="container-fluid">
        @yield('page_header')
      </div>
    </div> --}}
    <div class="content">
      <div class="container-fluid">
        {{-- **** مكان المحتوى المتغير (CONTENT) **** --}}
        @yield('content') 
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
<script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/admin/dist/js/adminlte.min.js')}}"></script>

@yield('scripts') 

</body>
</html>
