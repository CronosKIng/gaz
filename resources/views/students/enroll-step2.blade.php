@extends('layouts.topmenu')
@section('title', 'Enroll Students - Step 2')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Enroll Students - {{ $class }} ({{ $year }})</h2>
        <div>
            <a href="/students/enroll" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;margin-right:5px;">Back</a>
            <button onclick="deleteClass()" style="background:#dc3545;color:#fff;border:none;padding:5px 14px;border-radius:6px;cursor:pointer;font-size:0.8rem;">Delete Class</button>
        </div>
    </div>
    <div style="display:flex;gap:20px;padding-top:15px;flex-wrap:wrap;">
        <div style="flex:1;min-width:300px;">
            <h5 style="font-weight:700;color:#1a2332;">Available Students</h5>
            <div style="display:flex;gap:8px;margin-bottom:10px;"><input type="text" id="searchInput" placeholder="Search..." style="flex:1;padding:6px 12px;border:2px solid #e9ecef;border-radius:6px;"><button onclick="loadStudents()" style="background:#D9A52A;color:#fff;border:none;padding:6px 16px;border-radius:6px;cursor:pointer;">Search</button></div>
            <div style="border:1px solid #e9ecef;border-radius:6px;max-height:400px;overflow:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;"><thead><tr style="background:#1a2332;color:#fff;"><th style="padding:8px;">Reg No</th><th>Name</th><th>Class</th><th></th></tr></thead><tbody id="studentsBody"><tr><td colspan="4" style="text-align:center;padding:20px;">Loading...</td></tr></tbody></table>
            </div>
        </div>
        <div style="flex:1;min-width:300px;">
            <h5 style="font-weight:700;color:#1a2332;">Enrolled <span id="totalCount">(0)</span></h5>
            <div style="border:1px solid #e9ecef;border-radius:6px;max-height:400px;overflow:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;"><thead><tr style="background:#1a2332;color:#fff;"><th style="padding:8px;">Reg No</th><th>Name</th><th></th></tr></thead><tbody id="enrolledBody"><tr><td colspan="3" style="text-align:center;padding:20px;">Loading...</td></tr></tbody></table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
var cls='{{ $class }}',yr='{{ $year }}';
function loadStudents(){var s=$('#searchInput').val()||'';$.get('/api/enroll/students-list?search='+s,function(r){var t=$('#studentsBody');t.empty();if(r.success&&r.data.length>0){r.data.forEach(function(b){t.append('<tr><td><strong>'+b.reg+'</strong></td><td>'+b.sname+'</td><td>'+b.class+'</td><td><button onclick="addStudent(\''+b.reg+'\')" style="background:#28a745;color:#fff;border:none;padding:3px 12px;border-radius:4px;cursor:pointer;">Add+</button></td></tr>');});}else{t.html('<tr><td colspan="4" style="text-align:center;padding:20px;">No students</td></tr>');}});}
function loadEnrolled(){$.get('/api/enroll/enrolled-list',function(r){var t=$('#enrolledBody');t.empty();if(r.success&&r.data.length>0){r.data.forEach(function(b){t.append('<tr><td><strong>'+b.reg+'</strong></td><td>'+b.sname+'</td><td><button onclick="deleteStudent(\''+b.reg+'\')" style="background:#dc3545;color:#fff;border:none;padding:3px 12px;border-radius:4px;cursor:pointer;">Delete</button></td></tr>');});$('#totalCount').text('('+r.count+')');}else{t.html('<tr><td colspan="3" style="text-align:center;padding:20px;">No students</td></tr>');$('#totalCount').text('(0)');}});}
function addStudent(r){$.post('/api/enroll/add',{reg:r,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success){loadStudents();loadEnrolled();}else alert(d.message);});}
function deleteStudent(r){if(!confirm('Remove?'))return;$.post('/api/enroll/delete',{reg:r,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success){loadStudents();loadEnrolled();}else alert(d.message);});}
function deleteClass(){if(!confirm('Delete entire class?'))return;$.post('/api/enroll/delete-class',{_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success){alert('Deleted!');window.location.href='/students/enroll';}else alert(d.message);});}
$(function(){loadStudents();loadEnrolled();});
</script>
@endsection
