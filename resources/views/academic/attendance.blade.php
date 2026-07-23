@extends('layouts.topmenu')
@section('title', 'Student Attendance')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Today Class Attendance</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="display:flex;gap:10px;align-items:flex-end;margin:10px 0;flex-wrap:wrap;">
        <input type="date" id="dateInput" value="{{ date('Y-m-d') }}" style="padding:8px 12px;border:1px solid #ccc;border-radius:4px;">
        <button onclick="loadAttendance()" style="background:#D9A52A;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;">Search</button>
        <button onclick="printAttendance()" style="background:#17a2b8;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;">Print</button>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);" id="printArea">
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
            <thead><tr style="background:gray;color:#fff;"><th style="padding:10px;">SNO</th><th>CLASS NAME</th><th>STUDENTS</th><th>PRESENT</th><th>ABSENT</th><th>REASON</th></tr></thead>
            <tbody id="attendanceBody"><tr><td colspan="6" style="text-align:center;padding:20px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadAttendance(){
    var d=$('#dateInput').val()||'{{ date('Y-m-d') }}';
    $('#attendanceBody').html('<tr><td colspan="6" style="text-align:center;padding:20px;">Loading...</td></tr>');
    $.get('/api/attendance-list',{date:d},function(r){
        var t=$('#attendanceBody');t.empty();
        if(r.success&&r.data.length>0){
            var s=1,ts=0,tp=0,ta=0,tr=0;
            r.data.forEach(function(b){ts+=+b.total;tp+=+b.present;ta+=+b.absent;tr+=+b.reason;
                t.append('<tr><td>'+(s++)+'</td><td>'+b.class+'</td><td>'+b.total+'</td><td>'+b.present+'</td><td>'+b.absent+'</td><td>'+b.reason+'</td></tr>');});
            t.append('<tr style="background:gray;color:#fff;font-weight:700;"><td>TOTAL</td><td></td><td>'+ts+'</td><td>'+tp+'</td><td>'+ta+'</td><td>'+tr+'</td></tr>');
        }else{t.html('<tr><td colspan="6" style="text-align:center;padding:20px;">No data</td></tr>');}
    });
}
function printAttendance(){var w=window.open('','_blank','width=900,height=600');w.document.write('<!DOCTYPE html><html><head><title>Attendance</title><style>table{width:100%;border-collapse:collapse;font-size:12px}th{background:gray;color:#fff;padding:10px}td{padding:8px;border:1px solid #ddd}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
$(function(){loadAttendance();});
</script>
@endsection
