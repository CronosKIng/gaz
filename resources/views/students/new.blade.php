@extends('layouts.topmenu')
@section('title', 'New Student')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">New Student Registration</h2>
        <a href="/students" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>
    <div style="max-width:900px;margin:15px auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
        <div id="alertMessage" style="padding:10px 15px;border-radius:6px;margin-bottom:15px;display:none;"></div>
        <form onsubmit="return false;">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Student Name *</label><input type="text" id="sname" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;" required></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Place of Birth</label><input type="text" id="address" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Date of Birth</label><input type="date" id="dob" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Gender</label><select id="gender" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"><option value="">Select</option><option>male</option><option>female</option></select></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Religion</label><input type="text" id="religion" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Nationality</label><input type="text" id="national" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Former School</label><input type="text" id="school" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Shehia</label><input type="text" id="shehia" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Ward</label><input type="text" id="ward" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Level</label><select id="level" onchange="loadClasses()" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"><option value="">Select</option><option>Nursery</option><option>Primary</option><option>Secondary</option><option>Advance</option><option>Repeater</option></select></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Class</label><select id="class" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"><option value="">Select Level first</option></select></div>
                <div><label style="font-weight:600;color:#444;font-size:0.8rem;">Year</label><select id="ryear" style="width:100%;padding:8px;border:2px solid #e9ecef;border-radius:6px;"><option value="">Select</option><option>2026</option><option>2025</option><option>2024</option></select></div>
            </div>
            <h5 style="color:#B8860B;margin-top:15px;">Parent/Guardian</h5>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:10px;">
                <input type="text" id="pgname" placeholder="Full Name" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
                <input type="text" id="pgaddress" placeholder="Address" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
                <input type="text" id="pgmob" placeholder="Mobile" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
                <input type="text" id="relation" placeholder="Relationship" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
            </div>
            <h5 style="color:#B8860B;margin-top:15px;">Sponsor</h5>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:10px;">
                <input type="text" id="spname" placeholder="Full Name" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
                <input type="text" id="spaddress" placeholder="Address" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
                <input type="text" id="spmob" placeholder="Mobile" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
                <input type="text" id="accupation" placeholder="Occupation" style="padding:8px;border:2px solid #e9ecef;border-radius:6px;">
            </div>
            <div style="text-align:center;margin-top:20px;"><button onclick="submitForm()" style="background:#D9A52A;color:#fff;border:none;padding:10px 30px;border-radius:6px;font-weight:600;cursor:pointer;">Register Student</button></div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function loadClasses(){var l=$('#level').val();if(!l){$('#class').html('<option value="">Select Level first</option>');return;}$.get('/api/new-student/classes?level='+l,function(r){var c=$('#class');c.empty();c.append('<option value="">Select Class</option>');if(r.success)r.data.forEach(function(x){c.append('<option value="'+x.class+'">'+x.class+' | '+x.level+'</option>');});});}
function submitForm(){var d={sname:$('#sname').val(),address:$('#address').val(),dob:$('#dob').val(),gender:$('#gender').val(),religion:$('#religion').val(),national:$('#national').val(),school:$('#school').val(),shehia:$('#shehia').val(),ward:$('#ward').val(),level:$('#level').val(),class:$('#class').val(),ryear:$('#ryear').val(),pgname:$('#pgname').val(),pgaddress:$('#pgaddress').val(),pgmob:$('#pgmob').val(),relation:$('#relation').val(),spname:$('#spname').val(),spaddress:$('#spaddress').val(),spmob:$('#spmob').val(),accupation:$('#accupation').val(),_token:$('meta[name="csrf-token"]').attr('content')};if(!d.sname){alert('Enter student name');return;}$.post('/api/new-student',d,function(r){if(r.success){alert(r.message);location.reload();}else alert(r.message);}).fail(function(x){alert(x.responseJSON?.message||'Error');});}
</script>
@endsection
