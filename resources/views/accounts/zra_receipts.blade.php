@extends('layouts.topmenu')
@section('title', 'ZRA Receipts')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">ZRA Receipts</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="display:flex;gap:10px;align-items:flex-end;margin:10px 0;flex-wrap:wrap;background:#f3f3f3;padding:15px;border-radius:8px;">
        <div><label style="font-weight:600;color:#666;font-size:0.75rem;">FROM</label><input type="date" id="dateFrom" style="padding:10px;border:1px solid #ccc;border-radius:4px;"></div>
        <div><label style="font-weight:600;color:#666;font-size:0.75rem;">TO</label><input type="date" id="dateTo" style="padding:10px;border:1px solid #ccc;border-radius:4px;"></div>
        <div><label>&nbsp;</label><button onclick="loadReceipts()" style="background:#33CCCC;color:#fff;border:none;padding:10px 30px;border-radius:4px;font-weight:600;cursor:pointer;">Search</button></div>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:10px;">RECEIPT NO</th><th>SPONSOR</th><th>TOTAL PAID</th><th>DATE</th><th>ZRA STATUS</th><th>ACTION</th></tr></thead>
            <tbody id="receiptBody"><tr><td colspan="6" style="text-align:center;padding:20px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadReceipts(){
    $.get('/api/zra-receipts-list',{dts:$('#dateFrom').val(),dtf:$('#dateTo').val()},function(r){
        var t=$('#receiptBody');t.empty();
        if(r.success&&r.data.length>0){r.data.forEach(function(b){
            t.append('<tr><td><strong>'+b.invoid+'</strong></td><td>'+b.name+'</td><td>'+Number(b.cost||0).toLocaleString()+'</td><td>'+b.date+'</td><td>'+(b.zra_status=='Paid'?'<span style="background:#d4edda;color:#155724;padding:2px 8px;border-radius:10px;font-size:0.65rem;">Paid</span>':'<span style="background:#fff3cd;color:#856404;padding:2px 8px;border-radius:10px;font-size:0.65rem;">Not Paid</span>')+'</td><td><button onclick="viewReceipt(\''+b.invoid+'\')" style="background:#003399;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.65rem;cursor:pointer;margin-right:3px;">View</button>'+(b.zra_status!='Paid'?'<button onclick="payZRA(\''+b.invoid+'\')" style="background:#008080;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.65rem;cursor:pointer;">Pay ZRA</button>':'')+' <button onclick="printZRA(\''+b.invoid+'\',\''+b.name+'\',\''+b.cost+'\',\''+b.date+'\',\''+b.zra_status+'\',\''+b.reg+'\')" style="background:#17a2b8;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.65rem;cursor:pointer;">Print</button></td></tr>');
        });}else{t.html('<tr><td colspan="6" style="text-align:center;padding:20px;">No receipts</td></tr>');}
    });
}
function viewReceipt(i){window.open('/payment/receipt?invo='+i,'_blank');}
function payZRA(i){if(confirm('Pay ZRA?'))$.post('/api/zra-pay',{invo:i,_token:$('meta[name="csrf-token"]').attr('content')},function(r){if(r.success){alert(r.message);loadReceipts();}else alert(r.message);});}
function printZRA(i,n,c,d,s,r){var w=window.open('','_blank','width=450,height=650');w.document.write('<!DOCTYPE html><html><head><title>ZRA Receipt</title><style>*{margin:0;padding:0}body{font-family:Arial;text-align:center;padding:20px;background:#f4f6f9}.box{background:#fff;border:2px solid #1a2332;border-radius:12px;padding:25px;max-width:420px;margin:0 auto}.logo{width:100px;margin-bottom:5px}.school{font-size:16px;font-weight:700;color:#1a2332}.sub{font-size:10px;color:#888}.title{font-size:14px;font-weight:700;color:#fff;background:#003399;padding:8px;border-radius:5px;margin:10px 0}.info{text-align:left;font-size:13px}.info td{padding:5px 8px;border-bottom:1px solid #eee}.lbl{font-weight:700;color:#666;width:120px}.total{font-size:16px;font-weight:700;color:#dc3545;text-align:right;margin-top:10px;padding-top:10px;border-top:2px solid #1a2332}.footer{font-size:9px;color:#888;margin-top:15px}@media print{body{background:#fff;padding:0}}</style></head><body><div class="box"><img src="https://i.ibb.co/7JY9jHwM/logo-zra.png" class="logo"><div class="school">ZANZIBAR REVENUE AUTHORITY</div><div class="sub">ZRA Receipt</div><div class="title">ZRA RECEIPT</div><table class="info"><tr><td class="lbl">Receipt No:</td><td>'+i+'</td></tr><tr><td class="lbl">Reg No:</td><td>'+r+'</td></tr><tr><td class="lbl">Name:</td><td>'+n+'</td></tr><tr><td class="lbl">Date:</td><td>'+d+'</td></tr><tr><td class="lbl">ZRA Status:</td><td>'+s+'</td></tr></table><div class="total">TOTAL: '+Number(c).toLocaleString()+' TZS</div><div class="footer">Glorious Academy</div></div><script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
$(function(){loadReceipts();});
</script>
@endsection
