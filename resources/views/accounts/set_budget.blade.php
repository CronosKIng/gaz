@extends('layouts.topmenu')
@section('title', 'Set Budget')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Set Budget</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="background:#4CAF50;color:#fff;padding:8px 20px;border-radius:6px;font-weight:700;margin:10px 0;">Budget Panel - TOTAL: <span id="totalBudget">0</span> /=</div>
    <div style="background:#fff;border-radius:10px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:15px;">
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:150px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Section</label><select id="sectionSelect" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"><option value="">SELECT ITEM</option><option>SALARIES AND WAGES</option><option>ADMINISTRATIVE EXPENSES</option><option>OTHER COST OF OPERATION</option><option>INTEREST AND FINANCIAL EXPENSES</option><option>REPAIR AND MAINTAINANCE</option><option>DEPRECIATION</option></select></div>
            <div style="flex:1;min-width:150px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Particular</label><input type="text" id="itermInput" placeholder="Particular" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"></div>
            <div style="flex:1;min-width:150px;"><label style="font-weight:600;color:#666;font-size:0.75rem;">Amount</label><input type="number" id="amountInput" placeholder="Amount" style="width:100%;padding:10px;border:2px solid #e9ecef;border-radius:6px;"></div>
            <div><label>&nbsp;</label><button onclick="addBudget()" style="background:#4CAF50;color:#fff;border:none;padding:10px 25px;border-radius:6px;font-weight:600;cursor:pointer;">Add+</button></div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:10px;">SECTION</th><th>PARTICULAR</th><th>AMOUNT</th><th></th></tr></thead>
            <tbody id="budgetBody"><tr><td colspan="4" style="text-align:center;padding:20px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function addBudget(){var d={section:$('#sectionSelect').val(),iterm:$('#itermInput').val(),amount:$('#amountInput').val(),_token:$('meta[name="csrf-token"]').attr('content')};if(!d.section||!d.iterm||!d.amount){alert('Fill all fields');return;}$.post('/api/set-budget-add',d,function(r){if(r.success){loadBudget();$('#itermInput,#amountInput').val('');}else alert(r.message||'Error');});}
function loadBudget(){$.get('/api/set-budget-list',function(r){var t=$('#budgetBody');t.empty();var total=0;if(r.success&&r.data.length>0){r.data.forEach(function(b){total+=parseFloat(b.amount||0);t.append('<tr><td>'+b.section+'</td><td>'+b.iterm+'</td><td>'+Number(b.amount||0).toLocaleString()+'</td><td><button onclick="deleteBudget('+b.id+')" style="background:#dc3545;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.65rem;cursor:pointer;">Delete</button></td></tr>');});}else{t.html('<tr><td colspan="4" style="text-align:center;padding:20px;">No budget items</td></tr>');}$('#totalBudget').text(Number(total).toLocaleString());});}
function deleteBudget(id){if(confirm('Delete?'))$.post('/api/set-budget-delete',{id:id,_token:$('meta[name="csrf-token"]').attr('content')},function(r){if(r.success)loadBudget();});}
$(function(){loadBudget();});
</script>
@endsection
