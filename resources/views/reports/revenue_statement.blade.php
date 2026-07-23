<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Statement - Glorious Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; background: #f4f6f9; }
        .dashboard-wrapper { min-height: 100vh; display: flex; }
        .dashboard-main { flex: 1; padding: 0; min-height: 100vh; height: 100vh; display: flex; flex-direction: column; background: #f4f6f9; overflow: hidden; }
        .content { display: flex; flex-direction: column; height: 100%; padding: 10px 20px; background: #f4f6f9; overflow: hidden; }
        .content-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid #e9ecef; flex-shrink: 0; }
        .content-header h2 { font-weight: 700; color: #336699; margin: 0; font-size: 1.1rem; }
        .btn-back { background: #6c757d; color: #fff; padding: 5px 14px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.8rem; }
        .filter-row { display: flex; gap: 10px; align-items: flex-end; margin: 10px 0; flex-shrink: 0; flex-wrap: wrap; }
        .filter-row select { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-search { background: #D9A52A; color: #fff; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; }
        .btn-print { background: #17a2b8; color: #fff; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; margin-left: 5px; }
        .table-wrapper { flex: 1; overflow: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 15px; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
        .data-table th { background: gray; color: #fff; padding: 10px; }
        .data-table td { padding: 6px; border: 1px solid #ddd; }
        .data-table tr:nth-child(even) { background: #f2f2f2; }
        .total-row td { background: gray !important; color: #fff !important; font-weight: 700; }
        @media (max-width: 768px) { .content { padding: 10px; } }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <button class="sidebar-open-btn" id="sidebarOpenBtn">━</button>
        <aside class="dashboard-sidebar" id="sidebar">
            <div class="sidebar-header"><img src="https://i.ibb.co/gMfmMQ2B/logo.png"><h5>Glorious Academy</h5><p>School Management System</p><button class="sidebar-toggle-top" id="sidebarToggleTop">━</button></div>
            <ul class="sidebar-menu">
                <li><a href="#" class="menu-parent" data-target="sub-students">Students <span class="menu-toggle">▾</span></a><ul class="sub-menu" id="sub-students"><li><a href="/students/new">New Student</a></li><li><a href="/students/existing">Existing Student</a></li><li><a href="/students/enroll">Enroll Student</a></li><li><a href="/students">All Student</a></li><li><a href="/students/staff">View Staff Student</a></li><li><a href="/students/delete">Delete Student</a></li><li><a href="/students/change-class">Change Class</a></li></ul></li>
                <li><a href="#" class="menu-parent" data-target="sub-staff">Staff <span class="menu-toggle">▾</span></a><ul class="sub-menu" id="sub-staff"><li><a href="/staff/new">New Staff</a></li><li><a href="/staff/profile">Staff Profile</a></li></ul></li>
                <li><a href="#" class="menu-parent" data-target="sub-accounts">Accounts <span class="menu-toggle">▾</span></a><ul class="sub-menu" id="sub-accounts"><li><a href="/receive-payment">Receive Payment</a></li><li><a href="/register-staff-students">Register Staff Students</a></li><li><a href="/staff-student-payment">Staff Students Payment</a></li><li><a href="/pay-bills">Pay Bills</a></li><li><a href="/set-budget">Set Budget</a></li><li><a href="/zra-receipts">ZRA Receipts</a></li><li><a href="/payroll">Payroll</a></li></ul></li>
                <li><a href="#" class="menu-parent" data-target="sub-academic">Academic <span class="menu-toggle">▾</span></a><ul class="sub-menu" id="sub-academic"><li><a href="/attendance">Student Attendance</a></li><li><a href="/print-class-results">Print Class Results</a></li><li><a href="/print-students-results">Print Students Results</a></li><li><a href="/class-list">Class List</a></li></ul></li>
                <li><a href="#" class="menu-parent active" data-target="sub-reports">Reports <span class="menu-toggle open">▾</span></a><ul class="sub-menu open" id="sub-reports"><li><a href="/revenue-summary">Revenue Summary</a></li><li><a href="/revenue-statement" class="active">Revenue Statement</a></li><li><a href="/debtors-statement">Debtors Statement</a></li><li><a href="/income-expenses">Income and Expenses</a></li><li><a href="/tax-return">Tax Return</a></li></ul></li>
            </ul>
        </aside>

        <main class="dashboard-main" id="mainContent">
            <div class="content">
                <div class="content-header"><h2>REVENUE STATEMENT</h2><a href="/dashboard" class="btn-back">Back</a></div>
                <div class="filter-row">
                    <select id="feeSelect">
                        <option value="">Select Fee</option>
                        <option value="Registration">Registration Fee</option>
                        <option value="Term">School Fee</option>
                        <option value="Transport">Transport Fee</option>
                        <option value="Hostel">Hostel Fee</option>
                        <option value="First term">First term</option>
                        <option value="Second term">Second term</option>
                    </select>
                    <select id="yearSelect">
                        <option value="">Year</option>
                        <option value="2026">2026</option><option value="2025">2025</option><option value="2024">2024</option><option value="2023">2023</option>
                    </select>
                    <button class="btn-search" onclick="loadStatement()">Search</button>
                    <button class="btn-print" onclick="printStatement()">Print</button>
                </div>
                <div class="table-wrapper" id="printArea">
                    <table class="data-table">
                        <thead><tr><th>SNO</th><th>REG</th><th>NAME</th><th>LEVEL</th><th>CLASS</th><th>YEAR</th><th>FEE NAME</th><th>AMOUNT</th><th>RECEIVED</th><th>REMAINING</th></tr></thead>
                        <tbody id="stmtBody"><tr><td colspan="10" style="text-align:center;padding:20px;">Select filters</td></tr></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadStatement() {
            var fee = $('#feeSelect').val(), year = $('#yearSelect').val();
            if (!fee || !year) { alert('Select fee and year'); return; }
            $('#stmtBody').html('<tr><td colspan="10" style="text-align:center;padding:20px;">Loading...</td></tr>');
            $.ajax({
                url: '/api/revenue-statement',
                type: 'GET',
                data: { fee: fee, year: year },
                success: function(r) {
                    var tbody = $('#stmtBody'); tbody.empty();
                    if (r.success && r.data.length > 0) {
                        var tf=0, tp=0, tb=0, sno=1;
                        r.data.forEach(function(b) {
                            var amt = parseInt(b.amount||0), bal = parseInt(b.balance||0), paid = amt - bal;
                            tf += amt; tp += paid; tb += bal;
                            tbody.append('<tr><td>'+sno+'</td><td>'+b.reg+'</td><td>'+b.sname+'</td><td>'+b.level+'</td><td>'+b.class+'</td><td>'+b.year+'</td><td>'+b.category+'</td><td>'+Number(amt).toLocaleString()+'</td><td>'+Number(paid).toLocaleString()+'</td><td>'+Number(bal).toLocaleString()+'</td></tr>');
                            sno++;
                        });
                        tbody.append('<tr class="total-row"><td></td><td></td><td></td><td></td><td></td><td></td><td>TOTAL</td><td>'+Number(tf).toLocaleString()+'</td><td>'+Number(tp).toLocaleString()+'</td><td>'+Number(tb).toLocaleString()+'</td></tr>');
                    } else { tbody.html('<tr><td colspan="10" style="text-align:center;padding:20px;">No data</td></tr>'); }
                }
            });
        }
        function printStatement() {
            var w = window.open('', '_blank');
            w.document.write('<!DOCTYPE html><html><head><title>Revenue Statement</title><style>.data-table{width:100%;border-collapse:collapse;font-size:11px}.data-table th{background:gray;color:#fff;padding:8px}.data-table td{padding:5px;border:1px solid #ddd}.total-row td{background:gray!important;color:#fff!important}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>');
            w.document.close();
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.menu-parent').forEach(function(p) { p.addEventListener('click', function(e) { e.preventDefault(); var s = document.getElementById(this.getAttribute('data-target')); var t = this.querySelector('.menu-toggle'); if (s) { s.classList.toggle('open'); if (t) t.classList.toggle('open'); } }); });
            var sb = document.getElementById('sidebar'), mn = document.getElementById('mainContent'), cb = document.getElementById('sidebarToggleTop'), ob = document.getElementById('sidebarOpenBtn');
            function cs() { sb.classList.add('collapsed'); mn.classList.add('expanded'); ob.classList.add('visible'); }
            function os() { sb.classList.remove('collapsed'); mn.classList.remove('expanded'); ob.classList.remove('visible'); }
            cb.addEventListener('click', cs); ob.addEventListener('click', os);
            if (window.innerWidth <= 992) cs(); else ob.classList.remove('visible');
            window.addEventListener('resize', function() { if (window.innerWidth <= 992) { if (!sb.classList.contains('collapsed')) cs(); } else os(); });
        });
    </script>
</body>
</html>
