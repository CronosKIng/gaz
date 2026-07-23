@extends('layouts.topmenu')
@section('title', 'Delete Student')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;flex-wrap:wrap;gap:10px;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Delete Student</h2>
        <div style="display:flex;gap:8px;">
            <a href="/students" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
            <input type="text" id="searchInput" placeholder="Search..." style="padding:6px 12px;border:2px solid #e9ecef;border-radius:6px;font-size:0.85rem;" onkeyup="if(event.key==='Enter')loadStudents()">
            <button onclick="loadStudents()" style="background:#D9A52A;color:#fff;border:none;padding:6px 16px;border-radius:6px;font-weight:600;cursor:pointer;">Search</button>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;overflow:auto;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:8px;">SID</th><th>Name</th><th>Reg No</th><th>Mobile</th><th>Class</th><th>Date</th><th>Action</th></tr></thead>
            <tbody id="deleteBody"><tr><td colspan="7" style="text-align:center;padding:20px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
<div id="delModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;justify-content:center;align-items:center;">
    <div style="background:#fff;padding:25px;border-radius:10px;max-width:400px;width:90%;">
        <h4>Confirm Delete</h4><p style="color:#666;">Enter action code: <strong>******</strong></p>
        <input type="text" id="actionCode" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;margin-bottom:10px;" maxlength="10">
        <p id="delError" style="color:#dc3545;display:none;"></p>
        <button onclick="confirmDelete()" style="background:#dc3545;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;">Delete</button>
        <button onclick="closeModal()" style="background:#6c757d;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;margin-left:10px;">Cancel</button>
    </div>
</div>
@endsection
@section('scripts')
<script>
var cur=null;
function loadStudents(){$.get('/api/delete-students',{search:$('#searchInput').val()},function(r){var t=$('#deleteBody');t.empty();if(r.success&&r.data.length>0){r.data.forEach(function(s){t.append('<tr><td>'+s.sid+'</td><td>'+s.sname+'</td><td><strong>'+s.reg+'</strong></td><td>'+s.pgmob+'</td><td>'+s.class+'</td><td>'+s.date+'</td><td><button onclick="openModal(\''+s.sid+'\',\''+s.reg+'\',\''+s.sname+'\',\''+s.class+'\',\''+s.pgmob+'\')" style="background:#dc3545;color:#fff;border:none;padding:4px 12px;border-radius:4px;cursor:pointer;">Delete</button></td></tr>');});}else{t.html('<tr><td colspan="7" style="text-align:center;padding:20px;">No students</td></tr>');}});}
function openModal(sid,reg,sname,cls,mob){cur={sid:sid,reg:reg,sname:sname,class:cls,mob:mob};$('#actionCode').val('');$('#delError').hide();$('#delModal').css('display','flex');}
function closeModal(){cur=null;$('#delModal').css('display','none');}
function confirmDelete(){var a=$('#actionCode').val().trim();if(!a){$('#delError').text('Enter code').show();return;}$.post('/api/delete-student',{sid:cur.sid,reg:cur.reg,sname:cur.sname,class:cur.class,mob:cur.mob,action_code:a,_token:$('meta[name="csrf-token"]').attr('content')},function(r){if(r.success){alert('Deleted!');closeModal();loadStudents();}else{$('#delError').text(r.message).show();}});}
$(function(){loadStudents();});
</script>
@endsection
