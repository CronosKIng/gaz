@extends('layouts.topmenu')
@section('title', 'View Staff Student')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;flex-wrap:wrap;gap:10px;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Staff Students</h2>
        <div style="display:flex;gap:8px;">
            <a href="/students" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
            <input type="text" id="searchInput" placeholder="Search..." style="padding:6px 12px;border:2px solid #e9ecef;border-radius:6px;font-size:0.85rem;" onkeyup="if(event.key==='Enter')loadStaffStudents()">
            <button onclick="loadStaffStudents()" style="background:#D9A52A;color:#fff;border:none;padding:6px 16px;border-radius:6px;font-weight:600;cursor:pointer;">Search</button>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;overflow:auto;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:8px;">Reg No</th><th>Name</th><th>Staff</th><th>Class</th><th>Action</th></tr></thead>
            <tbody id="staffBody"><tr><td colspan="5" style="text-align:center;padding:20px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadStaffStudents(){$.get('/api/staff-students',{search:$('#searchInput').val()},function(r){var t=$('#staffBody');t.empty();if(r.success&&r.data.length>0){r.data.forEach(function(s){var f=s.first_status=='Paid'?'<span style="background:#dc3545;color:#fff;padding:4px 12px;border-radius:4px;font-size:0.7rem;">Paid</span>':'<button onclick="processPayment(\''+s.reg+'\',\'first\')" style="background:#fd7e14;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.7rem;cursor:pointer;">First</button>';var sec=s.second_status=='Paid'?'<span style="background:#dc3545;color:#fff;padding:4px 12px;border-radius:4px;font-size:0.7rem;">Paid</span>':'<button onclick="processPayment(\''+s.reg+'\',\'second\')" style="background:#fd7e14;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.7rem;cursor:pointer;">Second</button>';t.append('<tr><td><strong>'+s.reg+'</strong></td><td>'+s.sname+'</td><td>'+s.staff+'</td><td>'+s.class+'</td><td><button onclick="viewStudent(\''+s.reg+'\')" style="background:#003399;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.7rem;cursor:pointer;margin-right:3px;">View</button>'+f+sec+'</td></tr>');});}else{t.html('<tr><td colspan="5" style="text-align:center;padding:20px;">No staff students</td></tr>');}});}
function viewStudent(r){$.post('/api/staff-view',{reg:r,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success)window.location.href=d.redirect||'/payment';else alert(d.message);});}
function processPayment(r,t){if(!confirm('Process '+t+' payment?'))return;$.post('/api/staff-payment',{reg:r,term:t,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success){alert('Paid!');loadStaffStudents();}else alert(d.message);});}
$(function(){loadStaffStudents();});
</script>
@endsection
