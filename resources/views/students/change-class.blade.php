@extends('layouts.topmenu')
@section('title', 'Change Class')
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<style>.select2-container .select2-selection--single{height:46px!important;border:2px solid #e9ecef!important;border-radius:6px!important}</style>
@endsection
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Change Class</h2>
        <a href="/students" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="max-width:500px;margin:40px auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);text-align:center;">
        <div id="alertMessage" style="padding:10px 15px;border-radius:6px;margin-bottom:15px;display:none;"></div>
        <div style="margin-bottom:15px;"><label style="font-weight:600;color:#444;">Select Class</label><select id="classSelect" class="search" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"><option value="">Loading...</option></select></div>
        <div style="margin-bottom:15px;"><label style="font-weight:600;color:#444;">Select Year</label><select id="yearSelect" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"><option value="">Select</option><option>2026</option><option>2025</option><option>2024</option></select></div>
        <button onclick="processChange()" style="background:#D9A52A;color:#fff;border:none;padding:10px 40px;border-radius:6px;font-weight:600;cursor:pointer;width:100%;">Done</button>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
$(function(){$('.search').select2({placeholder:'Select',allowClear:true});$.get('/api/change-class/classes',function(r){var c=$('#classSelect');c.empty();c.append('<option value="">Select Class</option>');if(r.success)r.data.forEach(function(x){c.append('<option value="'+x.class+'">'+x.class+' | '+x.level+'</option>');});});});
function processChange(){var c=$('#classSelect').val(),y=$('#yearSelect').val();if(!c||!y){alert('Select both');return;}$.post('/api/change-class/process',{class:c,year:y,_token:$('meta[name="csrf-token"]').attr('content')},function(r){if(r.success){window.location.href=r.redirect||'/vtrans';}else alert(r.message);});}
</script>
@endsection
