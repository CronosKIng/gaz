@extends('layouts.topmenu')
@section('title', 'Receive Payment')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Receive Payment</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="display:flex;gap:10px;margin:15px 0;">
        <input type="text" id="searchInput" placeholder="Enter student name or registration number..." style="flex:1;padding:8px 16px;border:2px solid #e9ecef;border-radius:6px;" onkeyup="if(event.key==='Enter')searchStudents()">
        <button onclick="searchStudents()" style="background:#D9A52A;color:#fff;border:none;padding:8px 25px;border-radius:6px;font-weight:600;cursor:pointer;">Search</button>
    </div>
    <div style="background:#fff;border-radius:12px;padding:15px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
        <div id="loadingSpinner" style="text-align:center;padding:20px;display:none;">Loading...</div>
        <div id="tableContainer" style="display:none;">
            <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:10px;">#</th><th>Reg No</th><th>Name</th><th>Level</th><th>Class</th><th>Gender</th><th>Mobile</th><th>Action</th></tr></thead>
                <tbody id="studentsTableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" style="text-align:center;padding:40px;display:none;">No students found</div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function searchStudents(){
    var k=$('#searchInput').val();$('#loadingSpinner').show();$('#tableContainer,#emptyState').hide();
    $.get('/api/payment/search',{key:k},function(r){$('#loadingSpinner').hide();
        if(r.success&&r.data.length>0){renderStudents(r.data);$('#tableContainer').show();}else{$('#emptyState').show();}});
}
function renderStudents(s){var t=$('#studentsTableBody');t.empty();s.forEach(function(b,i){t.append('<tr><td>'+(i+1)+'</td><td><strong>'+b.reg+'</strong></td><td>'+b.sname+'</td><td>'+b.level+'</td><td>'+b.class+'</td><td>'+b.gender+'</td><td>'+b.mobile+'</td><td><button onclick="viewPayment(\''+b.reg+'\')" style="background:#fd7e14;color:#fff;border:none;padding:4px 14px;border-radius:4px;font-weight:600;cursor:pointer;">Payment</button></td></tr>');});}
function viewPayment(r){$.post('/api/payment/view',{reg:r,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success)window.location.href=d.redirect;else alert(d.message);});}
$(function(){searchStudents();});
</script>
@endsection
