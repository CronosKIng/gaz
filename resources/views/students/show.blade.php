@extends('layouts.topmenu')
@section('title', 'Student Profile')
@section('styles')
<style>
    .profile-body{flex:1;overflow-y:auto;padding:15px 20px}
    .profile-card{background:#fff;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);padding:20px;margin-bottom:15px}
    .avatar-icon{width:100px;height:100px;border-radius:50%;background:#D9A52A;color:#fff;display:flex;align-items:center;justify-content:center;font-size:3rem;font-weight:700;margin:0 auto 10px}
    .balance-box{background:#dc3545;color:#fff;padding:8px 15px;border-radius:8px;text-align:center;font-weight:700;font-size:1rem}
    .info-row{display:flex;padding:5px 0;border-bottom:1px solid #f0f0f0}
    .info-row .lbl{font-weight:600;color:#666;width:160px;font-size:0.85rem}
    .info-row .val{color:#1a2332;font-weight:500;font-size:0.85rem}
    .tab-btn{background:#e9ecef;border:none;padding:8px 18px;border-radius:6px;font-weight:600;color:#666;cursor:pointer;font-size:0.8rem;margin-right:5px}
    .tab-btn.active,.tab-btn:hover{background:#D9A52A;color:#fff}
    .tab-content{display:none}.tab-content.active{display:block}
    .table-scroll{max-height:350px;overflow:auto}
    .table-scroll table{width:100%;border-collapse:collapse;font-size:0.8rem}
    .table-scroll th{background:#1a2332;color:#fff;padding:8px;position:sticky;top:0}
    .table-scroll td{padding:6px 8px;border-bottom:1px solid #eee}
    .year-selector{display:flex;gap:10px;align-items:center;margin-bottom:10px;flex-wrap:wrap}
    .year-selector select{padding:6px 12px;border:2px solid #e9ecef;border-radius:6px;font-size:0.8rem}
    .btn-load{background:#D9A52A;color:#fff;border:none;padding:6px 14px;border-radius:6px;font-weight:600;cursor:pointer;font-size:0.8rem}
    @media(max-width:768px){.profile-body{padding:10px}.info-row{flex-direction:column}.info-row .lbl{width:100%}}
</style>
@endsection
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Student Profile</h2>
        <a href="/students" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div class="profile-body">
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:15px;">
            <div>
                <div class="profile-card" style="text-align:center;">
                    <div class="avatar-icon">{{ strtoupper(substr($student->sname??'S',0,1)) }}</div>
                    <div style="font-weight:700;font-size:1.1rem;color:#1a2332;">{{ strtoupper($student->sname??'-') }}</div>
                    <div style="color:#D9A52A;font-weight:600;">{{ strtoupper($enrollment->class??'-') }}</div>
                    <hr>
                    <div class="balance-box">Balance: {{ number_format($balance??0) }} TZS</div>
                    <hr>
                    <div style="font-size:0.8rem;"><b>Last Seen:</b> {{ $attendance->date??'Not recorded' }}<br><b>Class Teacher:</b> {{ strtoupper($classTeacher->name??'Not assigned') }}<br><b>Teacher Mobile:</b> {{ $classTeacher->contact??'-' }}</div>
                </div>
            </div>
            <div>
                <div class="profile-card">
                    <div style="margin-bottom:10px;">
                        <button class="tab-btn active" onclick="showTab('info')">Info</button>
                        <button class="tab-btn" onclick="showTab('payment')">Payment</button>
                        <button class="tab-btn" onclick="showTab('exam')">Exam</button>
                        <button class="tab-btn" onclick="showTab('attendance')">Attendance</button>
                    </div>
                    <div id="tab-info" class="tab-content active">
                        <h5 style="color:#D9A52A;font-weight:700;margin-bottom:10px;">Student Information</h5>
                        <div class="info-row"><div class="lbl">Reg No</div><div class="val"><strong>{{ strtoupper($student->reg??'-') }}</strong></div></div>
                        <div class="info-row"><div class="lbl">Name</div><div class="val">{{ strtoupper($student->sname??'-') }}</div></div>
                        <div class="info-row"><div class="lbl">Gender</div><div class="val">{{ strtoupper($student->gender??'-') }}</div></div>
                        <div class="info-row"><div class="lbl">DOB</div><div class="val">{{ $student->dob??'-' }}</div></div>
                        <div class="info-row"><div class="lbl">Class</div><div class="val">{{ strtoupper($enrollment->class??'-') }}</div></div>
                        <h5 style="color:#D9A52A;font-weight:700;margin-top:10px;">Parent</h5>
                        <div class="info-row"><div class="lbl">Name</div><div class="val">{{ strtoupper($student->pgname??'-') }}</div></div>
                        <div class="info-row"><div class="lbl">Mobile</div><div class="val">{{ $student->pgmob??'-' }}</div></div>
                    </div>
                    <div id="tab-payment" class="tab-content">
                        <div class="year-selector"><select id="payYear"><option value="">All</option></select><button class="btn-load" onclick="loadPayment()">Load</button></div>
                        <div id="payContent"><p style="color:#888;">Select year to load</p></div>
                    </div>
                    <div id="tab-exam" class="tab-content">
                        <div class="year-selector"><select id="examYear"><option value="">All</option></select><select id="examType"><option value="">All Types</option></select><button class="btn-load" onclick="loadExams()">Load</button></div>
                        <div id="examContent"><p style="color:#888;">Select filters to load</p></div>
                    </div>
                    <div id="tab-attendance" class="tab-content">
                        <div class="year-selector"><select id="attYear"><option value="">All</option></select><button class="btn-load" onclick="loadAttendance()">Load</button></div>
                        <div id="attContent"><p style="color:#888;">Select year to load</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
var reg='{{ $student->reg??'' }}';
function showTab(t){document.querySelectorAll('.tab-content').forEach(e=>e.classList.remove('active'));document.querySelectorAll('.tab-btn').forEach(e=>e.classList.remove('active'));document.getElementById('tab-'+t).classList.add('active');document.querySelector(`.tab-btn[onclick*="${t}"]`).classList.add('active');if(t==='payment'&&!$('#payYear').data('loaded'))loadPayYears();if(t==='exam'&&!$('#examYear').data('loaded'))loadExamFilters();if(t==='attendance'&&!$('#attYear').data('loaded'))loadAttYears();}
function loadPayYears(){$.get('/api/student/payment-years?reg='+reg,function(r){var s=$('#payYear');s.empty().append('<option value="">All</option>');if(r.success)r.data.forEach(function(y){s.append('<option value="'+y+'">'+y+'</option>');});s.data('loaded',true);loadPayment();});}
function loadPayment(){$.get('/api/student/payment?reg='+reg+'&year='+$('#payYear').val(),function(r){var c=$('#payContent');if(r.success&&r.data){var h='<div class="table-scroll"><table><thead><tr><th>Item</th><th>Year</th><th>Fee</th><th>Paid</th><th>Balance</th></tr></thead><tbody>';var tf=0,tp=0,tb=0;if(r.data.bills)r.data.bills.forEach(function(b){var p=b.amount-b.balance;tf+=+b.amount;tp+=+p;tb+=+b.balance;h+='<tr><td>'+b.category+'</td><td>'+b.year+'</td><td>'+b.amount+'</td><td>'+p+'</td><td>'+b.balance+'</td></tr>';});h+='<tr style="font-weight:700;background:#f8f9fa;"><td colspan="2">TOTAL</td><td>'+tf+'</td><td>'+tp+'</td><td>'+tb+'</td></tr></tbody></table></div>';c.html(h);}else{c.html('<p style="color:#888;">No data</p>');}});}
function loadExamFilters(){$.get('/api/student/exam-filters?reg='+reg,function(r){if(r.success){var ys=$('#examYear');ys.empty().append('<option value="">All</option>');(r.data.years||[]).forEach(function(y){ys.append('<option>'+y+'</option>');});var ts=$('#examType');ts.empty().append('<option value="">All Types</option>');(r.data.types||[]).forEach(function(t){ts.append('<option>'+t+'</option>');});$('#examYear').data('loaded',true);loadExams();}});}
function loadExams(){$.get('/api/student/examinations?reg='+reg+'&year='+$('#examYear').val()+'&exam_type='+$('#examType').val(),function(r){var c=$('#examContent');if(r.success&&r.data&&r.data.results&&r.data.results.length>0){var h='<div class="table-scroll"><table><thead><tr><th>Subject</th><th>Marks</th><th>Grade</th><th>Type</th><th>Year</th></tr></thead><tbody>';r.data.results.forEach(function(e){h+='<tr><td>'+e.subject+'</td><td>'+e.marks+'</td><td>'+e.grade+'</td><td>'+e.examtp+'</td><td>'+e.acyear+'</td></tr>';});h+='</tbody></table></div>';c.html(h);}else{c.html('<p style="color:#888;">No exam records</p>');}});}
function loadAttYears(){$.get('/api/student/attendance-years?reg='+reg,function(r){var s=$('#attYear');s.empty().append('<option value="">All</option>');if(r.success)r.data.forEach(function(y){s.append('<option>'+y+'</option>');});s.data('loaded',true);loadAttendance();});}
function loadAttendance(){$.get('/api/student/attendance?reg='+reg+'&year='+$('#attYear').val(),function(r){var c=$('#attContent');if(r.success&&r.data){var h='<div style="display:flex;gap:10px;margin-bottom:10px;"><div style="background:#d4edda;padding:10px;border-radius:6px;text-align:center;flex:1;"><strong style="font-size:1.5rem;color:#155724;">'+(r.data.present||0)+'</strong><br><small>Present</small></div><div style="background:#f8d7da;padding:10px;border-radius:6px;text-align:center;flex:1;"><strong style="font-size:1.5rem;color:#721c24;">'+(r.data.absent||0)+'</strong><br><small>Absent</small></div><div style="background:#fff3cd;padding:10px;border-radius:6px;text-align:center;flex:1;"><strong style="font-size:1.5rem;color:#856404;">'+(r.data.reason||0)+'</strong><br><small>Reason</small></div></div>';if(r.data.records&&r.data.records.length>0){h+='<div class="table-scroll"><table><thead><tr><th>Date</th><th>Class</th><th>Status</th></tr></thead><tbody>';r.data.records.forEach(function(a){h+='<tr><td>'+a.date+'</td><td>'+a.class+'</td><td>'+a.status+'</td></tr>';});h+='</tbody></table></div>';}c.html(h);}else{c.html('<p style="color:#888;">No attendance records</p>');}});}
</script>
@endsection
