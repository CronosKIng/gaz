@extends('layouts.topmenu')
@section('title', 'New Staff')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">New Staff Registration</h2>
        <a href="/dashboard" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="max-width:700px;margin:20px auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
        <div id="alertMessage" style="padding:10px 15px;border-radius:6px;margin-bottom:15px;display:none;"></div>
        <form onsubmit="return false;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div><label style="font-weight:600;color:#444;font-size:0.85rem;">Username *</label><input type="text" id="user" style="width:100%;padding:8px 12px;border:2px solid #e9ecef;border-radius:6px;" required></div>
                <div><label style="font-weight:600;color:#444;font-size:0.85rem;">Full Name *</label><input type="text" id="name" style="width:100%;padding:8px 12px;border:2px solid #e9ecef;border-radius:6px;" required></div>
                <div><label style="font-weight:600;color:#444;font-size:0.85rem;">Contact *</label><input type="text" id="contact" style="width:100%;padding:8px 12px;border:2px solid #e9ecef;border-radius:6px;" required></div>
                <div><label style="font-weight:600;color:#444;font-size:0.85rem;">Status *</label><select id="status" style="width:100%;padding:8px 12px;border:2px solid #e9ecef;border-radius:6px;" required><option value="">Select</option><option>Admin</option><option>Registrar</option><option>Teacher</option><option>Accountant</option><option>Head master</option><option>Class teacher</option><option>Super Admin</option></select></div>
                <div><label style="font-weight:600;color:#444;font-size:0.85rem;">Password *</label><input type="password" id="password" style="width:100%;padding:8px 12px;border:2px solid #e9ecef;border-radius:6px;" required minlength="6"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.85rem;">Confirm Password *</label><input type="password" id="password_confirmation" style="width:100%;padding:8px 12px;border:2px solid #e9ecef;border-radius:6px;" required></div>
            </div>
            <div style="text-align:center;margin-top:20px;"><button type="button" id="submitBtn" onclick="registerStaff()" style="background:#D9A52A;color:#fff;border:none;padding:10px 30px;border-radius:6px;font-weight:600;cursor:pointer;">Register Staff</button></div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function registerStaff(){
    var d={user:$('#user').val(),name:$('#name').val(),contact:$('#contact').val(),status:$('#status').val(),password:$('#password').val(),password_confirmation:$('#password_confirmation').val(),_token:$('meta[name="csrf-token"]').attr('content')};
    if(!d.user||!d.name||!d.contact||!d.status||!d.password){showAlert('Fill all fields','error');return;}
    if(d.password!==d.password_confirmation){showAlert('Passwords do not match','error');return;}
    $('#submitBtn').prop('disabled',true).text('Saving...');
    $.post('/api/staff/store',d,function(r){$('#submitBtn').prop('disabled',false).text('Register Staff');if(r.success){showAlert(r.message,'success');$('#user,#name,#contact,#password,#password_confirmation').val('');$('#status').val('');}else showAlert(r.message||'Error','error');}).fail(function(x){$('#submitBtn').prop('disabled',false).text('Register Staff');showAlert(x.responseJSON?.message||'Error','error');});
}
function showAlert(m,t){var a=$('#alertMessage');a.text(m).removeClass().addClass(t=='success'?'success':'error').css({background:t=='success'?'#d4edda':'#f8d7da',color:t=='success'?'#155724':'#721c24',border:'1px solid '+(t=='success'?'#c3e6cb':'#f5c6cb'),display:'block'});}
</script>
@endsection
