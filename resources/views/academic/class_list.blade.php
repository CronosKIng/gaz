@extends('layouts.topmenu')
@section('title', 'Class List')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#336699;font-size:1.1rem;">NUMBER OF REGISTERED STUDENTS BY CLASS</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="display:flex;gap:10px;align-items:flex-end;margin:10px 0;">
        <select id="yearSelect" style="padding:8px 12px;border:1px solid #ccc;border-radius:4px;">
            <option value="">Select Year</option><option>2026</option><option>2025</option><option>2024</option><option>2023</option>
        </select>
        <button onclick="loadClassList()" style="background:#D9A52A;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;">Search</button>
        <button onclick="printClassList()" style="background:#17a2b8;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;">Print</button>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);" id="printArea">
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
            <thead><tr style="background:gray;color:#fff;"><th style="padding:12px;">SNO</th><th>CLASS NAME</th><th>NUMBER OF STUDENTS</th></tr></thead>
            <tbody id="classBody"><tr><td colspan="3" style="text-align:center;padding:20px;">Select year</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadClassList(){var y=$('#yearSelect').val();if(!y){alert('Select year');return;}$('#classBody').html('<tr><td colspan="3" style="text-align:center;padding:20px;">Loading...</td></tr>');$.get('/api/class-list-data',{year:y},function(r){var t=$('#classBody');t.empty();if(r.success&&r.data.length>0){var total=0,s=1;r.data.forEach(function(b){total+=+b.total;t.append('<tr><td>'+s+'</td><td>'+b.class+'</td><td>'+b.total+'</td></tr>');s++;});t.append('<tr style="background:gray;color:#fff;font-weight:700;"><td>TOTAL</td><td></td><td>'+total+'</td></tr>');}else{t.html('<tr><td colspan="3" style="text-align:center;padding:20px;">No data</td></tr>');}});}
function printClassList(){var w=window.open('','_blank');w.document.write('<!DOCTYPE html><html><head><title>Class List</title><style>table{width:100%;border-collapse:collapse}th{background:gray;color:#fff;padding:12px}td{padding:8px;border:1px solid #ddd}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
</script>
@endsection
