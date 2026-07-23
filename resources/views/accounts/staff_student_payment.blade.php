@extends('layouts.topmenu')
@section('title', 'Staff Student Payment')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Staff Student Payment</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="display:flex;gap:10px;margin:12px 0;">
        <input type="text" id="searchInput" placeholder="Search student..." style="flex:1;padding:10px 15px;border:2px solid #e9ecef;border-radius:6px;" onkeyup="if(event.key==='Enter')loadStudents()">
        <button onclick="loadStudents()" style="background:#D9A52A;color:#fff;border:none;padding:10px 25px;border-radius:6px;font-weight:600;cursor:pointer;">Search</button>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:10px;">REG NO</th><th>NAME</th><th>STAFF</th><th>CLASS</th><th>ACTION</th></tr></thead>
            <tbody id="studentsBody"><tr><td colspan="5" style="text-align:center;padding:30px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadStudents(){$.get('/api/staff-students',{search:$('#searchInput').val()},function(r){var t=$('#studentsBody');t.empty();if(r.success&&r.data.length>0){r.data.forEach(function(s){var fl=s.first_status||'First',fc=s.first_color||'orange',fd=s.first_disabled||'',sl=s.second_status||'Second',sc=s.second_color||'orange',sd=s.second_disabled||'';t.append('<tr><td><input value="'+s.reg+'" readonly style="border:none;background:transparent;width:100%;"></td><td><input value="'+s.sname+'" readonly style="border:none;background:transparent;width:100%;"></td><td><input value="'+s.staff+'" readonly style="border:none;background:transparent;width:100%;"></td><td><input value="'+s.class+'" readonly style="border:none;background:transparent;width:100%;"></td><td><button onclick="viewStudent(\''+s.reg+'\')" style="background:#003399;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.7rem;cursor:pointer;margin-right:3px;">View</button><button onclick="payTerm(\''+s.reg+'\',\'first\')" style="background:'+fc+';color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.7rem;cursor:pointer;margin-right:3px;" '+fd+'>'+fl+'</button><button onclick="payTerm(\''+s.reg+'\',\'second\')" style="background:'+sc+';color:#fff;border:none;padding:4px 10px;border-radius:4px;font-size:0.7rem;cursor:pointer;" '+sd+'>'+sl+'</button></td></tr>');});}else{t.html('<tr><td colspan="5" style="text-align:center;padding:30px;">No staff students found</td></tr>');}});}
function viewStudent(r){$.post('/api/staff-students-view',{reg:r,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success)window.location.href=d.redirect;});}
function payTerm(r,t){if(!confirm('Process '+t+' term payment?'))return;$.post('/api/staff-students-pay',{reg:r,term:t,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success){alert(d.message);loadStudents();}else alert(d.message);});}
$(function(){loadStudents();});
</script>
@endsection
