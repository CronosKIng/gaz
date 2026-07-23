<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income and Expenses - Glorious Academy</title>
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
        .filter-row { display: flex; gap: 10px; align-items: flex-end; margin: 10px 0; flex-shrink: 0; }
        .filter-row select { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-search { background: #D9A52A; color: #fff; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; }
        .btn-print { background: #17a2b8; color: #fff; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; margin-left: 5px; }
        .section-title { font-weight: 700; color: #336699; margin: 15px 0 5px 0; font-size: 0.95rem; }
        .table-wrapper { flex: 1; overflow: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 15px; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 15px; }
        .data-table th { background: gray; color: #fff; padding: 10px; }
        .data-table td { padding: 6px; border: 1px solid #ddd; }
        .data-table tr:nth-child(even) { background: #f2f2f2; }
        .total-row td { background: gray !important; color: #fff !important; font-weight: 700; }
        .net-row td { background: #1a2332 !important; color: #fff !important; font-weight: 700; font-size: 1rem; }
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
                <li><a href="#" class="menu-parent active" data-target="sub-reports">Reports <span class="menu-toggle open">▾</span></a><ul class="sub-menu open" id="sub-reports"><li><a href="/revenue-summary">Revenue Summary</a></li><li><a href="/revenue-statement">Revenue Statement</a></li><li><a href="/debtors-statement">Debtors Statement</a></li><li><a href="/income-expenses" class="active">Income and Expenses</a></li><li><a href="/tax-return">Tax Return</a></li></ul></li>
            </ul>
        </aside>

        <main class="dashboard-main" id="mainContent">
            <div class="content">
                <div class="content-header"><h2>INCOME AND EXPENDITURE STATEMENT</h2><a href="/dashboard" class="btn-back">Back</a></div>
                <div class="filter-row">
                    <select id="yearSelect"><option value="">Select Year</option><option value="2026">2026</option><option value="2025">2025</option><option value="2024">2024</option><option value="2023">2023</option></select>
                    <button class="btn-search" onclick="loadData()">Search</button>
                    <button class="btn-print" onclick="printData()">Print</button>
                </div>
                <div class="table-wrapper" id="printArea">
                    <div class="section-title">INCOME STATEMENT</div>
                    <table class="data-table"><thead><tr><th>SNO</th><th>PAYMENT CATEGORY</th><th>AMOUNT</th></tr></thead><tbody id="incomeBody"><tr><td colspan="3" style="text-align:center;padding:20px;">Select year</td></tr></tbody></table>
                    <div class="section-title">EXPENDITURE STATEMENT</div>
                    <table class="data-table"><thead><tr><th>SNO</th><th>PAYMENT CATEGORY</th><th>AMOUNT</th></tr></thead><tbody id="expenseBody"><tr><td colspan="3" style="text-align:center;padding:20px;">Select year</td></tr></tbody></table>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadData() {
            var year = $('#yearSelect').val(); if (!year) { alert('Select year'); return; }
            $('#incomeBody,#expenseBody').html('<tr><td colspan="3" style="text-align:center;padding:20px;">Loading...</td></tr>');
            $.get('/api/income-expenses', { year: year }, function(r) {
                if (r.success) {
                    var ib = $('#incomeBody'); ib.empty(); var itotal = 0;
                    r.income.forEach(function(b, i) { itotal += parseInt(b.amount||0); ib.append('<tr><td>'+(i+1)+'</td><td>'+b.category+'</td><td>'+Number(b.amount||0).toLocaleString()+'</td></tr>'); });
                    ib.append('<tr class="total-row"><td>TOTAL</td><td></td><td>'+Number(itotal).toLocaleString()+'</td></tr>');
                    var eb = $('#expenseBody'); eb.empty(); var etotal = 0;
                    r.expense.forEach(function(b, i) { etotal += parseInt(b.cost||0); eb.append('<tr><td>'+(i+1)+'</td><td>'+b.part+'</td><td>'+Number(b.cost||0).toLocaleString()+'</td></tr>'); });
                    eb.append('<tr class="total-row"><td>TOTAL</td><td></td><td>'+Number(etotal).toLocaleString()+'</td></tr>');
                    eb.append('<tr class="net-row"><td>NET BALANCE</td><td></td><td>'+Number(itotal - etotal).toLocaleString()+'</td></tr>');
                }
            });
        }
        function printData() { var w=window.open(''); w.document.write('<!DOCTYPE html><html><head><title>Income & Expenses</title><style>.data-table{width:100%;border-collapse:collapse;font-size:11px}.data-table th{background:gray;color:#fff;padding:8px}.data-table td{padding:5px;border:1px solid #ddd}.total-row td{background:gray!important;color:#fff!important}.net-row td{background:#1a2332!important;color:#fff!important}@media print{body{margin:0}}</style></head><body>'+document.getElementById('printArea').innerHTML+'<script>window.onload=function(){window.print()}<\/script></body></html>'); w.document.close(); }
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.menu-parent').forEach(function(p) { p.addEventListener('click', function(e) { e.preventDefault(); var s = document.getElementById(this.getAttribute('data-target')); var t = this.querySelector('.menu-toggle'); if (s) { s.classList.toggle('open'); if (t) t.classList.toggle('open'); } }); });
            var sb=document.getElementById('sidebar'),mn=document.getElementById('mainContent'),cb=document.getElementById('sidebarToggleTop'),ob=document.getElementById('sidebarOpenBtn');
            function cs(){sb.classList.add('collapsed');mn.classList.add('expanded');ob.classList.add('visible');}
            function os(){sb.classList.remove('collapsed');mn.classList.remove('expanded');ob.classList.remove('visible');}
            cb.addEventListener('click',cs);ob.addEventListener('click',os);
            if(window.innerWidth<=992)cs();else ob.classList.remove('visible');
            window.addEventListener('resize',function(){if(window.innerWidth<=992){if(!sb.classList.contains('collapsed'))cs();}else os();});
        });
    </script>
</body>
</html>
