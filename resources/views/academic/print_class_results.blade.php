@extends('layouts.topmenu')
@section('title', 'Print Class Results')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Print Class Results</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="background:#DAE0E5;border-radius:8px;padding:15px;margin:10px 0;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <select id="classSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Select Class</option></select>
        <select id="examSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Select Exam</option><option>First middle term</option><option>First term</option><option>Second middle term</option><option>Annual examination</option></select>
        <select id="yearSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Year</option><option>2026</option><option>2025</option><option>2024</option></select>
        <button onclick="loadResults()" style="background:#FF9900;color:#fff;border:none;padding:10px 20px;border-radius:4px;font-weight:600;cursor:pointer;">View Results</button>
        <button onclick="printResults()" style="background:#17a2b8;color:#fff;border:none;padding:8px 15px;border-radius:4px;font-weight:600;cursor:pointer;">Print</button>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);" id="printArea">
        <table style="width:100%;border-collapse:collapse;font-size:0.75rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:8px;">SNO</th><th>REG NO</th><th>NAME</th><th>SUBJECT</th><th>MARKS</th><th>GRADE</th><th>POINT</th><th>POSITION</th></tr></thead>
            <tbody id="resultBody"><tr><td colspan="8" style="text-align:center;padding:20px;">Select filters</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(function(){$.get('/api/class-list-dropdown',function(r){if(r.success)r.data.forEach(function(c){$('#classSelect').append('<option value="'+c.class+'">'+c.class+' | '+c.level+'</option>');});});});
function loadResults(){var c=$('#classSelect').val(),e=$('#examSelect').val(),y=$('#yearSelect').val();if(!c||!e||!y){alert('Select all');return;}$('#resultBody').html('<tr><td colspan="8" style="text-align:center;padding:20px;">Loading...</td></tr>');$.get('/api/class-results',{class:c,examtp:e,acyear:y},function(r){var t=$('#resultBody');t.empty();if(r.success&&r.data.length>0){r.data.forEach(function(b,i){t.append('<tr><td>'+(i+1)+'</td><td>'+b.reg+'</td><td>'+b.sname+'</td><td>'+b.subject+'</td><td>'+b.marks+'</td><td>'+b.grade+'</td><td>'+b.point+'</td><td>'+b.position+'</td></tr>');});}else{t.html('<tr><td colspan="8" style="text-align:center;padding:20px;">No results</td></tr>');}});}
function printResults(){var w=window.open('','_blank');w.document.write('<!DOCTYPE html><html><head><title>Results</title><style>table{width:100%;border-collapse:collapse;font-size:12px}th{background:#000;color:#fff;padding:8px}td{padding:6px;border:1px solid #ddd}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
</script>
@endsection
