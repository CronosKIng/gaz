<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Glorious Academy')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/topmenu.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
</head>
<body>
    @php $user = auth()->user(); @endphp
    
    <div class="top-header">
        <div class="logo-section">
            <img src="https://i.ibb.co/gMfmMQ2B/logo.png" alt="Logo">
            <span class="school-name">GLORIOUS ACADEMY</span>
        </div>
        <div class="stats">
            <span class="stat-box">Students: <span id="hdrStudents">...</span></span>
            <span class="stat-box">Attendance: <span id="hdrAttendance">...</span></span>
            <span class="stat-box">Paid: <span id="hdrRevenue">...</span></span>
        </div>
        <div class="user-section">
            <span class="user-name">{{ $user->name ?? 'Staff' }}</span>
            <a href="/change-password" class="icon-link">Change Password</a>
            <form action="{{ route('staff.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
    
    <div class="nav-bar">
        <div class="dropdown">
            <button class="dropbtn">Students</button>
            <div class="dropdown-content">
                <a href="/students/new">New Student</a>
                <a href="/students/existing">Existing Student</a>
                <a href="/students/enroll">Enroll Student</a>
                <a href="/students">All Student</a>
                <a href="/students/staff">View Staff Student</a>
                <a href="/students/delete">Delete Student</a>
                <a href="/students/change-class">Change Class</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Staff</button>
            <div class="dropdown-content">
                <a href="/staff/new">New Staff</a>
                <a href="/staff/profile">Staff Profile</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Accounts</button>
            <div class="dropdown-content">
                <a href="/payment/receive">Receive Payment</a>
                <a href="/register-staff-students">Register Staff Students</a>
                <a href="/staff-student-payment">Staff Students Payment</a>
                <a href="/pay-bills">Pay Bills</a>
                <a href="/set-budget">Set Budget</a>
                <a href="/zra-receipts">ZRA Receipts</a>
                <a href="#">Payroll</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Academic</button>
            <div class="dropdown-content">
                <a href="/attendance">Student Attendance</a>
                <a href="/print-class-results">Print Class Results</a>
                <a href="/print-students-results">Print Students Results</a>
                <a href="/class-list">Class List</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Reports</button>
            <div class="dropdown-content">
                <a href="/revenue-summary">Revenue Summary</a>
                <a href="/revenue-statement">Revenue Statement</a>
                <a href="/debtors-statement">Debtors Statement</a>
                <a href="/income-expenses">Income and Expenses</a>
                <a href="/tax-return">Tax Return</a>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        @yield('content')
    </div>
    
    <div class="footer">
        Version: 10 | 2026 &nbsp;|&nbsp; Help: 0786139664 &nbsp;|&nbsp; Glorious Academy
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.get('/api/dashboard-stats', function(r) { 
            if (r.success) { $('#hdrStudents').text(r.students||0); $('#hdrAttendance').text(r.attendance||0); }
        });
        $.get('/api/dashboard-revenue', function(r) { 
            if (r.success) { $('#hdrRevenue').text(r.revenue ? (r.revenue/1000000).toFixed(1)+'M' : '0'); }
        });
    </script>
    @yield('scripts')
</body>
</html>
