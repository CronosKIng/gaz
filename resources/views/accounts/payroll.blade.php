<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst(str_replace('_', ' ', $page)) }} - Glorious Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; background: #f4f6f9; }
        .dashboard-wrapper { min-height: 100vh; display: flex; }
        .dashboard-main { flex: 1; padding: 0; min-height: 100vh; height: 100vh; display: flex; flex-direction: column; background: #f4f6f9; overflow: hidden; }
        .page-content { display: flex; flex-direction: column; height: 100%; padding: 20px 30px; background: #f4f6f9; overflow-y: auto; justify-content: center; align-items: center; }
        .page-content .page-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px; border-bottom: 1px solid #e9ecef; width: 100%; flex-shrink: 0; flex-wrap: wrap; gap: 10px; }
        .page-content .page-header h2 { font-weight: 700; color: #1a2332; margin: 0; font-size: 1.3rem; }
        .page-content .page-header .btn-back { background: #6c757d; color: #fff; padding: 6px 16px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.85rem; }
        .page-content .page-header .btn-back:hover { background: #5a6268; color: #fff; }
        .page-content .content-body { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; }
        .page-content .content-body .placeholder { text-align: center; color: #888; }
        .page-content .content-body .placeholder h3 { color: #D9A52A; margin-bottom: 10px; }
        @media (max-width: 768px) { .page-content { padding: 10px 15px; } }
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
                <li><a href="#" class="menu-parent" data-target="sub-students">Students <span class="menu-toggle">▾</span></a>
                    <ul class="sub-menu" id="sub-students">
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
                    <ul class="sub-menu" id="sub-staff">
                        <li><a href="/staff/new">New Staff</a></li>
                        <li><a href="/staff/profile">Staff Profile</a></li>
                    </ul>
                </li>
                <li><a href="#" class="menu-parent active" data-target="sub-accounts">Accounts <span class="menu-toggle open">▾</span></a>
                    <ul class="sub-menu open" id="sub-accounts">
                        <li><a href="/receive-payment" class="active">Receive Payment</a></li>
                        <li><a href="/register-staff-students">Register Staff Students</a></li>
                        <li><a href="/staff-student-payment">Staff Students Payment</a></li>
                        <li><a href="/pay-bills">Pay Bills</a></li>
                        <li><a href="/set-budget">Set Budget</a></li>
                        <li><a href="/zra-receipts">ZRA Receipts</a></li>
                        <li><a href="/payroll">Payroll</a></li>
                    </ul>
                </li>
                <li><a href="#" class="menu-parent" data-target="sub-academic">Academic <span class="menu-toggle">▾</span></a>
                    <ul class="sub-menu" id="sub-academic">
                        <li><a href="/attendance">Student Attendance</a></li>
                        <li><a href="/print-class-results">Print Class Results</a></li>
                        <li><a href="/print-students-results">Print Students Results</a></li>
                        <li><a href="/class-list">Class List</a></li>
                    </ul>
                </li>
                <li><a href="#" class="menu-parent" data-target="sub-reports">Reports <span class="menu-toggle">▾</span></a>
                    <ul class="sub-menu" id="sub-reports">
                        <li><a href="/revenue-summary">Revenue Summary</a></li>
                        <li><a href="/revenue-statement">Revenue Statement</a></li>
                        <li><a href="/debtors-statement">Debtors Statement</a></li>
                        <li><a href="/income-expenses">Income and Expenses</a></li>
                        <li><a href="/tax-return">Tax Return</a></li>
                    </ul>
                </li>
            </ul>
        </aside>

        <main class="dashboard-main" id="mainContent">
            <div class="page-content">
                <div class="page-header">
                    <h2>{{ ucfirst(str_replace('_', ' ', $page)) }}</h2>
                    <a href="/dashboard" class="btn-back">Back to Dashboard</a>
                </div>
                <div class="content-body">
                    <div class="placeholder">
                        <h3>{{ ucfirst(str_replace('_', ' ', $page)) }}</h3>
                        <p>This page is under development. Will be connected to database.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
</body>
</html>
