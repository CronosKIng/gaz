<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Payment - Glorious Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
        
        .payment-content { 
            display: flex; 
            flex-direction: column; 
            height: 100%; 
            padding: 0; 
            background: #ffffff; 
        }
        
        .payment-header {
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
        .payment-header h2 {
            font-weight: 700;
            color: #1a2332;
            margin: 0;
            font-size: 1.3rem;
        }
        .payment-header .btn-back {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .payment-header .btn-back:hover {
            background: #5a6268;
            color: #fff;
        }
        
        .payment-body {
            flex: 1;
            overflow-y: auto;
            padding: 15px 25px;
        }
        
        .student-info {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .student-info .info-item {
            display: inline-block;
            margin-right: 30px;
        }
        .student-info .info-item .label {
            font-weight: 600;
            color: #888;
            font-size: 0.8rem;
        }
        .student-info .info-item .value {
            font-weight: 700;
            color: #1a2332;
            font-size: 1rem;
        }
        
        .balance-box {
            background: #dc3545;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        
        .payment-tabs {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
        }
        .payment-tabs .tab-btn {
            background: #e9ecef;
            border: none;
            padding: 8px 20px;
            border-radius: 6px 6px 0 0;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        .payment-tabs .tab-btn:hover {
            background: #D9A52A;
            color: #fff;
        }
        .payment-tabs .tab-btn.active {
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
        .table-scroll table tbody tr:hover {
            background: #f8f9fa;
        }
        .table-scroll table tbody td {
            padding: 6px 12px;
            color: #444;
        }
        
        .payment-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
        }
        .payment-form .form-control {
            border: 2px solid #e9ecef;
            border-radius: 6px;
            padding: 8px 12px;
        }
        .payment-form .form-control:focus {
            border-color: #D9A52A;
            box-shadow: 0 0 0 3px rgba(217, 165, 42, 0.15);
        }
        .payment-form .btn-pay {
            background: #D9A52A;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }
        .payment-form .btn-pay:hover {
            background: #B8860B;
        }
        .payment-form .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .payment-form .btn-generate {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }
        .payment-form .btn-generate:hover {
            background: #218838;
        }
        .payment-form .btn-delete-control {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
        }
        .payment-form .btn-delete-control:hover {
            background: #c82333;
        }
        .payment-form .btn-print-invoice {
            background: #17a2b8;
            color: #fff;
            border: none;
            padding: 6px 15px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .payment-form .btn-print-invoice:hover {
            background: #138496;
            color: #fff;
            text-decoration: none;
        }
        .payment-form .btn-download-pdf {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 15px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .payment-form .btn-download-pdf:hover {
            background: #c82333;
            color: #fff;
            text-decoration: none;
        }
        .payment-form .btn-view-receipt {
            background: #6f42c1;
            color: #fff;
            border: none;
            padding: 6px 15px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .payment-form .btn-view-receipt:hover {
            background: #5a32a3;
            color: #fff;
            text-decoration: none;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #D9A52A;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .alert-message {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: none;
        }
        .alert-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        .alert-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        .alert-message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            display: block;
        }
        
        .control-number-box {
            background: #fff3cd;
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid #ffc107;
            margin-bottom: 10px;
        }
        .control-number-box .cn {
            font-weight: 700;
            color: #856404;
            font-size: 1.1rem;
        }
        
        .badge-paid {
            background: #28a745;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
        }
        .badge-pending {
            background: #ffc107;
            color: #000;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
        }
        
        .invoice-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .invoice-modal-content {
            background-color: #fefefe;
            margin: 2% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 900px;
            border-radius: 10px;
            position: relative;
        }
        .invoice-modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .invoice-modal-close:hover {
            color: #000;
        }
        .invoice-modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .invoice-logo {
            max-width: 80px;
            height: auto;
        }
        .invoice-header-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .receipt-title {
            color: #1a2332;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
        }
        .receipt-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .receipt-table th {
            background: #1a2332;
            color: #fff;
            padding: 10px;
            text-align: left;
        }
        .receipt-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .receipt-total {
            background: #D9A52A;
            color: #fff;
            font-weight: bold;
        }
        .receipt-total td {
            padding: 10px;
            font-weight: bold;
        }
        .receipt-words {
            padding: 15px;
            border: 1px dashed #1a2332;
            border-radius: 5px;
            background: #f8f9fa;
            margin: 15px 0;
        }
        
        @media print {
            .no-print { display: none !important; }
            .payment-header { display: none !important; }
            .payment-tabs { display: none !important; }
            .student-info { display: none !important; }
            .balance-box { display: none !important; }
            .invoice-modal-content {
                margin: 0;
                padding: 0;
                border: none;
                width: 100%;
            }
            .invoice-modal-close { display: none !important; }
        }
        
        @media (max-width: 768px) {
            .payment-body { padding: 10px 15px; }
            .payment-header { padding: 10px 15px; flex-direction: column; align-items: stretch; }
            .payment-header .btn-back { text-align: center; }
            .student-info .info-item { display: block; margin-right: 0; margin-bottom: 5px; }
            .payment-tabs .tab-btn { flex: 1; text-align: center; font-size: 0.75rem; padding: 6px 10px; }
            .invoice-modal-content { width: 95%; margin: 5% auto; }
            .invoice-header-logo { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <button class="sidebar-open-btn" id="sidebarOpenBtn">-</button>
        <aside class="dashboard-sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="https://i.ibb.co/gMfmMQ2B/logo.png" alt="Glorious Academy">
                <h5>Glorious Academy</h5>
                <p>School Management System</p>
                <button class="sidebar-toggle-top" id="sidebarToggleTop">-</button>
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
                        <li><a href="/payment/receive" class="active">Receive Payment</a></li>
                        <li><a href="#">Register Staff Students</a></li>
                        <li><a href="#">Staff Students Payment</a></li>
                        <li><a href="#">Pay Bills</a></li>
                        <li><a href="#">Set Budget</a></li>
                        <li><a href="#">ZRA Receipts</a></li>
                        <li><a href="#">Payroll</a></li>
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
                    <a href="/payment/receive" class="btn-back">Back to Students</a>
                </div>

                <div class="payment-body">
                    <div id="alertMessage" class="alert-message"></div>

                    <div class="student-info">
                        <div class="info-item">
                            <div class="label">Registration</div>
                            <div class="value">{{ $reg ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Student Name</div>
                            <div class="value">{{ $student->sname ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Class</div>
                            <div class="value">{{ $student->class ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Level</div>
                            <div class="value">{{ $student->level ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="balance-box">
                        Total Balance: {{ number_format($totalBalance ?? 0) }} TZS
                    </div>

                    <div class="payment-tabs">
                        <button class="tab-btn active" onclick="showTab('control')">Generate Control</button>
                        <button class="tab-btn" onclick="showTab('transid')">Trans ID</button>
                        <button class="tab-btn" onclick="showTab('bills')">Bills</button>
                        <button class="tab-btn" onclick="showTab('receipts')">Receipts</button>
                        <button class="tab-btn" onclick="showTab('invoice')">Invoice</button>
                    </div>

                    <div id="tab-control" class="tab-content active">
                        <div class="payment-form">
                            <h6 style="font-weight:700;color:#1a2332;margin-bottom:15px;">Generate Control Number</h6>
                            <form id="controlForm" onsubmit="return false;">
                                <input type="hidden" id="ctrlReg" value="{{ $reg }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Select Bill</label>
                                        <select id="ctrlCatid" class="form-control" required>
                                            <option value="">Select Bill</option>
                                            @foreach($billsWithBalance ?? [] as $bill)
                                                <option value="{{ $bill->id }}">{{ $bill->category }} - {{ number_format($bill->balance) }} TZS ({{ $bill->year }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <button type="button" id="generateCtrlBtn" class="btn-generate w-100">Generate Control Number</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <h6 style="font-weight:700;color:#1a2332;margin-top:15px;margin-bottom:10px;">Control Numbers</h6>
                        <div id="controlNumbersList">
                            @if($controlNumbers && $controlNumbers->count() > 0)
                                <div class="table-scroll">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Control Number</th>
                                                <th>Amount</th>
                                                <th>Year</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($controlNumbers as $cn)
                                                <tr>
                                                    <td>{{ $cn->category }}</td>
                                                    <td><strong>{{ $cn->cnamba }}</strong></td>
                                                    <td>{{ number_format($cn->amount) }}</td>
                                                    <td>{{ $cn->year }}</td>
                                                    <td>
                                                        <button onclick="deleteControlNumber('{{ $cn->cnamba }}')" class="btn-delete-control">Delete</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No control numbers generated.</p>
                            @endif
                        </div>
                    </div>

                    <div id="tab-transid" class="tab-content">
                        <div class="payment-form">
                            <h6 style="font-weight:700;color:#1a2332;margin-bottom:15px;">Transport ID Card</h6>
                            @if(isset($transportId) && $transportId)
                                <div class="control-number-box">
                                    <p><strong>Transport ID:</strong> <span class="cn">{{ $transportId->trans_id ?? '-' }}</span></p>
                                    <p><strong>Route:</strong> {{ $transportId->route ?? '-' }}</p>
                                    <p><strong>Status:</strong> {{ $transportId->status ?? 'Active' }}</p>
                                </div>
                            @else
                                <p class="text-muted">No transport ID found for this student.</p>
                            @endif
                        </div>
                    </div>

                    <div id="tab-bills" class="tab-content">
                        <h6 style="font-weight:700;color:#1a2332;margin-bottom:10px;">All Bills</h6>
                        @if($payments && $payments->count() > 0)
                            <div class="table-scroll">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Year</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->category }}</td>
                                                <td>{{ $payment->year }}</td>
                                                <td>{{ number_format($payment->amount) }}</td>
                                                <td>{{ number_format($payment->balance) }}</td>
                                                <td>
                                                    @if($payment->balance == 0)
                                                        <span class="badge-paid">Paid</span>
                                                    @else
                                                        <span class="badge-pending">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No bills found.</p>
                        @endif
                    </div>

                    <div id="tab-receipts" class="tab-content">
                        <div class="payment-form">
                            <h6 style="font-weight:700;color:#1a2332;margin-bottom:15px;">All Receipts</h6>
                            <div id="receiptsList">
                                @if($allPayments && $allPayments->count() > 0)
                                    <div class="table-scroll">
                                        <table id="receiptsTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Category</th>
                                                    <th>Amount</th>
                                                    <th>Year</th>
                                                    <th>Date</th>
                                                    <th>Staff</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allPayments as $payment)
                                                    <tr>
                                                        <td><strong>{{ $payment->invo }}</strong></td>
                                                        <td>{{ $payment->category }}</td>
                                                        <td>{{ number_format($payment->amount) }} TZS</td>
                                                        <td>{{ $payment->year }}</td>
                                                        <td>{{ $payment->date }}</td>
                                                        <td>{{ $payment->staff ?? '-' }}</td>
                                                        <td>
                                                            <button onclick="viewReceipt({{ $payment->id }})" class="btn-view-receipt">View Receipt</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No receipts found.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="tab-invoice" class="tab-content">
                        <div class="payment-form">
                            <h6 style="font-weight:700;color:#1a2332;margin-bottom:15px;">Student Invoices</h6>
                            <div id="invoiceList">
                                <div class="text-center text-muted">Loading invoices...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="invoiceModal" class="invoice-modal">
        <div class="invoice-modal-content">
            <span class="invoice-modal-close" onclick="closeInvoiceModal()">x</span>
            <div id="invoiceModalBody" class="invoice-modal-body"></div>
            <div class="text-center mt-3 no-print">
                <button onclick="printInvoiceModal()" class="btn btn-primary">Print</button>
                <button onclick="downloadInvoicePDF()" class="btn btn-danger">Download PDF</button>
                <button onclick="closeInvoiceModal()" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        const currentReg = '{{ $reg }}';
        const LOGO_URL = 'https://i.ibb.co/gMfmMQ2B/logo.png';

        function showTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            
            document.getElementById('tab-' + tab).classList.add('active');
            document.querySelector('.tab-btn[onclick*="' + tab + '"]').classList.add('active');

            if (tab === 'invoice' && currentReg) {
                loadInvoices();
            }
            if (tab === 'receipts') {
                setTimeout(function() {
                    if ($.fn.DataTable && $('#receiptsTable').length) {
                        if (!$.fn.DataTable.isDataTable('#receiptsTable')) {
                            $('#receiptsTable').DataTable({
                                pageLength: 10,
                                order: [[4, 'desc']],
                                language: {
                                    search: "Search:",
                                    lengthMenu: "Show _MENU_ entries",
                                    info: "Showing _START_ to _END_ of _TOTAL_ receipts"
                                }
                            });
                        }
                    }
                }, 100);
            }
        }

        function showAlert(message, type) {
            const alertEl = $('#alertMessage');
            alertEl.removeClass('success error info').addClass(type);
            alertEl.text(message);
            alertEl.show();
            
            setTimeout(function() {
                alertEl.hide();
            }, 5000);
        }

        function hideAlert() {
            $('#alertMessage').hide();
        }

        function updateControlNumbers(controlNumbers) {
            let html = '';
            if (controlNumbers.length > 0) {
                html += '<div class="table-scroll"><table><thead><tr><th>Category</th><th>Control Number</th><th>Amount</th><th>Year</th><th>Action</th></tr></thead><tbody>';
                controlNumbers.forEach(function(cn) {
                    html += '<tr><td>' + cn.category + '</td><td><strong>' + cn.cnamba + '</strong></td><td>' + numberFormat(cn.amount) + '</td><td>' + cn.year + '</td><td><button onclick="deleteControlNumber(\'' + cn.cnamba + '\')" class="btn-delete-control">Delete</button></td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html = '<p class="text-muted">No control numbers generated.</p>';
            }
            $('#controlNumbersList').html(html);
        }

        function updateBillsDropdown(bills) {
            const select = $('#ctrlCatid');
            select.html('<option value="">Select Bill</option>');
            bills.forEach(function(bill) {
                select.append('<option value="' + bill.id + '">' + bill.category + ' - ' + numberFormat(bill.balance) + ' TZS (' + bill.year + ')</option>');
            });
        }

        function numberFormat(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function deleteControlNumber(cnamba) {
            if (!confirm('Are you sure you want to delete control number ' + cnamba + '?')) return;
            
            $.ajax({
                url: '/api/student-payment/delete-control',
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: {
                    cnamba: cnamba,
                    reg: $('#ctrlReg').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        if (response.controlNumbers) {
                            updateControlNumbers(response.controlNumbers);
                        }
                        if (response.billsWithBalance) {
                            updateBillsDropdown(response.billsWithBalance);
                        }
                    } else {
                        showAlert(response.message || 'Error deleting control number', 'error');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showAlert(errorMsg, 'error');
                }
            });
        }

        function loadInvoices() {
            if (!currentReg) {
                $('#invoiceList').html('<p class="text-muted">No student selected</p>');
                return;
            }

            $.ajax({
                url: '/api/student-payment/invoices',
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: {
                    reg: currentReg,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#invoiceList').html('<div class="text-center text-muted">Loading invoices...</div>');
                },
                success: function(response) {
                    if (response.success && response.invoices.length > 0) {
                        let html = '<div class="table-scroll"><table id="invoiceTable" class="table table-bordered">';
                        html += '<thead><tr>';
                        html += '<th>Invoice No</th>';
                        html += '<th>Category</th>';
                        html += '<th>Amount</th>';
                        html += '<th>Year</th>';
                        html += '<th>Date</th>';
                        html += '<th>Status</th>';
                        html += '<th>Actions</th>';
                        html += '</tr></thead><tbody>';

                        response.invoices.forEach(function(invoice) {
                            let statusBadge = invoice.status === 'Not yet' ? 
                                '<span class="badge-pending">Pending</span>' : 
                                '<span class="badge-paid">Paid</span>';
                            
                            html += '<tr>';
                            html += '<td><strong>' + (invoice.invo || invoice.id) + '</strong></td>';
                            html += '<td>' + invoice.category + '</td>';
                            html += '<td>' + numberFormat(invoice.amount) + ' TZS</td>';
                            html += '<td>' + invoice.year + '</td>';
                            html += '<td>' + invoice.date + '</td>';
                            html += '<td>' + statusBadge + '</td>';
                            html += '<td>';
                            html += '<button onclick="viewInvoice(' + invoice.id + ')" class="btn-print-invoice me-1">View</button>';
                            html += '</td>';
                            html += '</tr>';
                        });

                        html += '</tbody></table></div>';
                        $('#invoiceList').html(html);

                        if ($.fn.DataTable) {
                            if ($.fn.DataTable.isDataTable('#invoiceTable')) {
                                $('#invoiceTable').DataTable().destroy();
                            }
                            $('#invoiceTable').DataTable({
                                pageLength: 10,
                                order: [[4, 'desc']],
                                language: {
                                    search: "Search:",
                                    lengthMenu: "Show _MENU_ entries",
                                    info: "Showing _START_ to _END_ of _TOTAL_ invoices"
                                }
                            });
                        }
                    } else {
                        $('#invoiceList').html('<p class="text-muted">No invoices found for this student.</p>');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error loading invoices. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#invoiceList').html('<p class="text-danger">' + errorMsg + '</p>');
                }
            });
        }

        function viewReceipt(id) {
            $.ajax({
                url: '/api/invoice/' + id,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                    $('#invoiceModalBody').html('<div class="text-center">Loading receipt...</div>');
                    $('#invoiceModal').show();
                },
                success: function(response) {
                    if (response.success) {
                        let invoice = response.invoice;
                        let html = generateReceiptHTML(invoice);
                        $('#invoiceModalBody').html(html);
                    } else {
                        $('#invoiceModalBody').html('<p class="text-danger">' + response.message + '</p>');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error loading receipt. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#invoiceModalBody').html('<p class="text-danger">' + errorMsg + '</p>');
                }
            });
        }

        function generateReceiptHTML(invoice) {
            let amountWords = convertNum(invoice.amount);
            
            return `
                <div id="invoiceContent" style="font-family: Arial, sans-serif; padding: 20px;">
                    <div style="text-align: center; border-bottom: 2px solid #1a2332; padding-bottom: 15px; margin-bottom: 20px;">
                        <div class="invoice-header-logo" style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 10px;">
                            <img src="${LOGO_URL}" alt="Glorious Academy Logo" style="max-width: 80px; height: auto;">
                            <div>
                                <h1 style="color: #1a2332; font-size: 24px; margin: 0;">GLORIOUS ACADEMY ZANZIBAR</h1>
                                <div style="font-size: 12px; color: #666;">TIN: 107 440 992 | ZRB/03/C/2825</div>
                            </div>
                        </div>
                        <div style="font-size: 12px; color: #666;">
                            P.O.BOX: 1765 ZANZIBAR, TANZANIA<br>
                            MOB: +255 777 694 939 | Email: info@gloriousacademy.ac.tz
                        </div>
                    </div>

                    <div class="receipt-title">OFFICIAL RECEIPT</div>

                    <div class="receipt-details">
                        <table style="width: 100%; font-size: 14px;">
                            <tr>
                                <td><strong>Registration:</strong></td>
                                <td>${invoice.reg}</td>
                                <td><strong>Date:</strong></td>
                                <td>${invoice.date}</td>
                            </tr>
                            <tr>
                                <td><strong>Student Name:</strong></td>
                                <td>${invoice.name}</td>
                                <td><strong>Receipt No:</strong></td>
                                <td><strong>${invoice.invo || invoice.id}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Class:</strong></td>
                                <td>${invoice.class || '-'}</td>
                                <td><strong>Level:</strong></td>
                                <td>${invoice.level || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Staff:</strong></td>
                                <td>${invoice.staff || '-'}</td>
                                <td><strong>Status:</strong></td>
                                <td>${invoice.status || 'Not yet'}</td>
                            </tr>
                        </table>
                    </div>

                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Particulars</th>
                                <th style="width: 20%;">Year</th>
                                <th style="width: 20%; text-align: right;">Amount (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${invoice.category}</td>
                                <td>${invoice.year}</td>
                                <td style="text-align: right;">${numberFormat(invoice.amount)}</td>
                            </tr>
                            <tr class="receipt-total">
                                <td colspan="2"><strong>TOTAL</strong></td>
                                <td style="text-align: right;"><strong>${numberFormat(invoice.amount)}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="receipt-words">
                        <strong>Amount in Words:</strong> ${amountWords.toUpperCase()} SHILLINGS ONLY
                    </div>

                    <div style="margin-top: 30px; text-align: center; border-top: 2px solid #1a2332; padding-top: 15px; font-size: 12px; color: #666;">
                        <p>Thank you for choosing Glorious Academy Zanzibar</p>
                        <p>Web: www.gloriousacademy.ac.tz</p>
                        <p>This is a computer generated receipt</p>
                    </div>
                </div>
            `;
        }

        function viewInvoice(id) {
            $.ajax({
                url: '/api/invoice/' + id,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                    $('#invoiceModalBody').html('<div class="text-center">Loading invoice...</div>');
                    $('#invoiceModal').show();
                },
                success: function(response) {
                    if (response.success) {
                        let invoice = response.invoice;
                        let html = generateInvoiceHTML(invoice);
                        $('#invoiceModalBody').html(html);
                    } else {
                        $('#invoiceModalBody').html('<p class="text-danger">' + response.message + '</p>');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error loading invoice. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#invoiceModalBody').html('<p class="text-danger">' + errorMsg + '</p>');
                }
            });
        }

        function generateInvoiceHTML(invoice) {
            let amountWords = convertNum(invoice.amount);
            
            return `
                <div id="invoiceContent" style="font-family: Arial, sans-serif; padding: 20px;">
                    <div style="text-align: center; border-bottom: 2px solid #1a2332; padding-bottom: 15px; margin-bottom: 20px;">
                        <div class="invoice-header-logo" style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 10px;">
                            <img src="${LOGO_URL}" alt="Glorious Academy Logo" style="max-width: 80px; height: auto;">
                            <div>
                                <h1 style="color: #1a2332; font-size: 24px; margin: 0;">GLORIOUS ACADEMY ZANZIBAR</h1>
                                <div style="font-size: 12px; color: #666;">TIN: 107 440 992 | ZRB/03/C/2825</div>
                            </div>
                        </div>
                        <div style="font-size: 12px; color: #666;">
                            P.O.BOX: 1765 ZANZIBAR, TANZANIA<br>
                            MOB: +255 777 694 939 | Email: info@gloriousacademy.ac.tz
                        </div>
                    </div>

                    <div style="text-align: center; font-size: 20px; font-weight: bold; color: #1a2332; margin-bottom: 20px;">
                        INVOICE
                    </div>

                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <table style="width: 100%; font-size: 14px;">
                            <tr>
                                <td><strong>Registration:</strong></td>
                                <td>${invoice.reg}</td>
                                <td><strong>Date:</strong></td>
                                <td>${invoice.date}</td>
                            </tr>
                            <tr>
                                <td><strong>Student Name:</strong></td>
                                <td>${invoice.name}</td>
                                <td><strong>Invoice No:</strong></td>
                                <td><strong>${invoice.invo || invoice.id}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Class:</strong></td>
                                <td>${invoice.class || '-'}</td>
                                <td><strong>Level:</strong></td>
                                <td>${invoice.level || '-'}</td>
                            </tr>
                        </table>
                    </div>

                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Particulars</th>
                                <th style="width: 20%;">Year</th>
                                <th style="width: 20%; text-align: right;">Amount (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${invoice.category}</td>
                                <td>${invoice.year}</td>
                                <td style="text-align: right;">${numberFormat(invoice.amount)}</td>
                            </tr>
                            <tr class="receipt-total">
                                <td colspan="2"><strong>TOTAL</strong></td>
                                <td style="text-align: right;"><strong>${numberFormat(invoice.amount)}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="receipt-words">
                        <strong>Amount in Words:</strong> ${amountWords.toUpperCase()} SHILLINGS ONLY
                    </div>

                    <div style="margin-top: 30px; text-align: center; border-top: 2px solid #1a2332; padding-top: 15px; font-size: 12px; color: #666;">
                        <p>Thank you for choosing Glorious Academy Zanzibar</p>
                        <p>Web: www.gloriousacademy.ac.tz</p>
                    </div>
                </div>
            `;
        }

        function convertNum(num) {
            const ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 
                          'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 
                          'eighteen', 'nineteen'];
            const tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
            const triplets = ['', 'thousand', 'million', 'billion', 'trillion'];

            function convertTri(n, tri) {
                const r = Math.floor(n / 1000);
                const x = Math.floor((n / 100) % 10);
                const y = n % 100;
                let str = '';
                if (x > 0) str += ones[x] + ' hundred';
                if (y < 20) str += ones[y];
                else str += tens[Math.floor(y / 10)] + ones[y % 10];
                if (str !== '') str += triplets[tri];
                if (r > 0) return convertTri(r, tri + 1) + str;
                else return str;
            }

            if (num === 0) return 'zero';
            return convertTri(num, 0).trim();
        }

        function closeInvoiceModal() {
            $('#invoiceModal').hide();
        }

        function printInvoiceModal() {
            const content = document.getElementById('invoiceContent');
            if (!content) {
                alert('No content to print');
                return;
            }
            const originalContent = content.innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Invoice</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
            printWindow.document.write('.receipt-table { width: 100%; border-collapse: collapse; margin: 15px 0; }');
            printWindow.document.write('.receipt-table th { background: #1a2332; color: #fff; padding: 10px; text-align: left; }');
            printWindow.document.write('.receipt-table td { padding: 10px; border-bottom: 1px solid #e9ecef; }');
            printWindow.document.write('.receipt-total { background: #D9A52A; color: #fff; font-weight: bold; }');
            printWindow.document.write('.receipt-total td { padding: 10px; font-weight: bold; }');
            printWindow.document.write('.receipt-words { padding: 15px; border: 1px dashed #1a2332; border-radius: 5px; background: #f8f9fa; margin: 15px 0; }');
            printWindow.document.write('.receipt-details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }');
            printWindow.document.write('.receipt-title { color: #1a2332; font-size: 22px; font-weight: bold; text-align: center; margin: 15px 0; }');
            printWindow.document.write('.invoice-header-logo { display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 10px; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(originalContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            setTimeout(function() {
                printWindow.print();
            }, 500);
        }

        function downloadInvoicePDF() {
            const element = document.getElementById('invoiceContent');
            if (!element) {
                alert('No content to download');
                return;
            }
            const opt = {
                margin: 10,
                filename: 'invoice_' + new Date().getTime() + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('invoiceModal');
            if (event.target === modal) {
                closeInvoiceModal();
            }
        }

        $('#generateCtrlBtn').on('click', function() {
            const btn = $(this);
            const reg = $('#ctrlReg').val();
            const catid = $('#ctrlCatid').val();

            if (!catid) {
                showAlert('Please select a bill.', 'error');
                return;
            }

            btn.prop('disabled', true);
            btn.html('<span class="loading-spinner"></span> Generating...');
            hideAlert();

            $.ajax({
                url: '/api/student-payment/generate-control',
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: {
                    reg: reg,
                    catid: catid,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    btn.prop('disabled', false);
                    btn.html('Generate Control Number');
                    
                    if (response.success) {
                        showAlert(response.message + ' Control Number: ' + response.control_number, 'success');
                        if (response.controlNumbers) {
                            updateControlNumbers(response.controlNumbers);
                        }
                        if (response.billsWithBalance) {
                            updateBillsDropdown(response.billsWithBalance);
                        }
                    } else {
                        showAlert(response.message || 'Error generating control number', 'error');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    btn.html('Generate Control Number');
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showAlert(errorMsg, 'error');
                }
            });
        });

        $(document).ready(function() {
            if ($('#tab-invoice').hasClass('active') && currentReg) {
                loadInvoices();
            }
            if ($('#tab-receipts').hasClass('active')) {
                setTimeout(function() {
                    if ($.fn.DataTable && $('#receiptsTable').length) {
                        if (!$.fn.DataTable.isDataTable('#receiptsTable')) {
                            $('#receiptsTable').DataTable({
                                pageLength: 10,
                                order: [[4, 'desc']],
                                language: {
                                    search: "Search:",
                                    lengthMenu: "Show _MENU_ entries",
                                    info: "Showing _START_ to _END_ of _TOTAL_ receipts"
                                }
                            });
                        }
                    }
                }, 100);
            }
        });
    </script>
</body>
</html>
