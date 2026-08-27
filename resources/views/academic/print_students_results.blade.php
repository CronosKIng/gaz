@extends('layouts.topmenu')
@section('title', 'Print Students Results')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Print Students Results</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="background:#DAE0E5;border-radius:8px;padding:15px;margin:10px 0;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <select id="classSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Select Class</option></select>
        <select id="examSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Select Exam</option><option>First middle term</option><option>First term</option><option>Second middle term</option><option>Annual examination</option></select>
        <select id="yearSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Year</option><option>2026</option><option>2025</option><option>2024</option></select>
        <button onclick="viewStudentResults()" style="background:#FF9900;color:#fff;border:none;padding:10px 20px;border-radius:4px;font-weight:600;cursor:pointer;">View Results</button>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.75rem;white-space:nowrap;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:8px;">SNO</th><th>REG NO</th><th>NAME</th><th>TOTAL</th><th>AVG</th><th>POSITION</th><th>DIVISION</th><th>ACTION</th></tr></thead>
            <tbody id="resultBody"><tr><td colspan="8" style="text-align:center;padding:20px;">Select filters</td></tr></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(function(){
    $.get('/api/class-list-dropdown', function(r){
        if(r.success) {
            r.data.forEach(function(c){
                $('#classSelect').append('<option value="'+c.class+'">'+c.class+' | '+c.level+'</option>');
            });
        }
    });
});

function viewStudentResults(){
    var c = $('#classSelect').val();
    var e = $('#examSelect').val();
    var y = $('#yearSelect').val();
    
    if(!c || !e || !y){
        alert('Select all');
        return;
    }
    
    $('#resultBody').html('<tr><td colspan="8" style="text-align:center;padding:20px;">Loading...</td></tr>');
    
    $.get('/api/student-results-summary', {class:c, examtp:e, acyear:y}, function(r){
        var t = $('#resultBody');
        t.empty();
        
        if(r.success && r.data.length > 0){
            r.data.forEach(function(b, i){
                var divisionBadge = b.division ? '<span style="background:#FF9900;color:#fff;padding:2px 8px;border-radius:4px;font-weight:700;">'+b.division+'</span>' : '-';
                t.append('<tr>' +
                    '<td style="padding:8px;">'+(i+1)+'</td>' +
                    '<td style="padding:8px;"><strong>'+b.reg+'</strong></td>' +
                    '<td style="padding:8px;">'+b.sname+'</td>' +
                    '<td style="padding:8px;text-align:center;">'+b.total+'</td>' +
                    '<td style="padding:8px;text-align:center;">'+b.avg+'</td>' +
                    '<td style="padding:8px;text-align:center;"><strong>'+b.pos+'</strong></td>' +
                    '<td style="padding:8px;text-align:center;">'+divisionBadge+'</td>' +
                    '<td style="padding:8px;text-align:center;"><button onclick="printReportCard(\''+b.reg+'\',\''+e+'\',\''+y+'\',\''+c+'\')" style="background:#FF9900;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-size:0.65rem;cursor:pointer;">Print</button></td>' +
                '</tr>');
            });
        } else {
            t.html('<tr><td colspan="8" style="text-align:center;padding:20px;">No results</td></tr>');
        }
    });
}

function printReportCard(reg, exam, year, cls){
    // Open report card in new window with PIVOT style
    var url = '/api/print-report-card?reg=' + reg + '&exam=' + exam + '&year=' + year + '&class=' + cls;
    window.open(url, '_blank');
}
</script>
@endsection