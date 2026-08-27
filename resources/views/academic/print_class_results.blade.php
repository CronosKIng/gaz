@extends('layouts.topmenu')
@section('title', 'Print Class Results')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Print Class Results</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="background:#DAE0E5;border-radius:8px;padding:15px;margin:10px 0;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <select id="classSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Select Class</option></select>
        <select id="examSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Select Exam</option><option>First middle term</option><option>First term</option><option>Second middle term</option><option>Annual examination</option></select>
        <select id="yearSelect" style="padding:10px;border:1px solid #ccc;border-radius:4px;"><option value="">Year</option><option>2026</option><option>2025</option><option>2024</option></select>
        <button onclick="loadResults()" style="background:#FF9900;color:#fff;border:none;padding:10px 20px;border-radius:4px;font-weight:600;cursor:pointer;">View Results</button>
        <button onclick="printResults()" style="background:#17a2b8;color:#fff;border:none;padding:8px 15px;border-radius:4px;font-weight:600;cursor:pointer;">Print</button>
    </div>
    <div style="background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow-x:auto;" id="printArea">
        <table style="width:100%;border-collapse:collapse;font-size:0.75rem;white-space:nowrap;">
            <thead>
                <tr style="background:#1a2332;color:#fff;">
                    <th style="padding:8px;">SNO</th>
                    <th style="padding:8px;">REG NO</th>
                    <th style="padding:8px;">NAME</th>
                    <th style="padding:8px;" id="subjectHeaders"></th>
                    <th style="padding:8px;">TOTAL</th>
                    <th style="padding:8px;">AVERAGE</th>
                    <th style="padding:8px;">GRADE</th>
                    <th style="padding:8px;">POSITION</th>
                </tr>
            </thead>
            <tbody id="resultBody">
                <tr><td colspan="8" style="text-align:center;padding:20px;">Select filters</td></tr>
            </tbody>
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

function loadResults(){
    var c = $('#classSelect').val();
    var e = $('#examSelect').val();
    var y = $('#yearSelect').val();
    
    if(!c || !e || !y){
        alert('Select all');
        return;
    }
    
    $('#resultBody').html('<tr><td colspan="8" style="text-align:center;padding:20px;">Loading...</td></tr>');
    
    $.get('/api/class-results', {class:c, examtp:e, acyear:y}, function(r){
        var t = $('#resultBody');
        t.empty();
        
        if(r.success && r.data && r.data.length > 0 && r.subjects && r.subjects.length > 0){
            // Build subject headers
            var subjectHeaders = '';
            r.subjects.forEach(function(subject){
                subjectHeaders += '<th style="padding:8px;">' + subject.toUpperCase() + '</th>';
            });
            
            // Update header row with subjects
            $('#subjectHeaders').replaceWith(subjectHeaders);
            
            // Build body rows
            r.data.forEach(function(b, i){
                var row = '<tr>';
                row += '<td style="padding:8px;">' + (i + 1) + '</td>';
                row += '<td style="padding:8px;"><strong>' + b.reg + '</strong></td>';
                row += '<td style="padding:8px;">' + b.name + '</td>';
                
                // Add marks for each subject
                r.subjects.forEach(function(subject){
                    var alias = subject.toUpperCase().replace(/ /g, '_');
                    var marks = b[alias] !== null && b[alias] !== undefined ? b[alias] : '-';
                    var grade = b[alias + '_GRADE'] || '';
                    row += '<td style="padding:8px;text-align:center;">' + marks + (grade ? ' <small>(' + grade + ')</small>' : '') + '</td>';
                });
                
                row += '<td style="padding:8px;text-align:center;"><strong>' + (b.TOTAL || '-') + '</strong></td>';
                row += '<td style="padding:8px;text-align:center;"><strong>' + (b.AVERAGE || '-') + '</strong></td>';
                row += '<td style="padding:8px;text-align:center;"><span style="background:#FF9900;color:#fff;padding:2px 8px;border-radius:4px;font-weight:700;">' + (b.grade || '-') + '</span></td>';
                row += '<td style="padding:8px;text-align:center;"><strong>' + (b.position || '-') + '</strong></td>';
                row += '</tr>';
                t.append(row);
            });
        } else {
            // Fallback: Kama data ni ya mtindo wa zamani
            if(r.success && r.data && r.data.length > 0){
                // Check kama data ni pivot au la
                if(r.data[0].subject){
                    // Old style - group by student
                    var studentsMap = {};
                    r.data.forEach(function(b){
                        if(!studentsMap[b.reg]){
                            studentsMap[b.reg] = {reg: b.reg, name: b.sname, subjects: [], total: 0};
                        }
                        studentsMap[b.reg].subjects.push(b);
                        studentsMap[b.reg].total += parseFloat(b.marks);
                    });
                    
                    // Build dynamic subject headers
                    var allSubjects = [];
                    r.data.forEach(function(b){
                        if(allSubjects.indexOf(b.subject) === -1) allSubjects.push(b.subject);
                    });
                    
                    var headers = '';
                    allSubjects.forEach(function(subject){
                        headers += '<th style="padding:8px;">' + subject.toUpperCase() + '</th>';
                    });
                    $('#subjectHeaders').replaceWith(headers);
                    
                    var index = 0;
                    Object.keys(studentsMap).forEach(function(reg){
                        var student = studentsMap[reg];
                        index++;
                        var avg = student.total / student.subjects.length;
                        var grade = avg >= 81 ? 'A' : (avg >= 61 ? 'B' : (avg >= 45 ? 'C' : (avg >= 35 ? 'D' : 'F')));
                        
                        var row = '<tr>';
                        row += '<td style="padding:8px;">' + index + '</td>';
                        row += '<td style="padding:8px;"><strong>' + student.reg + '</strong></td>';
                        row += '<td style="padding:8px;">' + student.name + '</td>';
                        
                        allSubjects.forEach(function(subject){
                            var found = student.subjects.find(function(s){ return s.subject === subject; });
                            row += '<td style="padding:8px;text-align:center;">' + (found ? found.marks + ' (' + found.grade + ')' : '-') + '</td>';
                        });
                        
                        row += '<td style="padding:8px;text-align:center;"><strong>' + student.total + '</strong></td>';
                        row += '<td style="padding:8px;text-align:center;"><strong>' + avg.toFixed(2) + '</strong></td>';
                        row += '<td style="padding:8px;text-align:center;"><span style="background:#FF9900;color:#fff;padding:2px 8px;border-radius:4px;font-weight:700;">' + grade + '</span></td>';
                        row += '<td style="padding:8px;text-align:center;"><strong>' + index + '</strong></td>';
                        row += '</tr>';
                        t.append(row);
                    });
                } else {
                    t.html('<tr><td colspan="8" style="text-align:center;padding:20px;">No results</td></tr>');
                }
            } else {
                t.html('<tr><td colspan="8" style="text-align:center;padding:20px;">No results</td></tr>');
            }
        }
    });
}

function printResults(){
    var w = window.open('','_blank');
    w.document.write('<!DOCTYPE html><html><head><title>Results</title><style>table{width:100%;border-collapse:collapse;font-size:12px}th{background:#000;color:#fff;padding:8px}td{padding:6px;border:1px solid #ddd}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>');
    w.document.close();
}
</script>
@endsection