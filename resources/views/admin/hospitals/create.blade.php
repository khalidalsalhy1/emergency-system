{{-- resources/views/admin/hospitals/create.blade.php --}}

@extends('layouts.admin') 

@section('title', 'إضافة مستشفى جديد')

@section('content_header')
    <h1><i class="fas fa-plus-square"></i> إضافة مستشفى جديد للنظام</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- عرض تنبيهات الأخطاء في حال فشل التحقق (Validation) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> يرجى تصحيح الأخطاء التالية:</h5>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-primary card-outline shadow">
        <form action="{{ route('admin.hospitals.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    {{-- القسم الأيمن: البيانات النصية --}}
                    <div class="col-md-5 border-left">
                        <h4 class="text-primary mb-3"><i class="fas fa-id-card"></i> المعلومات الأساسية</h4>
                        
                        <div class="form-group">
                            <label for="hospital_name">اسم المستشفى <span class="text-danger">*</span></label>
                            <input type="text" name="hospital_name" id="hospital_name" class="form-control" required value="{{ old('hospital_name') }}" placeholder="أدخل اسم المستشفى الكامل">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">المدينة <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control" required value="{{ old('city') }}" placeholder="مثلاً: صنعاء">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="district">المديرية <span class="text-danger">*</span></label>
                                    <input type="text" name="district" id="district" class="form-control" required value="{{ old('district') }}" placeholder="مثلاً: السبعين">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">رقم الهاتف (عام) <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" class="form-control" required value="{{ old('phone') }}" placeholder="رقم التواصل الرئيسي">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{-- تم استبدال الوصف برقم الطوارئ هنا --}}
                                    <label for="emergency_number">رقم الطوارئ <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-danger text-white"><i class="fas fa-ambulance"></i></span>
                                        </div>
                                        <input type="text" name="emergency_number" id="emergency_number" class="form-control" required value="{{ old('emergency_number') }}" placeholder="رقم العمليات">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="hospital@example.com">
                        </div>

                        <hr>
                        <h4 class="text-info mb-3"><i class="fas fa-map-marked-alt"></i> إحداثيات الموقع الجغرافي</h4>
                        
                        <div class="p-2 mb-3 border rounded bg-light shadow-sm">
                            <label class="small font-weight-bold text-muted">لصق سريع للإحداثيات (من خرائط جوجل):</label>
                            <input type="text" id="quick_paste" class="form-control form-control-sm" placeholder="مثال: 15.36, 44.19" onchange="processPaste(this.value)">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label for="lat_input">خط العرض (Lat)</label>
                                <input type="text" name="latitude" id="lat_input" class="form-control" required value="{{ old('latitude', '15.3694') }}">
                            </div>
                            <div class="col-6">
                                <label for="lng_input">خط الطول (Lng)</label>
                                <input type="text" name="longitude" id="lng_input" class="form-control" required value="{{ old('longitude', '44.1910') }}">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="address">العنوان التفصيلي</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="الشارع، الحي، أقرب معلم">
                        </div>
                    </div>

                    {{-- القسم الأيسر: البحث والمعاينة --}}
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>استخدم البحث للمساعدة في إيجاد الموقع:</label>
                            <div class="input-group">
                                <input type="text" id="map_query" class="form-control" placeholder="اكتب اسم المنطقة أو المستشفى للبحث...">
                                <div class="input-group-append">
                                    <button type="button" onclick="searchInGoogleMaps()" class="btn btn-info">
                                        <i class="fas fa-search-location"></i> بحث في الخرائط
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="map-container shadow-sm" style="width: 100%; height: 500px; border: 2px solid #007bff; border-radius: 8px; overflow: hidden;">
                            <iframe 
                                id="hospital_map_frame"
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0;" 
                                src="https://maps.google.com/maps?q=15.3694,44.1910&hl=ar&z=15&output=embed">
                            </iframe>
                        </div>
                        <button type="button" onclick="loadMapFrame()" class="btn btn-outline-primary btn-sm btn-block mt-2">
                            <i class="fas fa-sync-alt"></i> تحديث معاينة الخريطة بناءً على الإحداثيات أعلاه
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right bg-light">
                <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary mr-2">إلغاء</a>
                <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                    <i class="fas fa-save"></i> حفظ وإضافة المستشفى
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function processPaste(value) {
        if (!value) return;
        var parts = value.split(',');
        if (parts.length >= 2) {
            document.getElementById('lat_input').value = parts[0].trim();
            document.getElementById('lng_input').value = parts[1].trim();
            loadMapFrame();
            document.getElementById('quick_paste').value = '';
        }
    }

    function loadMapFrame() {
        var lat = document.getElementById('lat_input').value;
        var lng = document.getElementById('lng_input').value;
        var frame = document.getElementById('hospital_map_frame');
        if(lat && lng) {
            frame.src = "https://maps.google.com/maps?q=" + lat + "," + lng + "&hl=ar&z=16&output=embed";
        }
    }

    function searchInGoogleMaps() {
        var query = document.getElementById('map_query').value;
        if(query) {
            window.open("https://www.google.com/maps/search/" + encodeURIComponent(query), '_blank');
        }
    }

    document.getElementById('lat_input').addEventListener('change', loadMapFrame);
    document.getElementById('lng_input').addEventListener('change', loadMapFrame);
</script>
@stop
