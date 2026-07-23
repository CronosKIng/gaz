<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Glorious Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; background: #f4f6f9; }
        
        .dashboard-wrapper { min-height: 100vh; display: flex; }
        .dashboard-main { 
            flex: 1; 
            padding: 0; 
            min-height: 100vh; 
            height: 100vh; 
            display: flex; 
            flex-direction: column; 
            background: #ffffff; 
            overflow: hidden; 
        }
        
        .profile-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0;
            background: #ffffff;
        }
        
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 25px;
            border-bottom: 1px solid #e9ecef;
            flex-shrink: 0;
            flex-wrap: wrap;
            gap: 10px;
            background: #ffffff;
        }
        .profile-header h2 {
            font-weight: 700;
            color: #1a2332;
            margin: 0;
            font-size: 1.3rem;
        }
        .profile-header .btn-back {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .profile-header .btn-back:hover {
            background: #5a6268;
            color: #fff;
        }
        
        .profile-body {
            flex: 1;
            overflow-y: auto;
            padding: 15px 20px;
        }
        
        .profile-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 15px;
        }
        .profile-card .profile-avatar {
            text-align: center;
            margin-bottom: 10px;
        }
        .profile-card .profile-avatar .avatar-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #D9A52A;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            font-weight: 700;
            margin: 0 auto;
        }
        .profile-card .profile-name {
            text-align: center;
            font-weight: 700;
            font-size: 1.3rem;
            color: #1a2332;
        }
        .profile-card .profile-class {
            text-align: center;
            color: #D9A52A;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .profile-card .balance-box {
            background: #dc3545;
            color: #fff;
            padding: 8px 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .info-row {
            display: flex;
            padding: 6px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row .label {
            font-weight: 600;
            color: #666;
            width: 160px;
            flex-shrink: 0;
            font-size: 0.9rem;
        }
        .info-row .value {
            color: #1a2332;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .profile-tabs {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .profile-tabs .tab-btn {
            background: #e9ecef;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        .profile-tabs .tab-btn:hover {
            background: #D9A52A;
            color: #fff;
        }
        .profile-tabs .tab-btn.active {
            background: #D9A52A;
            color: #fff;
        }
        
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        
        .table-scroll {
            max-height: 400px;
            overflow-y: auto;
        }
        .table-scroll table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        .table-scroll table thead {
            background: #1a2332;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table-scroll table thead th {
            padding: 8px 12px;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        .table-scroll table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }
        .table-scroll table tbody td {
            padding: 6px 12px;
            color: #444;
        }
        
        .exam-btn {
            background: #fd7e14;
            color: #fff;
            border: none;
            padding: 3px 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.7rem;
            cursor: pointer;
            margin: 2px;
        }
        .exam-btn:hover {
            background: #e06b0a;
            color: #fff;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #D9A52A;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-text {
            color: #dc3545;
            text-align: center;
            padding: 20px;
        }
        
        .stats-box {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .stats-box .stat-item {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 6px;
            flex: 1;
            min-width: 80px;
            text-align: center;
        }
        .stats-box .stat-item .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a2332;
        }
        .stats-box .stat-item .stat-label {
            font-size: 0.8rem;
            color: #888;
        }
        .stats-box .stat-item.present .stat-number { color: #28a745; }
        .stats-box .stat-item.absent .stat-number { color: #dc3545; }
        .stats-box .stat-item.reason .stat-number { color: #fd7e14; }
        
        .year-selector {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .year-selector select {
            padding: 6px 12px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 0.85rem;
            background: #fff;
        }
        .year-selector select:focus {
            border-color: #D9A52A;
            outline: none;
        }
        .year-selector .btn-load {
            background: #D9A52A;
            color: #fff;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
        }
        .year-selector .btn-load:hover {
            background: #B8860B;
        }
        
        .payment-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: #dc3545;
            margin-top: 10px;
            text-align: right;
        }
        
        .grade-a { color: #28a745; font-weight: 700; }
        .grade-b { color: #17a2b8; font-weight: 700; }
        .grade-c { color: #fd7e14; font-weight: 700; }
        .grade-d { color: #dc3545; font-weight: 700; }
        .grade-f { color: #6c757d; font-weight: 700; }
        
        .exam-tag {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .exam-tag.first-mdt { background: #e3f2fd; color: #0d47a1; }
        .exam-tag.first-term { background: #e8f5e9; color: #1b5e20; }
        .exam-tag.second-mdt { background: #fff3e0; color: #e65100; }
        .exam-tag.annual { background: #fce4ec; color: #880e4f; }
        .exam-tag.third-term { background: #f3e5f5; color: #4a148c; }
        .exam-tag.second-term { background: #fff8e1; color: #f57f17; }
        .exam-tag.final-exam { background: #efebe9; color: #4e342e; }
        
        @media (max-width: 768px) {
            .profile-body { padding: 10px 12px; }
            .profile-header { padding: 10px 15px; flex-direction: column; align-items: stretch; }
            .profile-header .btn-back { text-align: center; }
            .info-row { flex-direction: column; gap: 3px; }
            .info-row .label { width: 100%; }
            .profile-tabs .tab-btn { flex: 1; text-align: center; font-size: 0.75rem; padding: 6px 10px; }
            .year-selector { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <button class="sidebar-open-btn" id="sidebarOpenBtn">━</button>
        <aside class="dashboard-sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="https://i.ibb.co/gMfmMQ2B/logo.png" alt="Glorious Academy">
                <h5>Glorious Academy</h5>
                <p>School Management System</p>
                <button class="sidebar-toggle-top" id="sidebarToggleTop">━</button>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="#" class="menu-parent active" data-target="sub-students">
                        Students <span class="menu-toggle open">▾</span>
                    </a>
                    <ul class="sub-menu open" id="sub-students">
                        <li><a href="/students/new">New Student</a></li>
                        <li><a href="/students/existing">Existing Student</a></li>
                        <li><a href="/students/enroll">Enroll Student</a></li>
                        <li><a href="/students">All Student</a></li>
                        <li><a href="/students/staff">View Staff Student</a></li>
                        <li><a href="/students/delete">Delete Student</a></li>
                        <li><a href="/students/change-class">Change Class</a></li>
                    </ul>
                </li>
                <li><a href="#" class="menu-parent" data-target="sub-staff">Staff <span class="menu-toggle">▾</span></a>
                    <ul class="sub-menu" id="sub-staff"><li><a href="#">New Staff</a></li><li><a href="#">Staff Profile</a></li></ul>
                </li>
                <li><a href="#" class="menu-parent" data-target="sub-accounts">Accounts <span class="menu-toggle">▾</span></a>
                    <ul class="sub-menu" id="sub-accounts">
                        <li><a href="#">Receive Payment</a></li><li><a href="#">Register Staff Students</a></li>
                        <li><a href="#">Staff Students Payment</a></li><li><a href="#">Pay Bills</a></li>
                        <li><a href="#">Set Budget</a></li><li><a href="#">ZRA Receipts</a></li><li><a href="#">Payroll</a></li>
                    </ul>
                </li>
                <li><a href="#" class="menu-parent" data-target="sub-academic">Academic <span class="menu-toggle">▾</span></a>
                    <ul class="sub-menu" id="sub-academic">
                        <li><a href="#">Student Attendance</a></li><li><a href="#">Print Class Results</a></li>
                        <li><a href="#">Print Students Results</a></li><li><a href="#">Class List</a></li>
                    </ul>
                </li>
                <li><a href="#" class="menu-parent" data-target="sub-reports">Reports <span class="menu-toggle">▾</span></a>
                    <ul class="sub-menu" id="sub-reports">
                        <li><a href="#">Revenue Summary</a></li><li><a href="#">Revenue Statement</a></li>
                        <li><a href="#">Debtors Statement</a></li><li><a href="#">Income and Expenses</a></li><li><a href="#">Tax Return</a></li>
                    </ul>
                </li>
            </ul>
        </aside>

        <main class="dashboard-main" id="mainContent">
            <div class="profile-content">
                <div class="profile-header">
                    <h2>Student Profile</h2>
                    <a href="/students" class="btn-back">Back to List</a>
                </div>
                
                <div class="profile-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="profile-card">
                                <div class="profile-avatar">
                                    <div class="avatar-icon">
                                        {{ strtoupper(substr($student->sname ?? 'S', 0, 1)) }}
                                    </div>
                                </div>
                                <div class="profile-name">{{ strtoupper($student->sname ?? '-') }}</div>
                                <div class="profile-class">{{ strtoupper($enrollment->class ?? '-') }}</div>
                                <hr>
                                <div class="balance-box">
                                    Balance: {{ number_format($balance ?? 0) }} TZS
                                </div>
                                <hr>
                                <div style="font-size: 0.85rem;">
                                    <p><strong>Last Seen:</strong> {{ $attendance->date ?? 'Not recorded' }}</p>
                                    <p><strong>Class Teacher:</strong> {{ strtoupper($classTeacher->name ?? 'Not assigned') }}</p>
                                    <p><strong>Teacher Mobile:</strong> {{ $classTeacher->contact ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-8">
                            <div class="profile-card">
                                <div class="profile-tabs">
                                    <button class="tab-btn active" onclick="showTab('info')">Info</button>
                                    <button class="tab-btn" onclick="showTab('payment')">Payment</button>
                                    <button class="tab-btn" onclick="showTab('exam')">Examination</button>
                                    <button class="tab-btn" onclick="showTab('attendance')">Attendance</button>
                                </div>
                                
                                <div id="tab-info" class="tab-content active">
                                    <h5 style="color: #D9A52A; font-weight: 700; margin-bottom: 10px;">Student Information</h5>
                                    <div class="info-row"><div class="label">Registration Number</div><div class="value"><strong>{{ strtoupper($student->reg ?? '-') }}</strong></div></div>
                                    <div class="info-row"><div class="label">Student Name</div><div class="value">{{ strtoupper($student->sname ?? '-') }}</div></div>
                                    <div class="info-row"><div class="label">Gender</div><div class="value">{{ strtoupper($student->gender ?? '-') }}</div></div>
                                    <div class="info-row"><div class="label">Date of Birth</div><div class="value">{{ $student->dob ?? '-' }}</div></div>
                                    <div class="info-row"><div class="label">Place of Birth</div><div class="value">{{ strtoupper($student->address ?? '-') }}</div></div>
                                    <div class="info-row"><div class="label">Registration Date</div><div class="value">{{ $student->date ?? '-' }}</div></div>
                                    <div class="info-row"><div class="label">Class</div><div class="value">{{ strtoupper($enrollment->class ?? '-') }}</div></div>
                                    <div class="info-row"><div class="label">Level</div><div class="value">{{ strtoupper($enrollment->level ?? '-') }}</div></div>
                                    
                                    <h5 class="mt-3" style="color: #D9A52A; font-weight: 700; margin-bottom: 10px;">Parent/Guardian</h5>
                                    <div class="info-row"><div class="label">Parent Name</div><div class="value">{{ strtoupper($student->pgname ?? '-') }}</div></div>
                                    <div class="info-row"><div class="label">Parent Mobile</div><div class="value">{{ $student->pgmob ?? '-' }}</div></div>
                                    <div class="info-row"><div class="label">Relationship</div><div class="value">{{ strtoupper($student->relation ?? '-') }}</div></div>
                                    
                                    <h5 class="mt-3" style="color: #D9A52A; font-weight: 700; margin-bottom: 10px;">Sponsor</h5>
                                    <div class="info-row"><div class="label">Sponsor Name</div><div class="value">{{ strtoupper($student->spname ?? '-') }}</div></div>
                                    <div class="info-row"><div class="label">Sponsor Mobile</div><div class="value">{{ $student->spmob ?? '-' }}</div></div>
                                </div>
                                
                                <div id="tab-payment" class="tab-content">
                                    <div class="year-selector">
                                        <label for="paymentYearSelect" style="font-weight:600;color:#444;">Select Year:</label>
                                        <select id="paymentYearSelect">
                                            <option value="">All Years</option>
                                        </select>
                                        <button onclick="loadPayment()" class="btn-load">Load</button>
                                    </div>
                                    <div id="paymentLoading" style="text-align:center; padding:20px; display:none;">
                                        <div class="loading-spinner"></div> Loading...
                                    </div>
                                    <div id="paymentContent" style="display:none;"></div>
                                    <div id="paymentError" class="error-text" style="display:none;">No payment records found.</div>
                                </div>
                                
                                <div id="tab-exam" class="tab-content">
                                    <div class="year-selector">
                                        <label for="examYearSelect" style="font-weight:600;color:#444;">Select Year:</label>
                                        <select id="examYearSelect">
                                            <option value="">All Years</option>
                                        </select>
                                        <label for="examTypeSelect" style="font-weight:600;color:#444;margin-left:10px;">Exam Type:</label>
                                        <select id="examTypeSelect">
                                            <option value="">All Types</option>
                                        </select>
                                        <button onclick="loadExaminations()" class="btn-load">Load</button>
                                    </div>
                                    <div id="examLoading" style="text-align:center; padding:20px; display:none;">
                                        <div class="loading-spinner"></div> Loading...
                                    </div>
                                    <div id="examContent" style="display:none;"></div>
                                    <div id="examError" class="error-text" style="display:none;">No examination records found.</div>
                                </div>
                                
                                <div id="tab-attendance" class="tab-content">
                                    <div class="year-selector">
                                        <label for="attendanceYearSelect" style="font-weight:600;color:#444;">Select Year:</label>
                                        <select id="attendanceYearSelect">
                                            <option value="">All Years</option>
                                        </select>
                                        <button onclick="loadAttendance()" class="btn-load">Load</button>
                                    </div>
                                    <div id="attendanceLoading" style="text-align:center; padding:20px; display:none;">
                                        <div class="loading-spinner"></div> Loading...
                                    </div>
                                    <div id="attendanceContent" style="display:none;"></div>
                                    <div id="attendanceError" class="error-text" style="display:none;">No attendance records found.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const studentReg = '{{ $student->reg ?? '' }}';
        const paymentYears = @json($paymentYears ?? []);
        const attendanceYears = @json($attendanceYears ?? []);
        const examYears = @json($examYears ?? []);
        const examTypes = @json($examTypes ?? []);

        function showTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            
            document.getElementById('tab-' + tab).classList.add('active');
            document.querySelector(`.tab-btn[onclick*="${tab}"]`).classList.add('active');
            
            if (tab === 'payment') loadPaymentYears();
            else if (tab === 'exam') loadExamFilters();
            else if (tab === 'attendance') loadAttendanceYears();
        }

        // ===== PAYMENT =====
        function loadPaymentYears() {
            const select = document.getElementById('paymentYearSelect');
            
            if (paymentYears && paymentYears.length > 0) {
                let options = '<option value="">All Years</option>';
                paymentYears.forEach(function(year) {
                    options += `<option value="${year}">${year}</option>`;
                });
                select.innerHTML = options;
                if (paymentYears.length > 0) {
                    select.value = paymentYears[0];
                    loadPayment();
                }
            } else {
                select.innerHTML = '<option value="">No years available</option>';
                document.getElementById('paymentContent').style.display = 'none';
                document.getElementById('paymentError').textContent = 'No payment records found.';
                document.getElementById('paymentError').style.display = 'block';
            }
        }

        function loadPayment() {
            const year = document.getElementById('paymentYearSelect').value;
            
            $('#paymentLoading').show();
            $('#paymentContent').hide();
            $('#paymentError').hide();
            
            $.ajax({
                url: '/api/student/payment?reg=' + studentReg + '&year=' + year,
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                success: function(response) {
                    $('#paymentLoading').hide();
                    if (response.success && response.data) {
                        renderPayment(response.data);
                        $('#paymentContent').show();
                    } else {
                        $('#paymentError').text('No payment records found.');
                        $('#paymentError').show();
                    }
                },
                error: function() {
                    $('#paymentLoading').hide();
                    $('#paymentError').text('Error loading payment data.');
                    $('#paymentError').show();
                }
            });
        }

        function renderPayment(data) {
            let html = '';
            
            html += `<h6 style="font-weight:700;color:#1a2332;margin-bottom:5px;">Bills</h6>`;
            if (data.bills && data.bills.length > 0) {
                html += `<div class="table-scroll"><table><thead><tr><th>Item</th><th>Year</th><th>Fee</th><th>Paid</th><th>Balance</th></tr></thead><tbody>`;
                let totalFee = 0, totalPaid = 0, totalBalance = 0;
                data.bills.forEach(function(item) {
                    const paid = item.amount - item.balance;
                    totalFee += parseInt(item.amount);
                    totalPaid += parseInt(paid);
                    totalBalance += parseInt(item.balance);
                    html += `<tr><td>${item.category}</td><td>${item.year}</td><td>${item.amount}</td><td>${paid}</td><td>${item.balance}</td></tr>`;
                });
                html += `<tr style="font-weight:700;background:#f8f9fa;"><td colspan="2">TOTAL</td><td>${totalFee}</td><td>${totalPaid}</td><td>${totalBalance}</td></tr>`;
                html += `</tbody></table></div>`;
            } else {
                html += `<p class="text-muted">No pending bills.</p>`;
            }
            
            html += `<h6 style="font-weight:700;color:#1a2332;margin-top:15px;margin-bottom:5px;">Receipts</h6>`;
            if (data.receipts && data.receipts.length > 0) {
                html += `<div class="table-scroll"><table><thead><tr><th>Item</th><th>Invoice</th><th>Date</th><th>Year</th><th>Amount</th></tr></thead><tbody>`;
                let totalReceipts = 0;
                data.receipts.forEach(function(item) {
                    totalReceipts += parseInt(item.amount);
                    html += `<tr><td>${item.category}</td><td>${item.invo}</td><td>${item.date}</td><td>${item.year}</td><td>${item.amount}</td></tr>`;
                });
                html += `<tr style="font-weight:700;background:#f8f9fa;"><td colspan="4">TOTAL</td><td>${totalReceipts}</td></tr>`;
                html += `</tbody></table></div>`;
            } else {
                html += `<p class="text-muted">No receipts found.</p>`;
            }
            
            html += `<div class="payment-total">Total Balance: ${data.total_balance || 0} TZS</div>`;
            $('#paymentContent').html(html);
        }

        // ===== EXAMINATION =====
        function loadExamFilters() {
            // Load years
            const yearSelect = document.getElementById('examYearSelect');
            if (examYears && examYears.length > 0) {
                let options = '<option value="">All Years</option>';
                examYears.forEach(function(year) {
                    options += `<option value="${year}">${year}</option>`;
                });
                yearSelect.innerHTML = options;
                if (examYears.length > 0) {
                    yearSelect.value = examYears[0];
                }
            } else {
                yearSelect.innerHTML = '<option value="">No years available</option>';
            }
            
            // Load exam types
            const typeSelect = document.getElementById('examTypeSelect');
            if (examTypes && examTypes.length > 0) {
                let options = '<option value="">All Types</option>';
                examTypes.forEach(function(type) {
                    options += `<option value="${type}">${type}</option>`;
                });
                typeSelect.innerHTML = options;
            } else {
                typeSelect.innerHTML = '<option value="">No types available</option>';
            }
            
            loadExaminations();
        }

        function loadExaminations() {
            const year = document.getElementById('examYearSelect').value;
            const examType = document.getElementById('examTypeSelect').value;
            
            $('#examLoading').show();
            $('#examContent').hide();
            $('#examError').hide();
            
            $.ajax({
                url: '/api/student/examinations?reg=' + studentReg + '&year=' + year + '&exam_type=' + encodeURIComponent(examType),
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                success: function(response) {
                    $('#examLoading').hide();
                    if (response.success && response.data && response.data.results && response.data.results.length > 0) {
                        renderExams(response.data.results);
                        $('#examContent').show();
                    } else {
                        $('#examError').text('No examination records found.');
                        $('#examError').show();
                    }
                },
                error: function() {
                    $('#examLoading').hide();
                    $('#examError').text('Error loading examination data.');
                    $('#examError').show();
                }
            });
        }

        function renderExams(results) {
            let html = `<div class="table-scroll"><table><thead><tr><th>Subject</th><th>Marks</th><th>Grade</th><th>Exam Type</th><th>Class</th><th>Year</th></tr></thead><tbody>`;
            
            results.forEach(function(exam) {
                const gradeClass = getGradeClass(exam.grade);
                const examTagClass = getExamTagClass(exam.examtp);
                
                html += `<tr>
                    <td>${exam.subject || '-'}</td>
                    <td>${exam.marks || '-'}</td>
                    <td><span class="${gradeClass}">${exam.grade || '-'}</span></td>
                    <td><span class="exam-tag ${examTagClass}">${exam.examtp || '-'}</span></td>
                    <td>${exam.class || '-'}</td>
                    <td>${exam.acyear || '-'}</td>
                </tr>`;
            });
            
            html += `</tbody></table></div>`;
            $('#examContent').html(html);
        }

        function getGradeClass(grade) {
            if (!grade) return '';
            const g = grade.toUpperCase();
            if (g >= 'A' && g <= 'A+') return 'grade-a';
            if (g >= 'B' && g <= 'B+') return 'grade-b';
            if (g >= 'C' && g <= 'C+') return 'grade-c';
            if (g >= 'D' && g <= 'D+') return 'grade-d';
            return 'grade-f';
        }

        function getExamTagClass(examType) {
            if (!examType) return '';
            const type = examType.toLowerCase();
            if (type.includes('first middle')) return 'first-mdt';
            if (type.includes('first term')) return 'first-term';
            if (type.includes('second middle')) return 'second-mdt';
            if (type.includes('annual')) return 'annual';
            if (type.includes('third')) return 'third-term';
            if (type.includes('second term')) return 'second-term';
            if (type.includes('final')) return 'final-exam';
            return '';
        }

        // ===== ATTENDANCE =====
        function loadAttendanceYears() {
            const select = document.getElementById('attendanceYearSelect');
            
            if (attendanceYears && attendanceYears.length > 0) {
                let options = '<option value="">All Years</option>';
                attendanceYears.forEach(function(year) {
                    options += `<option value="${year}">${year}</option>`;
                });
                select.innerHTML = options;
                if (attendanceYears.length > 0) {
                    select.value = attendanceYears[0];
                    loadAttendance();
                }
            } else {
                select.innerHTML = '<option value="">No years available</option>';
                document.getElementById('attendanceContent').style.display = 'none';
                document.getElementById('attendanceError').textContent = 'No attendance records found.';
                document.getElementById('attendanceError').style.display = 'block';
            }
        }

        function loadAttendance() {
            const year = document.getElementById('attendanceYearSelect').value;
            
            $('#attendanceLoading').show();
            $('#attendanceContent').hide();
            $('#attendanceError').hide();
            
            $.ajax({
                url: '/api/student/attendance?reg=' + studentReg + '&year=' + year,
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                success: function(response) {
                    $('#attendanceLoading').hide();
                    if (response.success && response.data) {
                        renderAttendance(response.data);
                        $('#attendanceContent').show();
                    } else {
                        $('#attendanceError').text('No attendance records found.');
                        $('#attendanceError').show();
                    }
                },
                error: function() {
                    $('#attendanceLoading').hide();
                    $('#attendanceError').text('Error loading attendance data.');
                    $('#attendanceError').show();
                }
            });
        }

        function renderAttendance(data) {
            let html = `
                <div class="stats-box">
                    <div class="stat-item present">
                        <div class="stat-number">${data.present || 0}</div>
                        <div class="stat-label">Present</div>
                    </div>
                    <div class="stat-item absent">
                        <div class="stat-number">${data.absent || 0}</div>
                        <div class="stat-label">Absent</div>
                    </div>
                    <div class="stat-item reason">
                        <div class="stat-number">${data.reason || 0}</div>
                        <div class="stat-label">Reason</div>
                    </div>
                </div>
                <div style="font-size:0.85rem; color:#888; margin-bottom:10px;">
                    Year: ${data.year || 'All Years'}
                </div>
            `;
            
            if (data.records && data.records.length > 0) {
                html += `<div class="table-scroll"><table><thead><tr><th>Date</th><th>Class</th><th>Status</th></tr></thead><tbody>`;
                data.records.forEach(function(record) {
                    html += `<tr><td>${record.date}</td><td>${record.class}</td><td>${record.status}</td></tr>`;
                });
                html += `</tbody></table></div>`;
            } else {
                html += `<p class="text-muted">No attendance records found.</p>`;
            }
            
            $('#attendanceContent').html(html);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var menuParents = document.querySelectorAll('.menu-parent');
            menuParents.forEach(function(parent) {
                parent.addEventListener('click', function(e) {
                    e.preventDefault();
                    var targetId = this.getAttribute('data-target');
                    var subMenu = document.getElementById(targetId);
                    var toggle = this.querySelector('.menu-toggle');
                    if (subMenu) { 
                        subMenu.classList.toggle('open'); 
                        if (toggle) toggle.classList.toggle('open'); 
                    }
                });
            });
            var sidebar = document.getElementById('sidebar');
            var main = document.getElementById('mainContent');
            var closeBtn = document.getElementById('sidebarToggleTop');
            var openBtn = document.getElementById('sidebarOpenBtn');
            function closeSidebar() { 
                sidebar.classList.add('collapsed'); 
                main.classList.add('expanded'); 
                openBtn.classList.add('visible'); 
            }
            function openSidebar() { 
                sidebar.classList.remove('collapsed'); 
                main.classList.remove('expanded'); 
                openBtn.classList.remove('visible'); 
            }
            closeBtn.addEventListener('click', closeSidebar);
            openBtn.addEventListener('click', openSidebar);
            if (window.innerWidth <= 992) closeSidebar(); 
            else openBtn.classList.remove('visible');
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 992) { 
                    if (!sidebar.classList.contains('collapsed')) closeSidebar(); 
                } else openSidebar();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
