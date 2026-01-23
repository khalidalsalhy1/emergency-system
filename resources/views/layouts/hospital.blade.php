<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>لوحة تحكم مسؤول المستشفى | نظام الإسعاف</title>
  
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

  @include('layouts.includes.hospital_navbar') 
  @include('layouts.includes.hospital_sidebar')

  <div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            @yield('content_header')
        </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        @yield('content') 
      </div>
    </div>
  </div>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>

  <footer class="main-footer">
    <!-- <strong>جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام الإسعاف الطارئ</strong> -->
  </footer>
</div>

<script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        function checkNewEmergencies() {
            $.ajax({
                url: "/emergency_response_system/hospital/check-new-emergencies",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    if (data && data.has_new === true) {
                        let lastId = localStorage.getItem('last_alerted_emergency_id');
                        
                        // التنبيه فقط إذا كان الطلب جديداً برقم ID مختلف
                        if (lastId != data.latest_id) {
                            localStorage.setItem('last_alerted_emergency_id', data.latest_id);
                            
                            var audio = new Audio('https://assets.mixkit.co/active_storage/sfx/995/995-preview.mp3');
                            audio.play().catch(e => console.log("الصوت محجوب"));

                            Swal.fire({
                                title: '🚨 بلاغ طوارئ جديد!',
                                text: 'استغاثة جديدة برقم #' + data.latest_id,
                                icon: 'error',
                                confirmButtonText: 'فتح الطلب',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/emergency_response_system/hospital/requests";
                                }
                            });
                        }
                    }
                }
            });
        }
        setInterval(checkNewEmergencies, 15000);
        checkNewEmergencies();
    });
</script>

@yield('scripts') 
</body>
</html>
