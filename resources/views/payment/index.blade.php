@extends('layouts.topmenu')
@section('title', 'Student Payment')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Payment - Glorious Academy</title>
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
            background: #f4f6f9; 
            overflow: hidden; 
        }
        
        .payment-content { 
            display: flex;
            flex-direction: column;
            height: 100%; 
            padding: 15px 25px; 
            background: #f4f6f9; 
            overflow-y: auto; 
        }
        
        .payment-content .payment-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding-bottom: 15px; 
            border-bottom: 1px solid #e9ecef; 
            flex-shrink: 0; 
            flex-wrap: wrap; 
            gap: 10px; 
        }
        .payment-content .payment-header h2 { 
            font-weight: 700; 
            color: #1a2332; 
            margin: 0; 
            font-size: 1.3rem; 
        }
        .payment-content .payment-header .btn-back { 
            background: #6c757d; 
            color: #fff; 
            padding: 6px 16px; 
            border-radius: 6px; 
            font-weight: 600; 
            text-decoration: none; 
            font-size: 0.85rem; 
        }
        .payment-content .payment-header .btn-back:hover { 
            background: #5a6268; 
            color: #fff; 
        }
        
        .payment-info {
            background: #ffffff;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-top: 15px;
            flex-shrink: 0;
        }
        .payment-info .info-row {
            display: flex;
            padding: 5px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .payment-info .info-row:last-child {
            border-bottom: none;
        }
        .payment-info .info-row .label {
            font-weight: 600;
            color: #666;
            width: 150px;
            flex-shrink: 0;
            font-size: 0.85rem;
        }
        .payment-info .info-row .value {
            color: #1a2332;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .payment-table-wrapper {
            flex: 1;
            overflow: auto;
            margin-top: 15px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 15px;
            min-height: 0;
        }
        .payment-table-wrapper table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        .payment-table-wrapper table thead {
            background: #1a2332;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .payment-table-wrapper table thead th {
            padding: 8px 12px;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .payment-table-wrapper table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }
        .payment-table-wrapper table tbody tr:hover {
            background: #f8f9fa;
        }
        .payment-table-wrapper table tbody td {
            padding: 6px 12px;
            color: #444;
        }
        .status-badge {
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .status-badge.paid { background: #d4edda; color: #155724; }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.unpaid { background: #f8d7da; color: #721c24; }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #888;
        }
        
        @media (max-width: 576px) {
            .payment-content { padding: 10px 15px; }
            .payment-info .info-row .label { width: 100px; font-size: 0.75rem; }
            .payment-info .info-row .value { font-size: 0.75rem; }
            .payment-table-wrapper table { font-size: 0.75rem; min-width: 500px; }
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
                <li><a href="#" class="menu-parent active" data-target="sub-students">Students <span class="menu-toggle open">▾</span></a>
                    <ul class="sub-menu open" id="sub-students">
                        <li><a href="/students/new">New Student</a></li>
                        <li><a href="/students/existing">Existing Student</a></li>
                        <li><a href="/students/enroll">Enroll Student</a></li>
                        <li><a href="/students" class="active">All Student</a></li>
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
            <div class="payment-content">
                <div class="payment-header">
                    <h2>Student Payment</h2>
                    <a href="/students/staff" class="btn-back">Back to Staff Students</a>
                </div>

                <!-- Student Info -->
                <div class="payment-info">
                    <div class="info-row">
                        <span class="label">Registration Number</span>
                        <span class="value"><strong>{{ $student->reg ?? '-' }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Student Name</span>
                        <span class="value">{{ $student->sname ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Class</span>
                        <span class="value">{{ $student->class ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Level</span>
                        <span class="value">{{ $student->level ?? '-' }}</span>
                    </div>
                </div>

                <!-- Payment Table -->
                <div class="payment-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Year</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $payment->category ?? '-' }}</td>
                                    <td>{{ number_format($payment->amount ?? 0, 0) }}</td>
                                    <td>{{ number_format($payment->balance ?? 0, 0) }}</td>
                                    <td>{{ $payment->year ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge {{ ($payment->balance ?? 0) == 0 ? 'paid' : 'pending' }}">
                                            {{ ($payment->balance ?? 0) == 0 ? 'Paid' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6"><div class="empty-state">No payment records found</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection