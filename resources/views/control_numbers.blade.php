<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Numbers - Glorious Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; background: #f4f6f9; }
        
        .dashboard-wrapper { min-height: 100vh; display: flex; }
        .dashboard-main { flex: 1; padding: 0; min-height: 100vh; height: 100vh; display: flex; flex-direction: column; background: #f4f6f9; overflow: hidden; }
        
        .content { display: flex; flex-direction: column; height: 100%; padding: 10px 20px; background: #f4f6f9; overflow: hidden; }
        .content-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid #e9ecef; flex-shrink: 0; flex-wrap: wrap; gap: 10px; }
        .content-header h2 { font-weight: 700; color: #1a2332; margin: 0; font-size: 1.1rem; }
        .content-header .btn-back { background: #6c757d; color: #fff; padding: 5px 14px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.8rem; }
        .content-header .btn-back:hover { background: #5a6268; color: #fff; }
        
        .balance-box { background: #dc3545; color: #fff; padding: 8px 20px; border-radius: 6px; text-align: center; font-size: 1.2rem; font-weight: 700; flex-shrink: 0; margin: 8px 0; }
        .balance-box span { font-size: 1.5rem; }
        
        .student-info { background: #ffffff; border-radius: 8px; padding: 10px 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); flex-shrink: 0; display: flex; flex-wrap: wrap; gap: 5px 20px; }
        .student-info .info-item { display: flex; padding: 2px 0; }
        .student-info .info-item .label { font-weight: 600; color: #666; font-size: 0.75rem; width: 70px; flex-shrink: 0; }
        .student-info .info-item .value { color: #1a2332; font-weight: 500; font-size: 0.75rem; }
        
        .card-container {
            flex: 1;
            display: flex;
            gap: 20px;
            margin-top: 15px;
            min-height: 0;
            flex-wrap: wrap;
        }
        
        .card-panel {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            padding: 20px;
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .card-panel h5 { font-weight: 700; color: #1a2332; margin-bottom: 15px; flex-shrink: 0; }
        
        .table-scroll {
            flex: 1;
            overflow: auto;
            min-height: 0;
        }
        
        .table-scroll table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        
        .table-scroll table thead {
            background: #1a2332;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table-scroll table thead th {
            padding: 10px 12px;
            font-size: 12px;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }
        
        .table-scroll table tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
        }
        
        .table-scroll table tbody tr:hover {
            background: #f5f5f5;
        }
        
        .btn-action { background: #D9A52A; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.7rem; cursor: pointer; }
        .btn-action:hover { background: #B8860B; }
        .btn-danger-sm { background: #dc3545; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.7rem; cursor: pointer; }
        .btn-danger-sm:hover { background: #c82333; }
        .btn-success-sm { background: #28a745; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.7rem; cursor: pointer; }
        .btn-success-sm:hover { background: #218838; }
        
        .status-badge { padding: 2px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 600; }
        .status-badge.paid { background: #d4edda; color: #155724; }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.canceled { background: #f8d7da; color: #721c24; }
        
        .empty-state { text-align: center; padding: 30px; color: #888; font-size: 0.9rem; }
        .loading-spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid #f3f3f3; border-top: 2px solid #D9A52A; border-radius: 50%; animation: spin 1s linear infinite; vertical-align: middle; margin-right: 5px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        @media (max-width: 768px) {
            .content { padding: 10px; }
            .card-container { flex-direction: column; }
            .card-panel { min-width: auto; }
            .student-info { display: block; }
            .balance-box { font-size: 18px; }
            table { min-width: 500px; }
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
                        <li><a href="/receive-payment">Receive Payment</a></li>
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
            <div class="content">
                <div class="content-header">
                    <h2>Control Numbers</h2>
                    <a href="/payment/account" class="btn-back">Back</a>
                </div>

                <div class="balance-box">
                    BALANCE: <span>{{ number_format($totalBalance ?? 0, 0) }}</span> TZS
                </div>

                <div class="student-info">
                    <div class="info-item">
                        <span class="label">Reg No</span>
                        <span class="value"><strong>{{ $student->reg ?? '-' }}</strong></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Name</span>
                        <span class="value">{{ $student->sname ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Class</span>
                        <span class="value">{{ $enrollment->class ?? $student->class ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Level</span>
                        <span class="value">{{ $enrollment->level ?? $student->level ?? '-' }}</span>
                    </div>
                </div>

                <div class="card-container">
                    <!-- Generate Control Number Panel -->
                    <div class="card-panel" style="flex: 0 0 350px;">
                        <h5>Generate Control Number</h5>
                        <div class="table-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories ?? [] as $cat)
                                    <tr>
                                        <td>{{ $cat->category ?? '-' }}</td>
                                        <td>{{ number_format($cat->balance ?? 0, 0) }}</td>
                                        <td>
                                            <button class="btn-success-sm" onclick="generateControl('{{ $cat->id }}')">Generate</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3"><div class="empty-state">No pending payments</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Control Numbers List Panel -->
                    <div class="card-panel">
                        <h5>Control Numbers</h5>
                        <div class="table-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Control Number</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($controlNumbers ?? [] as $cn)
                                    <tr>
                                        <td><strong>{{ $cn->cnamba ?? '-' }}</strong></td>
                                        <td>{{ $cn->category ?? '-' }}</td>
                                        <td>{{ number_format($cn->amount ?? 0, 0) }}</td>
                                        <td>
                                            <span class="status-badge {{ strtolower($cn->status ?? 'pending') == 'canceled' ? 'canceled' : 'pending' }}">
                                                {{ $cn->status ?? 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(($cn->status ?? '') != 'Canceled')
                                                <button class="btn-danger-sm" onclick="deleteControl('{{ $cn->cnamba }}')">Delete</button>
                                            @else
                                                <span style="color:#dc3545;">Canceled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5"><div class="empty-state">No control numbers found</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function generateControl(catid) {
            if (!catid) return;
            if (!confirm('Generate control number for this payment?')) return;

            $.ajax({
                url: '/api/control-number/generate',
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: {
                    catid: catid,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Control number generated: ' + response.control_number);
                        window.location.reload();
                    } else {
                        alert(response.message || 'Error generating control number');
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Error generating control number');
                }
            });
        }

        function deleteControl(cnamba) {
            if (!cnamba) return;
            if (!confirm('Delete control number ' + cnamba + '?')) return;

            $.ajax({
                url: '/api/control-number/delete',
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: {
                    cnamba: cnamba,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        window.location.reload();
                    } else {
                        alert(response.message || 'Error deleting control number');
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Error deleting control number');
                }
            });
        }

        // Sidebar toggle
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
