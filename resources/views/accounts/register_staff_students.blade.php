@extends('layouts.topmenu')
@section('title', 'Register Staff Students')
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<style>
    .staff-select-box { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 15px; }
    .staff-select-box select { width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 0.9rem; }
    .btn-done { background: #dc3545; color: #fff; border: none; padding: 10px 30px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem; }
    .main-content { display: flex; gap: 15px; flex: 1; min-height: 0; }
    .left-panel { flex: 1; background: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: auto; }
    .right-panel { width: 380px; background: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: auto; flex-shrink: 0; }
    .panel-title { font-weight: 700; color: #336699; margin-bottom: 10px; font-size: 0.95rem; border-bottom: 1px dotted #000080; padding-bottom: 8px; }
    .search-box { display: flex; gap: 8px; margin-bottom: 12px; }
    .search-box input { flex: 1; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.85rem; }
    .btn-search { background: #D9A52A; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.8rem; }
    .btn-add { background: #000; color: #fff; border: none; padding: 3px 12px; border-radius: 3px; font-weight: 600; cursor: pointer; font-size: 0.7rem; }
    .btn-remove { background: #dc3545; color: #fff; border: none; padding: 3px 12px; border-radius: 3px; font-weight: 600; cursor: pointer; font-size: 0.7rem; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .data-table th { background: #1a2332; color: #fff; padding: 8px 10px; font-size: 0.7rem; text-transform: uppercase; }
    .data-table td { padding: 6px 10px; border-bottom: 1px solid #eee; }
    .data-table input[readonly] { border: none; background: transparent; width: 100%; font-size: 0.8rem; color: #444; }
    .staff-header { font-weight: 700; color: #336699; font-size: 0.9rem; margin-bottom: 8px; }
    .total-students { font-weight: 700; font-size: 0.9rem; margin-top: 10px; padding-top: 8px; border-top: 1px solid #eee; }
    @media (max-width: 768px) { .main-content { flex-direction: column; } .right-panel { width: 100%; } }
</style>
@endsection
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Register Staff Students</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div class="staff-select-box">
        <div style="display:flex;gap:10px;align-items:flex-end;">
            <div style="flex:1;"><label style="font-weight:600;color:#336699;font-size:0.9rem;">Select Staff</label>
                <select id="staffSelect" class="search-select" style="width:100%;"><option value="">-- Select Staff --</option>
                    @foreach(DB::table('staff')->orderBy('name','asc')->get() as $s)
                    <option value="{{ $s->name }}">{{ $s->name }} | {{ $s->contact ?? $s->id }}</option>@endforeach
                </select>
            </div>
            <button class="btn-done" onclick="selectStaff()">Done</button>
        </div>
    </div>
    <div class="main-content">
        <div class="left-panel">
            <div class="panel-title">LIST OF STUDENTS</div>
            <div class="search-box"><input type="text" id="searchInput" placeholder="Search..." onkeyup="if(event.key==='Enter')searchStudents()"><button class="btn-search" onclick="searchStudents()">Search</button></div>
            <table class="data-table"><thead><tr><th>REG NO</th><th>NAME</th><th>CLASS</th><th></th></tr></thead><tbody id="allStudentsBody"><tr><td colspan="4" style="text-align:center;padding:20px;">Search or select staff</td></tr></tbody></table>
        </div>
        <div class="right-panel">
            <div class="staff-header" id="staffHeader">- STUDENTS</div>
            <table class="data-table"><thead><tr><th>REG NO</th><th>NAME</th><th></th></tr></thead><tbody id="staffStudentsBody"><tr><td colspan="3" style="text-align:center;padding:20px;">No students assigned</td></tr></tbody></table>
            <div class="total-students" id="totalCount">TOTAL STUDENTS: 0</div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
var currentStaff='';
$(function(){$('.search-select').select2({placeholder:'Select Staff',allowClear:true});loadAllStudents();});
function selectStaff(){currentStaff=$('#staffSelect').val();if(!currentStaff){alert('Select staff');return;}$('#staffHeader').text(currentStaff+' - STUDENTS');loadStaffStudents();}
function searchStudents(){var k=$('#searchInput').val();if(!currentStaff&&!k){loadAllStudents();return;}$.get('/api/staff-students-search',{search:k,staff:currentStaff,exclude_staff:currentStaff},function(r){if(r.success)renderAllStudents(r.data);});}
function loadAllStudents(){$.get('/api/staff-students-all',{exclude_staff:currentStaff||'NONE'},function(r){if(r.success)renderAllStudents(r.data);});}
function loadStaffStudents(){if(!currentStaff)return;$.get('/api/staff-students-list',{staff:currentStaff},function(r){if(r.success)renderStaffStudents(r.data);});}
function renderAllStudents(d){var t=$('#allStudentsBody');t.empty();if(!d.length){t.html('<tr><td colspan="4" style="text-align:center;padding:20px;">No students</td></tr>');return;}d.forEach(function(s){t.append('<tr><td><input value="'+s.reg+'" readonly></td><td><input value="'+s.sname+'" readonly></td><td><input value="'+s.class+'" readonly></td><td><button class="btn-add" onclick="addStudent(\''+s.reg+'\')">Add+</button></td></tr>');});}
function renderStaffStudents(d){var t=$('#staffStudentsBody');t.empty();$('#totalCount').text('TOTAL STUDENTS: '+d.length);if(!d.length){t.html('<tr><td colspan="3" style="text-align:center;padding:20px;">No students</td></tr>');return;}d.forEach(function(s){t.append('<tr><td><input value="'+s.reg+'" readonly></td><td><input value="'+s.sname+'" readonly></td><td><button class="btn-remove" onclick="removeStudent(\''+s.reg+'\')">Remove</button></td></tr>');});}
function addStudent(r){if(!currentStaff){alert('Select staff first');return;}$.post('/api/staff-students-add',{reg:r,staff:currentStaff,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success){loadAllStudents();loadStaffStudents();}else alert(d.message);});}
function removeStudent(r){if(!confirm('Remove?'))return;$.post('/api/staff-students-remove',{reg:r,_token:$('meta[name="csrf-token"]').attr('content')},function(d){if(d.success){loadAllStudents();loadStaffStudents();}else alert(d.message);});}
</script>
@endsection
