@extends('layouts.topmenu')
@section('title', 'Staff Profile')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Staff Profile</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="background:#fff;border-radius:12px;padding:15px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-top:15px;">
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:10px;">#</th><th>Username</th><th>Name</th><th>Contact</th><th>Status</th><th>Action</th></tr></thead>
            <tbody id="staffBody"><tr><td colspan="6" style="text-align:center;padding:30px;">Loading...</td></tr></tbody>
        </table>
    </div>
</div>

<div id="editModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;justify-content:center;align-items:center;">
    <div style="background:#fff;padding:25px;border-radius:10px;max-width:450px;width:90%;">
        <h4>Edit Staff</h4>
        <input type="hidden" id="edit_id">
        <div style="margin:8px 0;"><label style="font-weight:600;font-size:0.85rem;">Username</label><input type="text" id="edit_user" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;" readonly></div>
        <div style="margin:8px 0;"><label style="font-weight:600;font-size:0.85rem;">Name *</label><input type="text" id="edit_name" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
        <div style="margin:8px 0;"><label style="font-weight:600;font-size:0.85rem;">Contact *</label><input type="text" id="edit_contact" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
        <div style="margin:8px 0;"><label style="font-weight:600;font-size:0.85rem;">Status *</label><select id="edit_status" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"><option>Admin</option><option>Registrar</option><option>Teacher</option><option>Accountant</option><option>Head master</option><option>Class teacher</option><option>Super Admin</option></select></div>
        <div style="margin:8px 0;"><label style="font-weight:600;font-size:0.85rem;">New Password (leave blank)</label><input type="password" id="edit_password" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
        <div style="text-align:right;margin-top:15px;">
            <button onclick="closeModal()" style="background:#6c757d;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;">Cancel</button>
            <button onclick="saveStaff()" style="background:#D9A52A;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;margin-left:10px;">Save</button>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadStaff(){$.get('/api/staff/list',function(r){var t=$('#staffBody');t.empty();if(r.success&&r.data.length>0){r.data.forEach(function(s,i){t.append('<tr><td>'+(i+1)+'</td><td><strong>'+s.user+'</strong></td><td>'+s.name+'</td><td>'+s.contact+'</td><td>'+s.status+'</td><td><button onclick="openEdit('+s.id+')" style="background:#D9A52A;color:#fff;border:none;padding:4px 14px;border-radius:4px;cursor:pointer;">Edit</button></td></tr>');});}else{t.html('<tr><td colspan="6" style="text-align:center;padding:30px;">No staff found</td></tr>');}});}
function openEdit(id){$.get('/api/staff/'+id,function(r){if(r.success){var s=r.data;$('#edit_id').val(s.id);$('#edit_user').val(s.user);$('#edit_name').val(s.name);$('#edit_contact').val(s.contact);$('#edit_status').val(s.status);$('#edit_password').val('');$('#editModal').css('display','flex');}});}
function closeModal(){$('#editModal').css('display','none');}
function saveStaff(){var d={id:$('#edit_id').val(),name:$('#edit_name').val(),contact:$('#edit_contact').val(),status:$('#edit_status').val(),password:$('#edit_password').val(),_token:$('meta[name="csrf-token"]').attr('content')};$.post('/api/staff/update',d,function(r){if(r.success){alert(r.message);closeModal();loadStaff();}else alert(r.message);});}
$(function(){loadStaff();});
</script>
@endsection
