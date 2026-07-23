<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Account - Glorious Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; background: #f4f6f9; }
        
        .dashboard-wrapper { min-height: 100vh; display: flex; }
        .dashboard-main { flex: 1; padding: 0; min-height: 100vh; height: 100vh; display: flex; flex-direction: column; background: #f4f6f9; overflow: hidden; }
        
        .payment-content { display: flex; flex-direction: column; height: 100%; padding: 10px 20px; background: #f4f6f9; overflow: hidden; }
        .payment-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid #e9ecef; flex-shrink: 0; flex-wrap: wrap; gap: 10px; }
        .payment-header h2 { font-weight: 700; color: #1a2332; margin: 0; font-size: 1.1rem; }
        .payment-header .btn-back { background: #6c757d; color: #fff; padding: 5px 14px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.8rem; }
        .payment-header .btn-back:hover { background: #5a6268; color: #fff; }
        
        .balance-box { background: #dc3545; color: #fff; padding: 8px 20px; border-radius: 6px; text-align: center; font-size: 1.2rem; font-weight: 700; flex-shrink: 0; margin: 8px 0; }
        .balance-box span { font-size: 1.5rem; }
        
        .student-info { background: #ffffff; border-radius: 8px; padding: 10px 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); flex-shrink: 0; display: flex; flex-wrap: wrap; gap: 5px 20px; }
        .student-info .info-item { display: flex; padding: 2px 0; }
        .student-info .info-item .label { font-weight: 600; color: #666; font-size: 0.75rem; width: 70px; flex-shrink: 0; }
        .student-info .info-item .value { color: #1a2332; font-weight: 500; font-size: 0.75rem; }
        
        /* ===== TABS ===== */
        .tabs-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            margin-top: 15px;
            overflow: hidden;
            flex: 1;
            min-height: 0;
        }
        
        .tab-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
            flex-shrink: 0;
        }
        
        .tab-link {
            padding: 10px 18px;
            border: 1px solid #ddd;
            background: #ffffff;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            color: #444;
            text-decoration: none;
            display: inline-block;
        }
        
        .tab-link:hover {
            background: #D9A52A;
            color: #fff;
            border-color: #D9A52A;
            text-decoration: none;
        }
        
        .tab-link.active {
            background: #D9A52A;
            color: #fff;
            border-color: #D9A52A;
        }
        
        .tab-content-area {
            padding: 20px;
            background: #ffffff;
            overflow: auto;
            flex: 1;
        }
        
        .tab-content-area .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        
        .tab-content-area table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        
        .tab-content-area table thead {
            background: #1a2332;
            color: white;
        }
        
        .tab-content-area table thead th {
            padding: 12px;
            font-size: 13px;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }
        
        .tab-content-area table tbody td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .tab-content-area table tbody tr:hover {
            background: #f5f5f5;
        }
        
        /* ===== BUTTONS ===== */
        .btn-action { background: #D9A52A; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.7rem; cursor: pointer; }
        .btn-action:hover { background: #B8860B; }
        .btn-danger-sm { background: #dc3545; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.7rem; cursor: pointer; }
        .btn-danger-sm:hover { background: #c82333; }
        .btn-success-sm { background: #28a745; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.7rem; cursor: pointer; }
        .btn-success-sm:hover { background: #218838; }
        .btn-info-sm { background: #17a2b8; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 0.7rem; cursor: pointer; }
        .btn-info-sm:hover { background: #138496; }
        
        .status-badge { padding: 2px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 600; }
        .status-badge.paid { background: #d4edda; color: #155724; }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.canceled { background: #f8d7da; color: #721c24; }
        
        .empty-state { text-align: center; padding: 30px; color: #888; font-size: 0.9rem; }
        
        /* ===== MODAL ===== */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: #fff; padding: 25px; border-radius: 10px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .modal-box h4 { font-weight: 700; color: #1a2332; margin-bottom: 15px; }
        .modal-box .btn-save { background: #D9A52A; color: #fff; border: none; padding: 8px 25px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .modal-box .btn-save:hover { background: #B8860B; }
        .modal-box .btn-cancel { background: #6c757d; color: #fff; border: none; padding: 8px 25px; border-radius: 6px; font-weight: 600; cursor: pointer; margin-left: 10px; }
        .modal-box .btn-cancel:hover { background: #5a6268; }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .payment-content { padding: 10px; }
            .student-info { display: block; }
            .balance-box { font-size: 18px; }
            
            .tab-nav {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }
            
            .tab-link {
                width: 100%;
                text-align: center;
                padding: 8px 12px;
                font-size: 0.75rem;
            }
            
            table { min-width: 700px; }
            .tab-content-area { padding: 15px; }
        }
        
        @media (max-width: 576px) {
            .tab-nav {
                grid-template-columns: 1fr;
            }
            
            .tab-link {
                font-size: 0.7rem;
                padding: 6px 10px;
            }
            
            .tab-content-area { padding: 10px; }
            table { font-size: 0.7rem; min-width: 600px; }
            thead th { padding: 8px; font-size: 11px; }
            tbody td { padding: 6px 8px; }
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
            <div class="payment-content">
                <div class="payment-header">
                    <h2>Payment Account</h2>
                    <a href="/receive-payment" class="btn-back">Back to List</a>
                </div>

                <!-- Balance -->
                <div class="balance-box">
                    BALANCE: <span>{{ number_format($totalBalance ?? 0, 0) }}</span> TZS
                </div>

                <!-- Student Info -->
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
                    <div class="info-item">
                        <span class="label">Year</span>
                        <span class="value">{{ $enrollment->year ?? $student->year ?? '-' }}</span>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="tabs-container">
                    <div class="tab-nav">
                        <a href="{{ route('payment.profile') }}" class="tab-link {{ $activeTab == 'profile' ? 'active' : '' }}">Profile</a>
                        <a href="{{ route('payment.payment') }}" class="tab-link {{ $activeTab == 'payment' ? 'active' : '' }}">Payment</a>
                        <a href="{{ route('payment.cnumber') }}" class="tab-link {{ $activeTab == 'cnumber' ? 'active' : '' }}">C.Number</a>
                        <a href="{{ route('payment.transid') }}" class="tab-link {{ $activeTab == 'transid' ? 'active' : '' }}">Trans ID</a>
                        <a href="{{ route('payment.bills') }}" class="tab-link {{ $activeTab == 'bills' ? 'active' : '' }}">Bills</a>
                        <a href="{{ route('payment.receipts') }}" class="tab-link {{ $activeTab == 'receipts' ? 'active' : '' }}">Receipts</a>
                        <a href="{{ route('payment.invoice') }}" class="tab-link {{ $activeTab == 'invoice' ? 'active' : '' }}">Invoice</a>
                    </div>

                    <div class="tab-content-area">
                        <!-- Profile Tab Content -->
                        @if($activeTab == 'profile')
                        <div class="table-responsive">
                            <table>
                                <thead><tr><th colspan="2" style="text-align:center;">Student Profile</th></tr></thead>
                                <tbody>
                                    <tr><td><strong>Registration</strong></td><td>{{ $student->reg ?? '-' }}</td></tr>
                                    <tr><td><strong>Name</strong></td><td>{{ $student->sname ?? '-' }}</td></tr>
                                    <tr><td><strong>Gender</strong></td><td>{{ $student->gender ?? '-' }}</td></tr>
                                    <tr><td><strong>Date of Birth</strong></td><td>{{ $student->dob ?? '-' }}</td></tr>
                                    <tr><td><strong>Level</strong></td><td>{{ $enrollment->level ?? $student->level ?? '-' }}</td></tr>
                                    <tr><td><strong>Class</strong></td><td>{{ $enrollment->class ?? $student->class ?? '-' }}</td></tr>
                                    <tr><td><strong>Year</strong></td><td>{{ $enrollment->year ?? $student->year ?? '-' }}</td></tr>
                                    <tr><td><strong>Status</strong></td><td>{{ $student->status ?? '-' }}</td></tr>
                                    <tr><td><strong>Parent</strong></td><td>{{ $student->pgname ?? '-' }}</td></tr>
                                    <tr><td><strong>Parent Mobile</strong></td><td>{{ $student->pgmob ?? '-' }}</td></tr>
                                    <tr><td><strong>Sponsor</strong></td><td>{{ $student->spname ?? '-' }}</td></tr>
                                    <tr><td><strong>Registration Date</strong></td><td>{{ $student->date ?? '-' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Payment Tab Content -->
                        @if($activeTab == 'payment')
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories ?? [] as $payment)
                                    <tr>
                                        <td>{{ $payment->category ?? '-' }}</td>
                                        <td>{{ number_format($payment->balance ?? 0, 0) }}</td>
                                        <td>{{ $payment->year ?? '-' }}</td>
                                        <td>
                                            <span class="status-badge {{ ($payment->balance ?? 0) == 0 ? 'paid' : 'pending' }}">
                                                {{ ($payment->balance ?? 0) == 0 ? 'Paid' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(($payment->balance ?? 0) > 0)
                                                <button class="btn-success-sm" onclick="processPayment('{{ $payment->id }}', '{{ $student->sname ?? '' }}')">Pay</button>
                                                <button class="btn-info-sm" onclick="generateControl('{{ $payment->id }}')">C.Number</button>
                                            @else
                                                <span style="color:#28a745;">Paid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5"><div class="empty-state">No payment records found</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Control Number Tab Content -->
                        @if($activeTab == 'cnumber')
                        <div class="table-responsive">
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
                        @endif

                        <!-- Trans ID Tab Content -->
                        @if($activeTab == 'transid')
                        <div class="table-responsive">
                            <table>
                                <thead><tr><th>Transaction ID</th><th>Date</th><th>Amount</th><th>Mode</th></tr></thead>
                                <tbody>
                                    @forelse($transactions ?? [] as $t)
                                    <tr>
                                        <td><strong>{{ $t->invoid ?? '-' }}</strong></td>
                                        <td>{{ $t->date ?? '-' }}</td>
                                        <td>{{ number_format($t->cost ?? 0, 0) }}</td>
                                        <td>{{ $t->mode ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4"><div class="empty-state">No transactions found</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Bills Tab Content -->
                        @if($activeTab == 'bills')
                        <div class="table-responsive">
                            <table>
                                <thead><tr><th>Invoice</th><th>Category</th><th>Amount</th><th>Date</th></tr></thead>
                                <tbody>
                                    @forelse($bills ?? [] as $b)
                                    <tr>
                                        <td><strong>{{ $b->invo ?? '-' }}</strong></td>
                                        <td>{{ $b->category ?? '-' }}</td>
                                        <td>{{ number_format($b->amount ?? 0, 0) }}</td>
                                        <td>{{ $b->date ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4"><div class="empty-state">No bills found</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Receipts Tab Content -->
                        @if($activeTab == 'receipts')
                        <div class="table-responsive">
                            <table>
                                <thead><tr><th>Receipt No</th><th>Date</th><th>Amount</th><th>User</th></tr></thead>
                                <tbody>
                                    @forelse($receipts ?? [] as $r)
                                    <tr>
                                        <td><strong>{{ $r->invoid ?? '-' }}</strong></td>
                                        <td>{{ $r->date ?? '-' }}</td>
                                        <td>{{ number_format($r->cost ?? 0, 0) }}</td>
                                        <td>{{ $r->user ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4"><div class="empty-state">No receipts found</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Invoice Tab Content -->
                        @if($activeTab == 'invoice')
                        <div class="table-responsive">
                            <table>
                                <thead><tr><th>Invoice No</th><th>Total Amount</th><th>Items</th><th>Date</th></tr></thead>
                                <tbody>
                                    @forelse($invoiceData ?? [] as $inv)
                                    <tr>
                                        <td><strong>{{ $inv['invo'] ?? '-' }}</strong></td>
                                        <td>{{ number_format($inv['total'] ?? 0, 0) }}</td>
                                        <td>{{ $inv['items'] ?? 0 }}</td>
                                        <td>{{ $inv['date'] ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4"><div class="empty-state">No invoices found</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Payment Modal -->
    <div class="modal-overlay" id="paymentModal">
        <div class="modal-box">
            <h4>Confirm Payment</h4>
            <p>Are you sure you want to process this payment?</p>
            <div style="margin-top:15px; text-align:right;">
                <button class="btn-cancel" onclick="closePaymentModal()">Cancel</button>
                <button class="btn-save" id="confirmPayBtn">Confirm Payment</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ===== PAYMENT FUNCTIONS =====
        var currentCatId = null;
        var currentSname = '';

        function processPayment(catid, sname) {
            currentCatId = catid;
            currentSname = sname;
            $('#paymentModal').addClass('active');
        }

        function closePaymentModal() {
            $('#paymentModal').removeClass('active');
            currentCatId = null;
        }

        $('#confirmPayBtn').on('click', function() {
            if (!currentCatId) return;

            var btn = $(this);
            btn.prop('disabled', true);
            btn.html('<span class="loading-spinner"></span> Processing...');

            $.ajax({
                url: '/api/student-payment/process-payment',
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: {
                    catid: currentCatId,
                    sname: currentSname,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    btn.prop('disabled', false);
                    btn.html('Confirm Payment');
                    if (response.success) {
                        alert(response.message);
                        closePaymentModal();
                        window.location.reload();
                    } else {
                        alert(response.message || 'Error processing payment');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    btn.html('Confirm Payment');
                    alert(xhr.responseJSON?.message || 'Error processing payment');
                }
            });
        });

        function generateControl(catid) {
            if (!catid) return;
            if (!confirm('Generate control number for this payment?')) return;

            $.ajax({
                url: '/api/student-payment/generate-control',
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
                url: '/api/student-payment/delete-control',
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
