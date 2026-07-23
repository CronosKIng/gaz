@extends('layouts.topmenu')
@section('title', 'Debtors Statement')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#336699;font-size:1.1rem;">DEBTORS STATEMENT</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="display:flex;gap:10px;align-items:flex-end;margin:10px 0;flex-wrap:wrap;">
        <select id="feeSelect" style="padding:8px;border:1px solid #ccc;border-radius:4px;"><option value="">Select Fee</option><option>Registration</option><option>Term</option><option>Transport</option><option>First term</option><option>Second term</option></select>
        <select id="yearSelect" style="padding:8px;border:1px solid #ccc;border-radius:4px;"><option value="">Year</option><option>2026</option><option>2025</option><option>2024</option></select>
        <button onclick="loadDebtors()" style="background:#D9A52A;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;">Search</button>
        <button onclick="printTable()" style="background:#17a2b8;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;">Print</button>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);" id="printArea">
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
            <thead><tr style="background:gray;color:#fff;"><th style="padding:10px;">SNO</th><th>REG</th><th>NAME</th><th>LEVEL</th><th>CLASS</th><th>YEAR</th><th>FEE NAME</th><th>AMOUNT</th><th>RECEIVED</th><th>REMAINING</th></tr></thead>
            <tbody id="debtBody"><tr><td colspan="10" style="text-align:center;padding:20px;">Select filters</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadDebtors(){var f=$('#feeSelect').val(),y=$('#yearSelect').val();if(!f||!y){alert('Select both');return;}$('#debtBody').html('<tr><td colspan="10" style="text-align:center;padding:20px;">Loading...</td></tr>');$.get('/api/debtors-statement',{fee:f,year:y},function(r){var t=$('#debtBody');t.empty();if(r.success&&r.data.length>0){var tf=0,tp=0,tb=0,s=1;r.data.forEach(function(b){var a=+b.amount||0,bal=+b.balance||0,paid=a-bal;tf+=a;tp+=paid;tb+=bal;t.append('<tr><td>'+s+'</td><td>'+b.reg+'</td><td>'+b.sname+'</td><td>'+b.level+'</td><td>'+b.class+'</td><td>'+b.year+'</td><td>'+b.category+'</td><td>'+Number(a).toLocaleString()+'</td><td>'+Number(paid).toLocaleString()+'</td><td>'+Number(bal).toLocaleString()+'</td></tr>');s++;});t.append('<tr style="background:gray;color:#fff;font-weight:700;"><td></td><td></td><td></td><td></td><td></td><td></td><td>TOTAL</td><td>'+Number(tf).toLocaleString()+'</td><td>'+Number(tp).toLocaleString()+'</td><td>'+Number(tb).toLocaleString()+'</td></tr>');}else{t.html('<tr><td colspan="10" style="text-align:center;padding:20px;">No debtors</td></tr>');}});}
function printTable(){var w=window.open('','_blank');w.document.write('<!DOCTYPE html><html><head><title>Print</title><style>table{width:100%;border-collapse:collapse;font-size:11px}th{background:gray;color:#fff;padding:8px}td{padding:5px;border:1px solid #ddd}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
</script>
@endsection
