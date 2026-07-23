@extends('layouts.topmenu')
@section('title', 'Payment Account')
@section('styles')
<style>
    .balance-box{background:#dc3545;color:#fff;padding:8px 20px;border-radius:6px;text-align:center;font-size:1.2rem;font-weight:700;margin:8px 0}
    .student-info{background:#fff;border-radius:8px;padding:12px 18px;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:10px}
    .student-info table{width:100%;border-collapse:collapse;font-size:0.85rem}
    .student-info td{padding:5px 8px;border-bottom:1px solid #f0f0f0}
    .student-info .lbl{font-weight:600;color:#666;width:100px;font-size:0.8rem;text-transform:uppercase}
    .student-info .val{font-weight:500;color:#1a2332}
    .tab-nav{display:flex;flex-wrap:wrap;gap:8px;padding:12px;background:#f8f9fa;border-bottom:1px solid #ddd}
    .tab-link{padding:10px 18px;border:1px solid #ddd;background:#fff;border-radius:6px;font-weight:600;font-size:0.85rem;color:#444;text-decoration:none}
    .tab-link:hover,.tab-link.active{background:#D9A52A;color:#fff;border-color:#D9A52A}
    .tab-content-area{padding:20px;background:#fff;overflow:auto;flex:1}
    .data-table{width:100%;border-collapse:collapse;font-size:0.85rem}
    .data-table th{background:#1a2332;color:#fff;padding:12px;font-size:13px;text-align:left;text-transform:uppercase}
    .data-table td{padding:10px;border-bottom:1px solid #eee}
    .btn-sm{padding:4px 12px;border-radius:4px;font-weight:600;font-size:0.7rem;cursor:pointer;border:none}
    .btn-pay{background:#28a745;color:#fff}.btn-print{background:#17a2b8;color:#fff}
    .status-paid{background:#d4edda;color:#155724;padding:2px 8px;border-radius:20px;font-size:0.65rem}
    .status-pending{background:#fff3cd;color:#856404;padding:2px 8px;border-radius:20px;font-size:0.65rem}
    @media(max-width:768px){.tab-nav{grid-template-columns:repeat(2,1fr)}.tab-link{width:100%;text-align:center}}
</style>
@endsection
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Payment Account</h2>
        <a href="/receive-payment" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div class="balance-box">BALANCE: <span>{{ number_format($totalBalance??0,0) }}</span> TZS</div>
    <div class="student-info">
        <table>
            <tr><td class="lbl">Reg No</td><td class="val"><strong>{{ $student->reg??'-' }}</strong></td></tr>
            <tr><td class="lbl">Name</td><td class="val">{{ $student->sname??'-' }}</td></tr>
            <tr><td class="lbl">Level</td><td class="val">{{ $enrollment->level??$student->level??'-' }}</td></tr>
            <tr><td class="lbl">Class</td><td class="val">{{ $enrollment->class??$student->class??'-' }}</td></tr>
            <tr><td class="lbl">Year</td><td class="val">{{ $enrollment->year??$student->year??'-' }}</td></tr>
            <tr><td class="lbl">Gender</td><td class="val">{{ $student->gender??'-' }}</td></tr>
            <tr><td class="lbl">Mobile</td><td class="val">{{ $student->pgmob??'-' }}</td></tr>
        </table>
    </div>
    <div style="background:#fff;border-radius:10px;box-shadow:0 3px 12px rgba(0,0,0,0.08);flex:1;overflow:hidden;">
        <div class="tab-nav">
            <a href="/payment/payment" class="tab-link {{($activeTab??'payment')=='payment'?'active':''}}">Payment</a>
            <a href="/payment/cnumber" class="tab-link {{($activeTab??'')=='cnumber'?'active':''}}">C.Number</a>
            <a href="/payment/transid" class="tab-link {{($activeTab??'')=='transid'?'active':''}}">Trans ID</a>
            <a href="#" class="tab-link {{($activeTab??'')=='bills'?'active':''}}">Bills</a>
            <a href="/payment/receipts" class="tab-link {{($activeTab??'')=='receipts'?'active':''}}">Receipts</a>
            <a href="/payment/invoice" class="tab-link {{($activeTab??'')=='invoice'?'active':''}}">Invoice</a>
        </div>
        <div class="tab-content-area">
            @php $at=$activeTab??'payment'; @endphp
            @if($at=='payment')
            <table class="data-table"><thead><tr><th>Category</th><th>Amount</th><th>Year</th><th>Status</th><th>Action</th></tr></thead><tbody>
                @forelse($payments??[] as $p)
                <tr><td>{{$p->category??'-'}}</td><td>{{number_format($p->balance??0)}}</td><td>{{$p->year??'-'}}</td><td><span class="{{($p->balance??0)==0?'status-paid':'status-pending'}}">{{($p->balance??0)==0?'Paid':'Pending'}}</span></td><td>@if(($p->balance??0)>0)<button class="btn-sm btn-pay" onclick="processPayment('{{$p->id}}','{{$student->sname??''}}')">Pay</button>@else <span style="color:#28a745;">Paid</span>@endif</td></tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:30px;">No payment records</td></tr>
                @endforelse
            </tbody></table>
            @elseif($at=='transid')
            <h5 style="color:#1a2332;margin-bottom:15px;font-weight:700;">TRANSPORT ID CARDS</h5>
            <table class="data-table"><thead><tr><th>RECEIPT NO</th><th>REG NO</th><th>NAME</th><th>ID TYPE</th><th>YEAR</th><th>ACTION</th></tr></thead><tbody>
                @forelse($transactions??[] as $t)
                <tr><td><strong>{{$t->invoid??'-'}}</strong></td><td>{{$t->reg??'-'}}</td><td>{{$t->name??'-'}}</td><td>Transport</td><td>{{$t->year??'-'}}</td><td><button class="btn-sm btn-print" onclick="printTransid('{{$t->invoid}}','{{$t->reg}}','{{addslashes($t->name)}}','{{$t->year}}')">Print ID</button></td></tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:30px;">No Transport ID Cards</td></tr>
                @endforelse
            </tbody></table>
            @elseif($at=='bills')
            <h5 style="color:#1a2332;margin-bottom:15px;font-weight:700;">BILLS</h5>
            <table class="data-table"><thead><tr><th>#</th><th>Category</th><th>Amount</th><th>Year</th><th>Status</th></tr></thead><tbody>
                @forelse($payments??[] as $i=>$b)
                <tr><td>{{$i+1}}</td><td>{{$b->category??'-'}}</td><td>{{number_format($b->amount??0)}} TZS</td><td>{{$b->year??'-'}}</td><td><span class="{{($b->balance??0)==0?'status-paid':'status-pending'}}">{{($b->balance??0)==0?'Paid':'Pending'}}</span></td></tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:30px;">No bills</td></tr>
                @endforelse
            </tbody></table>
            @elseif($at=='receipts')
            <h5 style="color:#1a2332;margin-bottom:15px;font-weight:700;">RECEIPTS</h5>
            <table class="data-table"><thead><tr><th>RECEIPT NO</th><th>DATE</th><th>AMOUNT</th><th>YEAR</th><th>MODE</th><th>ACTION</th></tr></thead><tbody>
                @forelse($transactions??[] as $t)
                <tr><td><strong>{{$t->invoid??'-'}}</strong></td><td>{{$t->date??'-'}}</td><td>{{number_format($t->cost??0)}} TZS</td><td>{{$t->year??'-'}}</td><td>{{$t->mode??'-'}}</td><td><button class="btn-sm btn-print" onclick="printReceipt('{{$t->invoid}}','{{$t->reg}}','{{addslashes($t->name)}}','{{$t->cost}}','{{$t->date}}','{{$t->year}}','{{$t->mode}}')">Print</button></td></tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:30px;">No receipts</td></tr>
                @endforelse
            </tbody></table>
            @elseif($at=='invoice')
            <h5 style="color:#1a2332;margin-bottom:15px;font-weight:700;">INVOICE</h5>
            <table class="data-table"><thead><tr><th>INVOICE NO</th><th>DATE</th><th>CATEGORY</th><th>AMOUNT</th><th>YEAR</th><th>ACTION</th></tr></thead><tbody>
                @php $invs=DB::table('allpayment')->where('reg',$student->reg??'')->orderBy('date','desc')->get(); @endphp
                @forelse($invs as $inv)
                <tr><td><strong>{{$inv->invo??'-'}}</strong></td><td>{{$inv->date??'-'}}</td><td>{{$inv->category??'-'}}</td><td>{{number_format($inv->amount??0)}} TZS</td><td>{{$inv->year??'-'}}</td><td><button class="btn-sm btn-print" onclick="printInvoice('{{$inv->invo}}','{{$inv->reg}}','{{addslashes($inv->name)}}','{{$inv->category}}','{{$inv->amount}}','{{$inv->date}}','{{$inv->year}}')">Print</button></td></tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:30px;">No invoices</td></tr>
                @endforelse
            </tbody></table>
            @endif
        </div>
    </div>
</div>
<div id="payModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;justify-content:center;align-items:center;">
    <div style="background:#fff;padding:25px;border-radius:10px;max-width:450px;width:90%;">
        <h4>Confirm Payment</h4><p>Process this payment?</p>
        <div style="text-align:right;margin-top:15px;">
            <button onclick="closePayModal()" style="background:#6c757d;color:#fff;border:none;padding:8px 25px;border-radius:6px;cursor:pointer;">Cancel</button>
            <button id="confirmPayBtn" style="background:#D9A52A;color:#fff;border:none;padding:8px 25px;border-radius:6px;cursor:pointer;">Confirm</button>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
var curCat=null,curSname='';
function processPayment(c,s){curCat=c;curSname=s;document.getElementById('payModal').style.display='flex';}
function closePayModal(){document.getElementById('payModal').style.display='none';curCat=null;}
document.getElementById('confirmPayBtn').addEventListener('click',function(){
    if(!curCat)return;var b=this;b.disabled=true;b.innerHTML='Processing...';
    fetch('/api/student-payment/process-payment',{method:'POST',headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','Accept':'application/json'},body:JSON.stringify({catid:curCat,sname:curSname,_token:'{{csrf_token()}}'})})
    .then(r=>r.json()).then(d=>{b.disabled=false;b.innerHTML='Confirm';if(d.success){alert(d.message);closePayModal();location.reload();}else alert(d.message||'Error');})
    .catch(()=>{b.disabled=false;b.innerHTML='Confirm';alert('Error');});
});
function printTransid(i,r,n,y){var w=window.open('','_blank','width=550,height=420');w.document.write('<!DOCTYPE html><html><head><title>Transport ID</title><style>*{margin:0;padding:0}body{font-family:Arial;text-align:center;padding:15px;background:#f4f6f9}.card{background:#fff;border:3px solid #1a2332;border-radius:12px;padding:20px;max-width:480px;margin:0 auto}.logo{width:60px;height:60px;border-radius:50%}.school{font-size:15px;font-weight:700;color:#1a2332}.sub{font-size:10px;color:#888}.title{font-size:13px;font-weight:700;color:#fff;background:#D9A52A;padding:6px 15px;border-radius:4px;display:inline-block;margin-bottom:12px}.body{display:flex;gap:15px}.photo{width:100px;height:120px;border:2px dashed #ccc;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#f9f9f9}.info{flex:1;text-align:left;font-size:12px}.info td{padding:4px 5px;border-bottom:1px solid #eee}.lbl{font-weight:700;color:#666;width:85px;font-size:10px}.footer{font-size:9px;color:#888;margin-top:12px;border-top:1px solid #eee;padding-top:8px}@media print{body{background:#fff;padding:0}}</style></head><body><div class="card"><div class="card-header"><img src="https://i.ibb.co/gMfmMQ2B/logo.png" class="logo"><div class="school">GLORIOUS ACADEMY</div><div class="sub">Zanzibar, Tanzania</div></div><div class="title">TRANSPORT ID CARD</div><div class="body"><div class="photo"><span style="font-size:40px;color:#ccc;">&#128100;</span></div><div class="info"><table><tr><td class="lbl">Receipt No:</td><td>'+i+'</td></tr><tr><td class="lbl">Reg No:</td><td>'+r+'</td></tr><tr><td class="lbl">Name:</td><td>'+n+'</td></tr><tr><td class="lbl">ID Type:</td><td>Transport</td></tr><tr><td class="lbl">Year:</td><td>'+y+'</td></tr></table></div></div><div class="footer">Glorious Academy &bull; Valid for '+y+'</div></div><script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
function printReceipt(i,r,n,c,d,y,m){var w=window.open('','_blank','width=450,height=600');w.document.write('<!DOCTYPE html><html><head><title>Receipt</title><style>*{margin:0;padding:0}body{font-family:Arial;text-align:center;padding:20px;background:#f4f6f9}.box{background:#fff;border:2px solid #1a2332;border-radius:12px;padding:25px;max-width:420px;margin:0 auto}.logo{width:80px;height:80px;border-radius:50%;margin-bottom:10px}.school{font-size:16px;font-weight:700;color:#1a2332}.sub{font-size:10px;color:#888}.title{font-size:14px;font-weight:700;color:#fff;background:#1a2332;padding:8px;border-radius:5px;margin:12px 0}.info{text-align:left;font-size:13px}.info td{padding:5px 8px;border-bottom:1px solid #eee}.lbl{font-weight:700;color:#666;width:110px}.total{font-size:16px;font-weight:700;color:#dc3545;text-align:right;margin-top:10px;padding-top:10px;border-top:2px solid #1a2332}.footer{font-size:9px;color:#888;margin-top:15px}@media print{body{background:#fff;padding:0}}</style></head><body><div class="box"><img src="https://i.ibb.co/gMfmMQ2B/logo.png" class="logo"><div class="school">GLORIOUS ACADEMY</div><div class="sub">Zanzibar, Tanzania</div><div class="title">PAYMENT RECEIPT</div><table class="info"><tr><td class="lbl">Receipt No:</td><td>'+i+'</td></tr><tr><td class="lbl">Reg No:</td><td>'+r+'</td></tr><tr><td class="lbl">Name:</td><td>'+n+'</td></tr><tr><td class="lbl">Date:</td><td>'+d+'</td></tr><tr><td class="lbl">Year:</td><td>'+y+'</td></tr><tr><td class="lbl">Mode:</td><td>'+m+'</td></tr></table><div class="total">TOTAL: '+Number(c).toLocaleString()+' TZS</div><div class="footer">Glorious Academy</div></div><script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
function printInvoice(i,r,n,cat,a,d,y){var w=window.open('','_blank','width=450,height=600');w.document.write('<!DOCTYPE html><html><head><title>Invoice</title><style>*{margin:0;padding:0}body{font-family:Arial;text-align:center;padding:20px;background:#f4f6f9}.box{background:#fff;border:2px solid #1a2332;border-radius:12px;padding:25px;max-width:420px;margin:0 auto}.logo{width:80px;height:80px;border-radius:50%;margin-bottom:10px}.school{font-size:16px;font-weight:700;color:#1a2332}.sub{font-size:10px;color:#888}.title{font-size:14px;font-weight:700;color:#fff;background:#D9A52A;padding:8px;border-radius:5px;margin:12px 0}.info{text-align:left;font-size:13px}.info td{padding:5px 8px;border-bottom:1px solid #eee}.lbl{font-weight:700;color:#666;width:110px}.total{font-size:16px;font-weight:700;color:#28a745;text-align:right;margin-top:10px;padding-top:10px;border-top:2px solid #1a2332}.paid{text-align:center;margin-top:8px;font-size:12px;font-weight:700;color:#155724;background:#d4edda;padding:5px;border-radius:4px}.footer{font-size:9px;color:#888;margin-top:15px}@media print{body{background:#fff;padding:0}}</style></head><body><div class="box"><img src="https://i.ibb.co/gMfmMQ2B/logo.png" class="logo"><div class="school">GLORIOUS ACADEMY</div><div class="sub">Zanzibar, Tanzania</div><div class="title">INVOICE</div><table class="info"><tr><td class="lbl">Invoice No:</td><td>'+i+'</td></tr><tr><td class="lbl">Reg No:</td><td>'+r+'</td></tr><tr><td class="lbl">Name:</td><td>'+n+'</td></tr><tr><td class="lbl">Category:</td><td>'+cat+'</td></tr><tr><td class="lbl">Date:</td><td>'+d+'</td></tr><tr><td class="lbl">Year:</td><td>'+y+'</td></tr></table><div class="total">TOTAL: '+Number(a).toLocaleString()+' TZS</div><div class="paid">PAID</div><div class="footer">Glorious Academy</div></div><script>window.onload=function(){window.print()}<\/script></body></html>');w.document.close();}
</script>
@endsection
