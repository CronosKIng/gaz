@extends('layouts.topmenu')
@section('title', 'Change Password')
@section('content')
<div style="display:flex;justify-content:center;align-items:center;height:80vh;">
    <div style="background:#fff;padding:30px;border-radius:12px;max-width:420px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,0.2);text-align:center;">
        <img src="https://i.ibb.co/gMfmMQ2B/logo.png" style="width:70px;height:70px;border-radius:50%;margin-bottom:10px;">
        <h4 style="color:#dc3545;">Change Password</h4>
        <p style="color:#888;font-size:0.85rem;">{{ auth()->user()->name ?? 'Staff' }}, set a new password.</p>
        <input type="password" id="newPassword" placeholder="New Password" style="width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px;">
        <input type="password" id="confirmPassword" placeholder="Confirm Password" style="width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px;">
        <button onclick="changePassword()" style="background:#D9A52A;color:#fff;border:none;padding:10px 30px;border-radius:6px;font-weight:600;cursor:pointer;margin-top:10px;width:100%;">Update Password</button>
        <p id="passError" style="color:#dc3545;margin-top:8px;display:none;"></p>
        <p id="passSuccess" style="color:#28a745;margin-top:8px;display:none;"></p>
    </div>
</div>
@endsection
@section('scripts')
<script>
function changePassword() {
    var p1=$('#newPassword').val(),p2=$('#confirmPassword').val();
    $('#passError,#passSuccess').hide();
    if(!p1||!p2){$('#passError').text('Fill all fields').show();return;}
    if(p1!==p2){$('#passError').text('Passwords do not match').show();return;}
    if(p1.length<6){$('#passError').text('Minimum 6 characters').show();return;}
    $('button').prop('disabled',true).text('Updating...');
    $.post('/api/change-password',{password:p1,_token:$('meta[name="csrf-token"]').attr('content')},function(r){
        $('button').prop('disabled',false).text('Update Password');
        if(r.success){$('#passSuccess').text('Password changed! Redirecting...').show();setTimeout(function(){window.location.href='/dashboard';},1500);}
        else{$('#passError').text(r.message).show();}
    });
}
</script>
@endsection
