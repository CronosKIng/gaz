@extends('layouts.topmenu')
@section('title', 'Receipt')
@section('content')
<div style="display:flex;justify-content:center;align-items:center;min-height:80vh;padding:20px;">
    <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 4px 20px rgba(0,0,0,0.1);max-width:500px;width:100%;">
        <div style="text-align:center;border-bottom:2px solid #e9ecef;padding-bottom:15px;margin-bottom:15px;">
            <h3 style="font-weight:700;color:#1a2332;">Glorious Academy</h3>
            <p style="color:#666;font-size:0.9rem;">Zanzibar, Tanzania</p>
            <p style="font-size:0.8rem;">Receipt Number: <strong>{{ $invo??'-' }}</strong></p>
        </div>
        <div style="display:flex;padding:6px 0;border-bottom:1px solid #f5f5f5;"><span style="font-weight:600;color:#666;width:140px;">Registration</span><span style="color:#1a2332;">{{ $student->reg??'-' }}</span></div>
        <div style="display:flex;padding:6px 0;border-bottom:1px solid #f5f5f5;"><span style="font-weight:600;color:#666;width:140px;">Name</span><span style="color:#1a2332;">{{ $student->sname??'-' }}</span></div>
        <div style="display:flex;padding:6px 0;border-bottom:1px solid #f5f5f5;"><span style="font-weight:600;color:#666;width:140px;">Date</span><span style="color:#1a2332;">{{ date('Y-m-d') }}</span></div>
        <div style="display:flex;padding:6px 0;border-bottom:1px solid #f5f5f5;"><span style="font-weight:600;color:#666;width:140px;">Payment Mode</span><span style="color:#1a2332;">CASH</span></div>
        <div style="margin:10px 0;border-top:1px solid #e9ecef;"></div>
        @foreach($payments as $p)
        <div style="display:flex;padding:6px 0;border-bottom:1px solid #f5f5f5;"><span style="font-weight:600;color:#666;width:140px;">{{ $p->category??'Payment' }}</span><span style="color:#1a2332;">{{ number_format($p->amount??0) }} TZS</span></div>
        @endforeach
        <div style="display:flex;padding:10px 0;border-top:2px solid #1a2332;margin-top:10px;"><span style="font-weight:700;color:#1a2332;width:140px;font-size:1.1rem;">Total</span><span style="font-weight:700;color:#dc3545;font-size:1.1rem;">{{ number_format($total??0) }} TZS</span></div>
        <div style="text-align:center;margin-top:20px;">
            <button onclick="window.print()" style="background:#D9A52A;color:#fff;border:none;padding:10px 30px;border-radius:6px;font-weight:600;cursor:pointer;">Print</button>
            <a href="/payment/account" style="background:#6c757d;color:#fff;padding:10px 30px;border-radius:6px;text-decoration:none;font-weight:600;margin-left:10px;">Back</a>
        </div>
    </div>
</div>
@endsection
