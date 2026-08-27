@extends('layouts.topmenu')
@section('title', 'Income Expenses')
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<style>
    .table-scroll { max-height: 400px; overflow-y: auto; }
    .table-scroll table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .table-scroll table thead { background: #D9A52A; color: #1a2332; position: sticky; top: 0; z-index: 10; }
    .table-scroll table thead th { padding: 8px 12px; text-align: left; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; }
    .table-scroll table tbody tr { border-bottom: 1px solid #f0f0f0; }
    .table-scroll table tbody tr:hover { background: #f8f9fa; }
    .table-scroll table tbody td { padding: 6px 12px; color: #444; }
    .card { border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.08); border: none; }
    .card-header { background: #1a2332; color: white; border-radius: 10px 10px 0 0; padding: 15px 20px; }
    .card-header h4 { margin: 0; font-weight: 600; }
    .card-body { padding: 20px; }
    .btn-done { background: #D9A52A; color: #1a2332; border: none; padding: 10px 30px; font-weight: 700; border-radius: 6px; cursor: pointer; }
    .btn-done:hover { background: #B8860B; color: #fff; }
    .btn-view { background: #17a2b8; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.75rem; cursor: pointer; }
    .btn-view:hover { background: #138496; }
    .btn-delete { background: #dc3545; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.75rem; cursor: pointer; }
    .btn-delete:hover { background: #c82333; }
    .badge-paid { background: #28a745; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; }
    .badge-pending { background: #ffc107; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; }
    .badge-gold { background: #D9A52A; color: #1a2332; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; }
    .loading-spinner { display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid #D9A52A; border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .alert-message { padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; display: none; }
    .alert-message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; display: block; }
    .alert-message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; display: block; }
    .alert-message.info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; display: block; }
    .staff-info { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #D9A52A; }
    .staff-info .label { font-weight: 600; color: #888; font-size: 0.8rem; }
    .staff-info .value { font-weight: 700; color: #1a2332; font-size: 1rem; }
    .select2-container--default .select2-selection--single { border: 2px solid #D9A52A !important; border-radius: 6px !important; height: 46px !important; padding: 5px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 34px !important; color: #1a2332 !important; font-weight: 600 !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px !important; }
    .section-title { color: #D9A52A; font-weight: 700; border-bottom: 2px solid #D9A52A; padding-bottom: 8px; margin-bottom: 15px; }
    .card-header-gold { background: #D9A52A !important; color: #1a2332 !important; }
    .card-header-gold h4 { color: #1a2332 !important; }
    @media (max-width: 768px) { .dashboard-body { padding: 10px 15px; } }
</style>
@endsection

@section('content')
<div class="dashboard-body" style="flex:1;overflow-y:auto;padding:15px 25px;background:#f4f6f9;">
    <div id="alertMessage" class="alert-message"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-gold"><h4>Select Staff</h4></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label style="font-weight:600;color:#D9A52A;font-size:0.9rem;">Staff Name</label>
                                <select id="staffSelect" class="form-control search" style="width:100%;border:2px solid #D9A52A;border-radius:6px;padding:10px 15px;"><option value="">Loading staff...</option></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label style="font-weight:600;color:#D9A52A;font-size:0.9rem;">&nbsp;</label><button id="viewStaffBtn" class="btn-done w-100">View Students</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-gold"><h4>Staff Students List</h4></div>
                <div class="card-body">
                    <div id="staffInfo" class="staff-info" style="display:none;">
                        <div class="row">
                            <div class="col-md-3"><div class="label">Staff Name</div><div class="value" id="staffNameDisplay">-</div></div>
                            <div class="col-md-3"><div class="label">Staff ID</div><div class="value" id="staffIdDisplay">-</div></div>
                            <div class="col-md-3"><div class="label">Total Students</div><div class="value" id="totalStudentsDisplay">0</div></div>
                            <div class="col-md-3"><div class="label">Status</div><div class="value" id="staffStatusDisplay">-</div></div>
                        </div>
                    </div>
                    <div id="loadingSpinner" style="text-align:center;padding:40px;display:none;"><div class="loading-spinner"></div><p style="margin-top:10px;color:#888;">Loading students...</p></div>
                    <div id="studentsContainer" style="display:none;">
                        <div class="table-scroll">
                            <table class="table table-bordered table-hover">
                                <thead><tr><th>REG NO</th><th>NAME</th><th>CLASS</th><th>LEVEL</th><th>GENDER</th><th>STATUS</th><th>ACTION</th></tr></thead>
                                <tbody id="studentsTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div id="emptyState" style="text-align:center;padding:40px;display:none;"><h4 style="color:#444;margin-bottom:10px;">No students found</h4><p style="color:#888;">Select a staff member to view their students</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(document).ready(function() { $('.search').select2({ placeholder: "Search staff...", allowClear: true, theme: "default" }); loadStaffList(); });
    function loadStaffList() {
        const select = $('#staffSelect');
        $.ajax({ url: '/api/staff/list', type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function(response) {
                if (response.success) { let options = '<option value="">Select Staff</option>'; if (response.data && response.data.length > 0) { response.data.forEach(function(staff) { let name = staff.fname + ' ' + (staff.lname || ''); options += '<option value="' + (staff.user || staff.id) + '">' + (staff.user || staff.id) + ' | ' + name + '</option>'; }); } else { options = '<option value="">No staff found</option>'; } select.html(options); select.trigger('change'); }
                else { select.html('<option value="">Error loading staff</option>'); showAlert('Error loading staff: ' + (response.message || 'Unknown error'), 'error'); }
            },
            error: function(xhr) { let errorMsg = 'Error loading staff. Please try again.'; if (xhr.responseJSON && xhr.responseJSON.message) { errorMsg = xhr.responseJSON.message; } select.html('<option value="">Error loading staff</option>'); showAlert(errorMsg, 'error'); }
        });
    }
    $('#viewStaffBtn').on('click', function() { const staffName = $('#staffSelect').val(); if (!staffName) { showAlert('Please select a staff member.', 'error'); return; } loadStaffStudents(staffName); });
    function loadStaffStudents(staffName) {
        const btn = $('#viewStaffBtn'); btn.prop('disabled', true); btn.html('<span class="loading-spinner"></span> Loading...');
        $('#loadingSpinner').show(); $('#studentsContainer').hide(); $('#emptyState').hide(); $('#staffInfo').hide(); hideAlert();
        $.ajax({ url: '/api/staff-students-list', type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, data: { staff: staffName, _token: '{{ csrf_token() }}' },
            success: function(response) {
                btn.prop('disabled', false); btn.html('View Students'); $('#loadingSpinner').hide();
                if (response.success) {
                    if (response.success) { $('#staffNameDisplay').text(response.staff.fname + ' ' + (response.staff.lname || '') || response.staff.user || '-'); $('#staffIdDisplay').text(response.staff.id || '-'); $('#staffStatusDisplay').text(response.staff.status || 'Active'); $('#staffInfo').show(); }
                    if (response.data && response.data.length > 0) { renderStudents(response.data); $('#totalStudentsDisplay').text(response.data.length); $('#studentsContainer').show(); }
                    else { $('#emptyState').show(); $('#totalStudentsDisplay').text('0'); }
                } else { showAlert(response.message || 'Error loading students', 'error'); $('#emptyState').show(); }
            },
            error: function(xhr) { btn.prop('disabled', false); btn.html('View Students'); $('#loadingSpinner').hide(); let errorMsg = 'Error loading students. Please try again.'; if (xhr.responseJSON && xhr.responseJSON.message) { errorMsg = xhr.responseJSON.message; } showAlert(errorMsg, 'error'); $('#emptyState').show(); }
        });
    }
    function renderStudents(students) {
        const tbody = $('#studentsTableBody'); tbody.empty();
        students.forEach(function(student) { let statusBadge = student.status === 'active' || student.active === 'YES' ? '<span class="badge-paid">Active</span>' : '<span class="badge-pending">Inactive</span>'; let row = '<tr><td><strong>' + (student.reg || '-') + '</strong></td><td>' + (student.sname || student.name || '-') + '</td><td>' + (student.class || '-') + '</td><td>' + (student.level || '-') + '</td><td>' + (student.gender || '-') + '</td><td>' + statusBadge + '</td><td><button onclick="viewStudent(\'' + student.reg + '\')" class="btn-view">View</button> <button onclick="deleteStudent(\'' + student.reg + '\')" class="btn-delete">Delete</button></td></tr>'; tbody.append(row); });
    }
    function viewStudent(reg) { window.location.href = '/std_pay_acc?reg=' + reg; }
    function deleteStudent(reg) { if (!confirm('Are you sure you want to delete student ' + reg + '?')) { return; } $.ajax({ url: '/api/student/delete', type: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, data: { reg: reg, _token: '{{ csrf_token() }}' }, success: function(response) { if (response.success) { showAlert(response.message, 'success'); const staffName = $('#staffSelect').val(); if (staffName) { loadStaffStudents(staffName); } } else { showAlert(response.message || 'Error deleting student', 'error'); } }, error: function(xhr) { let errorMsg = 'Error deleting student. Please try again.'; if (xhr.responseJSON && xhr.responseJSON.message) { errorMsg = xhr.responseJSON.message; } showAlert(errorMsg, 'error'); } }); }
    function showAlert(message, type) { const alert = $('#alertMessage'); alert.text(message); alert.removeClass('success error info').addClass(type).show(); setTimeout(function() { alert.hide(); }, 5000); }
    function hideAlert() { $('#alertMessage').hide().removeClass('success error info'); }
</script>
@endsection
