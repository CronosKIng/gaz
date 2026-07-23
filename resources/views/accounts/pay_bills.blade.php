@extends('layouts.topmenu')
@section('title', 'Pay Bills')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Pay Bills</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="background:#fff;border-radius:10px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin:10px 0;">
        <h5 style="color:#1a2332;margin-bottom:12px;font-weight:700;">CREATE NEW VOUCHER</h5>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:130px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Category</label><select id="categorySelect" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"><option value="">SELECT CATEGORY</option><option>SALARIES AND WAGES</option><option>ADMINISTRATIVE EXPENSES</option><option>OTHER COST OF OPERATION</option><option>INTEREST AND FINANCIAL EXPENSES</option><option>REPAIR AND MAINTAINANCE</option><option>DEPRECIATION</option></select></div>
            <div style="flex:1;min-width:130px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Item/Description</label><input type="text" id="itemInput" placeholder="Enter item description" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"></div>
            <div style="flex:1;min-width:130px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Supplier</label><input type="text" id="supplierInput" placeholder="Supplier" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"></div>
            <div style="flex:1;min-width:130px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Date</label><input type="date" id="dateInput" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"></div>
            <div style="flex:1;min-width:130px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Amount</label><input type="number" id="amountInput" placeholder="Amount" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"></div>
            <div><label>&nbsp;</label><button onclick="saveBill()" style="background:#008080;color:#fff;border:none;padding:10px 25px;border-radius:6px;font-weight:600;cursor:pointer;">DONE</button></div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <h5 style="color:#1a2332;font-weight:700;font-size:0.9rem;">VOUCHER LIST</h5>
            <input type="text" id="searchBills" placeholder="Search..." style="padding:6px 12px;border:1px solid #ddd;border-radius:4px;font-size:0.8rem;width:200px;" onkeyup="if(event.key==='Enter')loadBills()">
        </div>
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:10px;">VID</th><th>SUPPLIER</th><th>DESCRIPTION</th><th>DATE</th><th>AMOUNT</th><th>STATUS</th><th>ACTION</th></tr></thead>
            <tbody id="billsBody"><tr><td colspan="7" style="text-align:center;padding:20px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function saveBill(){
    var d={category:$('#categorySelect').val(),item:$('#itemInput').val(),supplier:$('#supplierInput').val(),date:$('#dateInput').val(),amount:$('#amountInput').val(),_token:$('meta[name="csrf-token"]').attr('content')};
    if(!d.category||!d.item||!d.supplier||!d.date||!d.amount){alert('Fill all fields');return;}
    $.post('/api/pay-bills-save',d,function(r){if(r.success){alert(r.message);loadBills();$('#itemInput,#supplierInput,#dateInput,#amountInput').val('');}else alert(r.message||'Error');});
}
function loadBills(){
    $.get('/api/pay-bills-list',{search:$('#searchBills').val()},function(r){
        var t=$('#billsBody');t.empty();
        if(r.success&&r.data.length>0){r.data.forEach(function(b){t.append('<tr><td><strong>'+(b.vid||'-')+'</strong></td><td>'+(b.supplier||'-')+'</td><td>'+(b.app||'-')+'</td><td>'+(b.date||'-')+'</td><td>'+Number(b.cost||0).toLocaleString()+'</td><td>'+(b.status||'Request')+'</td><td><button onclick="printVoucher(\''+b.vid+'\',\''+(b.supplier||'')+'\',\''+(b.app||'')+'\',\''+(b.date||'')+'\',\''+(b.cost||0)+'\',\''+(b.status||'Request')+'\')" style="background:#17a2b8;color:#fff;border:none;padding:3px 10px;border-radius:4px;font-size:0.65rem;cursor:pointer;">Print</button> <button onclick="deleteBill('+b.vid+')" style="background:#dc3545;color:#fff;border:none;padding:3px 10px;border-radius:4px;font-size:0.65rem;cursor:pointer;">X</button></td></tr>');});}
        else{t.html('<tr><td colspan="7" style="text-align:center;padding:20px;">No vouchers</td></tr>');}
    });
}
function deleteBill(id){if(confirm('Delete?'))$.post('/api/pay-bills-delete',{id:id,_token:$('meta[name="csrf-token"]').attr('content')},function(r){if(r.success)loadBills();});}
function printVoucher(vid,supplier,app,date,cost,status){var w=window.open('','_blank','width=450,height=600');w.document.write('<!DOCTYPE html><html><head><title>Voucher '+vid+'</title><style>*{margin:0;padding:0}body{font-family:Arial;text-align:center;padding:20px;background:#f4f6f9}.box{background:#fff;border:2px solid #1a2332;border-radius:12px;padding:25px;max-width:420px;margin:0 auto}.logo{width:80px;height:80px;border-radius:50%}.school{font-size:16px;font-weight:700;color:#1a2332}.sub{font-size:10px;color:#888}.title{font-size:14px;font-weight:700;color:#fff;background:#008080;padding:8px;border-radius:5px;margin:12px 0}.info{text-align:left;font-size:13px}.info td{padding:5px 8px;border-bottom:1px solid #eee}.lbl{font-weight:700;color:#666;width:110px}.total{font-size:16px;font-weight:700;color:#dc3545;text-align:right;margin-top:10px;padding-top:10px;border-top:2px solid #1a2332}.footer{font-size:9px;color:#888;margin-top:15px}@media print{body{background:#fff;padding:0}}</style></head><body><div class="box"><img src="https://i.ibb.co/gMfmMQ2B/logo.png" class="logo"><div class="school">GLORIOUS ACADEMY</div><div class="sub">Zanzibar, Tanzania</div><div class="title">PAYMENT VOUCHER</div><table class="info"><tr><td class="lbl">Voucher No:</td><td>'+vid+'</td></tr><tr><td class="lbl">Supplier:</td><td>'+supplier+'</td></tr><tr><td class="lbl">Description:</td><td>'+app+'</td></tr><tr><td class="lbl">Date:</td><td>'+date+'</td></tr><tr><td class="lbl">Status:</td><td>'+status+'</td></tr></table><div class="total">TOTAL: '+Number(cost).toLocaleString()+' TZS</div><div class="footer">Glorious Academy</div></div><script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
$(function(){loadBills();});
</script>
@endsection
