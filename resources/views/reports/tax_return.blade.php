@extends('layouts.topmenu')
@section('title', 'Tax Return')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#336699;font-size:1.1rem;">TAX RETURN</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="background:#f3f3f3;padding:15px;border-radius:8px;margin:10px 0;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div><label style="font-weight:600;color:#666;font-size:0.75rem;display:block;">FROM</label><input type="date" id="dateFrom" style="padding:8px 12px;border:1px solid #ccc;border-radius:4px;"></div>
        <div><label style="font-weight:600;color:#666;font-size:0.75rem;display:block;">TO</label><input type="date" id="dateTo" style="padding:8px 12px;border:1px solid #ccc;border-radius:4px;"></div>
        <div><label style="font-weight:600;color:#666;font-size:0.75rem;display:block;">&nbsp;</label><button onclick="loadTax()" style="background:#33CCCC;color:#fff;border:none;padding:8px 25px;border-radius:4px;font-weight:600;cursor:pointer;">Search</button></div>
        <div><label style="font-weight:600;color:#666;font-size:0.75rem;display:block;">&nbsp;</label><button onclick="printTable()" style="background:#17a2b8;color:#fff;border:none;padding:8px 25px;border-radius:4px;font-weight:600;cursor:pointer;">Print</button></div>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow:auto;" id="printArea">
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem;min-width:800px;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:10px;">SN</th><th>Student</th><th>Item</th><th>Receipt No</th><th>Issue Date</th><th>Total</th><th>Tax</th><th>After Tax</th></tr></thead>
            <tbody id="taxBody"><tr><td colspan="8" style="text-align:center;padding:20px;">Select date range</td></tr></tbody>
            <tfoot id="taxFoot"></tfoot>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadTax(){
    var d=$('#dateFrom').val(),t=$('#dateTo').val();
    $('#taxBody').html('<tr><td colspan="8" style="text-align:center;padding:20px;">Loading...</td></tr>');
    $.get('/api/tax-return',{dts:d,dtf:t},function(r){
        var b=$('#taxBody');b.empty();var amt=0,tax=0,after=0;
        if(r.success&&r.data.length>0){
            r.data.forEach(function(x,i){
                amt+=+x.total_amount||0;tax+=+x.tax_amount||0;after+=+x.amount_after||0;
                b.append('<tr><td>'+(i+1)+'</td><td>'+x.student_name+'</td><td>'+x.item_name+'</td><td>'+x.receipt_number+'</td><td>'+x.issue_date+'</td><td>'+Number(x.total_amount||0).toLocaleString()+'</td><td>'+Number(x.tax_amount||0).toLocaleString()+'</td><td>'+Number(x.amount_after||0).toLocaleString()+'</td></tr>');
            });
        }else{b.html('<tr><td colspan="8" style="text-align:center;padding:20px;">No data</td></tr>');}
        $('#taxFoot').html('<tr style="font-weight:700;background:#f0f0f0;"><td></td><td></td><td></td><td></td><td><b>Totals</b></td><td>TSH '+Number(amt).toLocaleString()+'/=</td><td>TSH '+Number(tax).toLocaleString()+'/=</td><td>TSH '+Number(after).toLocaleString()+'/=</td></tr>');
    });
}
function printTable(){
    var w=window.open('','_blank');
    w.document.write('<!DOCTYPE html><html><head><title>Tax Return</title><style>table{width:100%;border-collapse:collapse}th{background:#1a2332;color:#fff;padding:10px}td{padding:6px;border-bottom:1px solid #eee}tfoot td{font-weight:700;background:#f0f0f0}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>');
    w.document.close();
}
</script>
@endsection
