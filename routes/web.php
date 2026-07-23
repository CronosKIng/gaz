<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NewStudentController;
use App\Http\Controllers\ExistingStudentController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\ChangeClassController;
use App\Http\Controllers\VtransController;
use App\Http\Controllers\StaffStudentController;
use App\Http\Controllers\DeleteStudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\ControlNumberController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\EnrollApiController;

// ===== PUBLIC ROUTES =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/admission', [AdmissionController::class, 'index'])->name('admission');
Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/staff-login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff-login', [StaffLoginController::class, 'login'])->name('staff.login.submit');
Route::get('/login', function() { return redirect()->route('staff.login'); })->name('login');

// ===== API ROUTES =====
Route::middleware(['auth', 'check.password'])->group(function () {
    Route::get('/api/students', [StudentApiController::class, 'getStudents']);
    Route::get('/api/students/{id}', [StudentApiController::class, 'getStudent']);
    Route::get('/api/dashboard', [DashboardApiController::class, 'getDashboardData']);
    Route::get('/api/enroll/classes', [EnrollApiController::class, 'getClasses']);
    Route::get('/api/enroll/students', [EnrollApiController::class, 'getStudentsByClass']);
    Route::post('/api/enroll/student', [EnrollApiController::class, 'enrollStudent']);
});

// ===== PROTECTED ROUTES =====
Route::middleware(['auth', 'check.password'])->group(function () {
    
    Route::post('/logout', [StaffLoginController::class, 'logout'])->name('staff.logout');
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard')->middleware('check.password');
    
    // ===== STUDENT ROUTES =====
    Route::get('/students/new', [NewStudentController::class, 'index'])->name('students.new');
    Route::get('/students/existing', [ExistingStudentController::class, 'index'])->name('students.existing');
    Route::get('/students/enroll', [EnrollController::class, 'index'])->name('students.enroll');
    Route::get('/students/change-class', [ChangeClassController::class, 'index'])->name('students.change-class');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/staff', [StaffStudentController::class, 'index'])->name('students.staff');
    Route::get('/students/delete', [DeleteStudentController::class, 'index'])->name('students.delete');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    
    // ===== ENROLLMENT ROUTES =====
    Route::get('/enroll-step2', [EnrollController::class, 'step2'])->name('enroll.step2');
    Route::get('/api/enroll/students-list', [EnrollController::class, 'getStudents']);
    Route::get('/api/enroll/enrolled-list', [EnrollController::class, 'getEnrolledStudents']);
    Route::post('/api/enroll/add', [EnrollController::class, 'addStudent']);
    Route::post('/api/enroll/delete', [EnrollController::class, 'deleteStudent']);
    Route::post('/api/enroll/delete-class', [EnrollController::class, 'deleteClass']);
    
    // ===== CHANGE CLASS ROUTES =====
    Route::get('/vtrans', [VtransController::class, 'index'])->name('vtrans');
    Route::get('/api/change-class/classes', [ChangeClassController::class, 'getClasses']);
    Route::post('/api/change-class/process', [ChangeClassController::class, 'processChangeClass']);
    Route::get('/api/vtrans/available', [VtransController::class, 'getAvailableStudents']);
    Route::get('/api/vtrans/enrolled', [VtransController::class, 'getEnrolledStudents']);
    Route::post('/api/vtrans/add', [VtransController::class, 'addStudent']);
    Route::post('/api/vtrans/remove', [VtransController::class, 'removeStudent']);
    Route::post('/api/vtrans/update-class', [VtransController::class, 'updateClass']);
    
    // ===== STAFF STUDENT ROUTES =====
    Route::get('/api/staff-students', [StaffStudentController::class, 'getStaffStudents']);
    Route::post('/api/staff-payment', [StaffStudentController::class, 'processPayment']);
    Route::post('/api/staff-view', [StaffStudentController::class, 'viewStudent']);
    
    // ===== DELETE STUDENT ROUTES =====
    Route::get('/api/delete-students', [DeleteStudentController::class, 'getStudents']);
    Route::post('/api/delete-student', [DeleteStudentController::class, 'deleteStudent']);
    
    // ===== NEW/EXISTING STUDENT ROUTES =====
    Route::get('/api/new-student/classes', [NewStudentController::class, 'getClasses']);
    Route::post('/api/new-student', [NewStudentController::class, 'store']);
    Route::get('/api/existing-student/classes', [ExistingStudentController::class, 'getClasses']);
    Route::post('/api/existing-student', [ExistingStudentController::class, 'store']);
    
    // ===== PAYMENT ROUTES =====
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment.index');
    Route::get('/payment/receive', [PaymentController::class, 'receivePayment'])->name('payment.receive');
    Route::get('/payment/account', [PaymentController::class, 'paymentAccount'])->name('payment.account');
    Route::get('/payment/payment', [PaymentController::class, 'paymentAccount'])->name('payment.payment');
    Route::get('/payment/transid', [PaymentController::class, 'transid'])->name('payment.transid');
    Route::get('/api/payment/transid-list', [PaymentController::class, 'transidList']);
    Route::get('/payment/receipt', [PaymentController::class, 'receipt'])->name('payment.receipt');
    Route::get('/api/payment/search', [PaymentController::class, 'searchStudents']);
    Route::post('/api/payment/view', [PaymentController::class, 'viewPayment']);
    Route::post('/api/payment/process', [PaymentController::class, 'processPayment']);
    Route::get('/payment/receipts', [PaymentController::class, 'receipts'])->name('payment.receipts');
    Route::get('/payment/invoice', [PaymentController::class, 'invoice'])->name('payment.invoice');
    
    Route::get('/receive-payment', function() {
        return redirect()->route('payment.receive');
    });
    
    // ===== CONTROL NUMBER ROUTES =====
    Route::get('/payment/cnumber', [ControlNumberController::class, 'index'])->name('payment.cnumber');
    Route::post('/api/control-number/generate', [ControlNumberController::class, 'generate']);
    Route::post('/api/control-number/delete', [ControlNumberController::class, 'delete']);
    
    // ===== STUDENT PAYMENT ROUTES =====
    Route::get('/std_pay_acc', [StudentPaymentController::class, 'index'])->name('std.pay.acc');
    Route::post('/api/student-payment/generate-control', [StudentPaymentController::class, 'generateControlNumber']);
    Route::post('/api/student-payment/delete-control', [StudentPaymentController::class, 'deleteControlNumber']);
    Route::post('/api/student-payment/process-payment', [StudentPaymentController::class, 'processPayment']);
    
    // ===== STAFF ROUTES =====
    Route::get('/staff/new', [App\Http\Controllers\StaffManagementController::class, 'newStaff'])->name('staff.new');
    Route::get('/staff/profile', [App\Http\Controllers\StaffManagementController::class, 'staffProfile'])->name('staff.profile');
    Route::get('/api/staff/list', [App\Http\Controllers\StaffManagementController::class, 'getStaffList']);
    Route::post('/api/staff/store', [App\Http\Controllers\StaffManagementController::class, 'storeStaff']);
    Route::post('/api/staff/update', [App\Http\Controllers\StaffManagementController::class, 'updateStaff']);
    Route::get('/api/staff/{id}', [App\Http\Controllers\StaffManagementController::class, 'getStaffById']);
    
    // ===== STAFF STUDENTS ADDITIONAL API =====
    Route::get('/api/staff-students-all', [StaffStudentController::class, 'getAllStudents']);
    Route::get('/api/staff-students-search', [StaffStudentController::class, 'searchStudents']);
    Route::get('/api/staff-students-list', [StaffStudentController::class, 'listStaffStudents']);
    Route::post('/api/staff-students-add', [StaffStudentController::class, 'addStaffStudent']);
    Route::post('/api/staff-students-remove', [StaffStudentController::class, 'removeStaffStudent']);
    Route::post('/api/staff-students-pay', [StaffStudentController::class, 'processPayment']);
    Route::post('/api/staff-students-view', [StaffStudentController::class, 'viewStudent']);
});

// ===== ACCOUNTS ROUTES =====
Route::middleware(['auth', 'check.password'])->group(function () {
    Route::get('/register-staff-students', function() { return view('accounts.register_staff_students'); });
    Route::get('/staff-student-payment', function() { return view('accounts.staff_student_payment'); });
    Route::get('/set-budget', function() { return view('accounts.set_budget'); });
    Route::get('/zra-receipts', function() { return view('accounts.zra_receipts'); });
    Route::get('/payroll', function() { return view('accounts.payroll'); });
    Route::get('/pay-bills', function() { return view('accounts.pay_bills'); });
});

// ===== ACADEMIC ROUTES =====
Route::middleware(['auth', 'check.password'])->group(function () {
    Route::get('/attendance', function() { return view('academic.attendance'); });
    Route::get('/print-class-results', function() { return view('academic.print_class_results'); });
    Route::get('/print-students-results', function() { return view('academic.print_students_results'); });
    Route::get('/class-list', function() { return view('academic.class_list'); });
});

// ===== REPORTS ROUTES =====
Route::middleware(['auth', 'check.password'])->group(function () {
    Route::get('/revenue-summary', function() { return view('reports.revenue_summary'); });
    Route::get('/revenue-statement', function() { return view('reports.revenue_statement'); });
    Route::get('/debtors-statement', function() { return view('reports.debtors_statement'); });
    Route::get('/income-expenses', function() { return view('reports.income_expenses'); });
    Route::get('/tax-return', function() { return view('reports.tax_return'); });
});

// ===== PAY BILLS API ROUTES =====
Route::middleware(['auth', 'check.password'])->group(function () {
    Route::get('/api/pay-bills-categories', function() {
        $categories = ['SALARIES AND WAGES', 'ADMINISTRATIVE EXPENSES', 'OTHER COST OF OPERATION', 'INTEREST AND FINANCIAL EXPENSES', 'REPAIR AND MAINTAINANCE', 'DEPRECIATION'];
        return response()->json(['success' => true, 'data' => $categories]);
    });
    
    Route::get('/api/pay-bills-items', function(Request $request) {
        $allItems = [
            'SALARIES AND WAGES' => ['Teachers Salary', 'Staff Salary', 'Overtime', 'Bonuses'],
            'ADMINISTRATIVE EXPENSES' => ['Office Supplies', 'Electricity', 'Water', 'Internet', 'Phone'],
            'OTHER COST OF OPERATION' => ['Transport', 'Food & Catering', 'Security', 'Cleaning'],
            'INTEREST AND FINANCIAL EXPENSES' => ['Bank Charges', 'Loan Interest', 'Insurance'],
            'REPAIR AND MAINTAINANCE' => ['Building Repair', 'Vehicle Repair', 'Equipment Repair'],
            'DEPRECIATION' => ['Building Depreciation', 'Vehicle Depreciation', 'Equipment Depreciation']
        ];
        $items = $allItems[$request->category] ?? [];
        $data = array_map(function($item) { return (object)['item' => $item]; }, $items);
        return response()->json(['success' => true, 'data' => $data]);
    });
    
    Route::post('/api/pay-bills-save', function(\Illuminate\Http\Request $request) {
        DB::table('voucher')->insert([
            'supplier' => $request->supplier,
            'date' => $request->date,
            'cost' => $request->amount,
            'app' => ($request->category ?? '') . ' - ' . ($request->item ?? ''),
            'status' => 'Request'
        ]);
        $vid = DB::getPdo()->lastInsertId();
        return response()->json(['success' => true, 'message' => 'Voucher saved! VID: ' . $vid]);
    });
    
    Route::get('/api/pay-bills-list', function(Request $request) {
        $search = $request->search ?? '';
        $bills = DB::table('voucher')
            ->when($search, function($q) use ($search) {
                $q->where('supplier', 'like', "%{$search}%")->orWhere('app', 'like', "%{$search}%");
            })->orderBy('date', 'desc')->limit(100)->get();
        return response()->json(['success' => true, 'data' => $bills]);
    });
    
    Route::post('/api/pay-bills-delete', function(Request $request) {
        DB::table('voucher')->where('vid', $request->id)->delete();
        return response()->json(['success' => true, 'message' => 'Voucher deleted']);
    });
});

Route::post('/api/set-budget-add', function(\Illuminate\Http\Request $request) {
    DB::table('bajeti')->insert([
        'section' => $request->section,
        'iterm' => $request->iterm,
        'amount' => $request->amount,
        'year' => date('Y')
    ]);
    return response()->json(['success' => true, 'message' => 'Budget added!']);
});

Route::get('/api/set-budget-list', function() {
    $data = DB::table('bajeti')->where('year', date('Y'))->orderBy('id', 'desc')->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::post('/api/set-budget-delete', function(\Illuminate\Http\Request $request) {
    DB::table('bajeti')->where('id', $request->id)->delete();
    return response()->json(['success' => true, 'message' => 'Budget deleted']);
});

Route::get('/api/zra-receipts-list', function(\Illuminate\Http\Request $request) {
    $dts = $request->dts;
    $dtf = $request->dtf;
    $query = DB::table('receipt')->where('cost', '!=', '0');
    if ($dts && $dtf) {
        $query->whereBetween('date', [$dts, $dtf]);
    } else {
        $mnt = date('Y-m');
        $query->where('date', 'like', "%{$mnt}%")->limit(50);
    }
    $data = $query->orderBy('date', 'desc')->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::post('/api/zra-pay', function(Request $request) {
    $invo = $request->invo;
    // Check if already paid
    $check = DB::table('zra_taxes')->where('invoice_number', $invo)->first();
    if ($check) {
        return response()->json(['success' => false, 'message' => 'Already paid']);
    }
    // Get receipt info
    $receipt = DB::table('receipt')->where('invoid', $invo)->first();
    $allpayment = DB::table('allpayment')->where('invo', $invo)->first();
    if (!$receipt || !$allpayment) {
        return response()->json(['success' => false, 'message' => 'Receipt not found']);
    }
    // Mark as paid
    DB::table('zra_taxes')->insert([
        'student_name' => $receipt->name,
        'item_name' => $allpayment->category ?? 'Fee',
        'total_amount' => $receipt->cost,
        'tax_amount' => 0,
        'amount_after' => $receipt->cost,
        'receipt_number' => $invo,
        'reference_number' => 'REF-' . time(),
        'response_number' => 'RES-' . time(),
        'issue_date' => now(),
        'registration_number' => $receipt->reg,
        'year' => $receipt->year,
        'class' => $allpayment->class ?? '',
        'level' => $allpayment->level ?? '',
        'invoice_number' => $invo
    ]);
    DB::table('receipt')->where('invoid', $invo)->update(['zra_status' => 'Paid']);
    return response()->json(['success' => true, 'message' => 'ZRA Paid successfully!']);
});

Route::get('/api/attendance-list', function(\Illuminate\Http\Request $request) {
    $date = $request->date ?? date('Y-m-d');
    $year = date('Y');
    
    $classes = DB::table('enrollment')
        ->select('class', DB::raw('COUNT(reg) as total'))
        ->where('year', $year)
        ->groupBy('class')
        ->orderBy('class')
        ->get();
    
    $attendance = DB::table('attendance')
        ->select('class', 'status', DB::raw('COUNT(*) as count'))
        ->where('date', $date)
        ->groupBy('class', 'status')
        ->get();
    
    $attMap = [];
    foreach ($attendance as $a) {
        $attMap[$a->class][$a->status] = $a->count;
    }
    
    foreach ($classes as $c) {
        $c->present = $attMap[$c->class]['Present'] ?? 0;
        $c->absent = $attMap[$c->class]['Absent'] ?? 0;
        $c->reason = $attMap[$c->class]['Reason'] ?? 0;
    }
    
    return response()->json(['success' => true, 'data' => $classes]);
});

Route::get('/api/class-list-dropdown', function() {
    $classes = DB::table('class')->orderBy('level')->get(['class', 'level']);
    return response()->json(['success' => true, 'data' => $classes]);
});

Route::get('/api/class-results', function(\Illuminate\Http\Request $request) {
    $results = DB::table('matokeo')
        ->join('student', 'matokeo.reg', '=', 'student.reg')
        ->where('matokeo.class', $request->class)
        ->where('matokeo.examtp', $request->examtp)
        ->where('matokeo.acyear', $request->acyear)
        ->select('matokeo.*', 'student.sname')
        ->orderBy('matokeo.position')
        ->get();
    return response()->json(['success' => true, 'data' => $results]);
});

Route::get('/api/student-results-summary', function(\Illuminate\Http\Request $request) {
    $data = DB::table('stream_pos')
        ->join('student', 'stream_pos.reg', '=', 'student.reg')
        ->where('stream_pos.class', $request->class)
        ->where('stream_pos.term', $request->examtp)
        ->where('stream_pos.year', $request->acyear)
        ->select('stream_pos.*', 'student.sname')
        ->orderBy('stream_pos.pos')
        ->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::get('/api/print-report-card', function(\Illuminate\Http\Request $request) {
    $reg = $request->reg;
    $exam = $request->exam;
    $year = $request->year;
    $class = $request->class;
    
    $student = DB::table('student')->where('reg', $reg)->first();
    $enrollment = DB::table('enrollment')->where('reg', $reg)->where('class', $class)->where('year', $year)->first();
    $results = DB::table('matokeo')->where('reg', $reg)->where('examtp', $exam)->where('acyear', $year)->orderBy('marks', 'desc')->get();
    $stream = DB::table('stream_pos')->where('reg', $reg)->where('term', $exam)->where('year', $year)->first();
    $totalStudents = DB::table('enrollment')->where('class', $class)->where('year', $year)->count();
    $present = DB::table('attendance')->where('reg', $reg)->where('class', $class)->where('year', $year)->where('status', 'Present')->count();
    
    $totalMarks = 0; $subjects = 0;
    foreach ($results as $r) { $totalMarks += $r->marks; $subjects++; }
    $avg = $subjects > 0 ? round($totalMarks / $subjects, 2) : 0;
    
    // Grade system
    if ($avg >= 81) $grade = 'A';
    elseif ($avg >= 61) $grade = 'B';
    elseif ($avg >= 45) $grade = 'C';
    elseif ($avg >= 35) $grade = 'D';
    else $grade = 'F';
    
    $html = '<!DOCTYPE html><html><head><title>Report Card</title>';
    $html .= '<style>body{font-family:Arial;padding:20px}.report{max-width:800px;margin:0 auto;border:1px solid #000;padding:15px}.header{text-align:center}.header img{width:100%;max-height:150px}.info-table{width:100%;border-collapse:collapse;margin:10px 0}.info-table td{border:1px solid #000;padding:5px}.marks-table{width:100%;border-collapse:collapse;margin:10px 0}.marks-table th,.marks-table td{border:1px solid #000;padding:5px;font-size:12px}.marks-table th{background:#C0C0C0}.summary{width:100%;border-collapse:collapse;margin:10px 0}.summary td{border:1px solid #000;padding:5px}.grade-table{width:100%;border-collapse:collapse;margin:10px 0;font-size:11px}.grade-table th,.grade-table td{border:1px solid #000;padding:3px;text-align:center}.grade-table th{background:#CCC}.signatures{width:100%;margin-top:20px}.signatures td{width:50%;text-align:center;padding:20px}@media print{body{margin:0;padding:0}}</style></head><body>';
    $html .= '<div class="report">';
    $html .= '<div class="header"><img src="https://i.ibb.co/gMfmMQ2B/logo.png" style="width:80px;height:80px;border-radius:50%;display:block;margin:0 auto"><h2>GLORIOUS ACADEMY</h2><h3>'.strtoupper($exam).' EXAMINATION REPORT - '.$year.'</h3></div>';
    $html .= '<table class="info-table">';
    $html .= '<tr><td><b>REG NO:</b> '.$reg.'</td><td><b>NAME:</b> '.($student->sname ?? '').'</td><td><b>GENDER:</b> '.($student->gender ?? '').'</td></tr>';
    $html .= '<tr><td><b>CLASS:</b> '.$class.'</td><td><b>LEVEL:</b> '.($enrollment->level ?? $student->level ?? '').'</td><td><b>ROLL:</b> '.$totalStudents.'</td></tr>';
    $html .= '</table>';
    $html .= '<table class="marks-table"><tr><th>SUBJECT</th><th>MARKS</th><th>GRADE</th><th>POSITION</th><th>REMARKS</th></tr>';
    
    foreach ($results as $r) {
        $g = $r->marks >= 81 ? 'A' : ($r->marks >= 61 ? 'B' : ($r->marks >= 45 ? 'C' : ($r->marks >= 35 ? 'D' : 'F')));
        $remark = $r->marks >= 81 ? 'Excellent' : ($r->marks >= 61 ? 'Very Good' : ($r->marks >= 45 ? 'Satisfactory' : ($r->marks >= 35 ? 'Unsatisfactory' : 'Failure')));
        $html .= '<tr><td>'.$r->subject.'</td><td>'.$r->marks.'</td><td>'.$g.'</td><td>'.$r->position.'</td><td>'.$remark.'</td></tr>';
    }
    $html .= '</table>';
    $html .= '<table class="summary"><tr><td><b>TOTAL MARKS:</b> '.$totalMarks.'</td><td><b>AVERAGE:</b> '.$avg.'</td><td><b>POSITION:</b> '.($stream->pos ?? '-').'</td><td><b>DIVISION:</b> '.($stream->division ?? $grade).'</td></tr></table>';
    $html .= '<table class="grade-table"><tr><th>Grade</th><th>A</th><th>B</th><th>C</th><th>D</th><th>F</th></tr><tr><td>Score</td><td>81-100</td><td>61-80</td><td>45-60</td><td>35-44</td><td>0-34</td></tr><tr><td>Remark</td><td>Excellent</td><td>Very Good</td><td>Satisfactory</td><td>Unsatisfactory</td><td>Failure</td></tr></table>';
    $html .= '<table class="signatures"><tr><td>CLASS TEACHER<br>SIGN...............</td><td>ACADEMIC OFFICER<br>SIGN...............</td></tr></table>';
    $html .= '</div><script>window.onload=function(){window.print();setTimeout(function(){window.close();},1000)}<\/script></body></html>';
    
    return $html;
});

Route::get('/api/class-list-data', function(\Illuminate\Http\Request $request) {
    $data = DB::table('student')
        ->select('class', DB::raw('COUNT(reg) as total'))
        ->where('class', '!=', 'Unknown')
        ->where('year', $request->year)
        ->groupBy('class')
        ->orderBy('class')
        ->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::get('/api/revenue-summary', function(\Illuminate\Http\Request $request) {
    $year = $request->year;
    $data = DB::table('payment')
        ->join('enrollment', 'payment.reg', '=', 'enrollment.reg')
        ->where('payment.year', $year)
        ->where('enrollment.year', $year)
        ->select('payment.category', DB::raw('COUNT(payment.reg) as student'), DB::raw('SUM(payment.amount) as amount'), DB::raw('SUM(payment.balance) as balance'))
        ->groupBy('payment.category')
        ->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::get('/api/revenue-statement', function(\Illuminate\Http\Request $request) {
    $fee = $request->fee;
    $year = $request->year;
    $data = DB::table('payment')
        ->join('enrollment', 'payment.reg', '=', 'enrollment.reg')
        ->where('payment.year', $year)
        ->where('enrollment.year', $year)
        ->where('payment.category', 'like', "%{$fee}%")
        ->select('enrollment.reg', 'enrollment.sname', 'enrollment.level', 'enrollment.class', 'payment.year', 'payment.category', 'payment.amount', 'payment.balance')
        ->orderBy('enrollment.class')
        ->orderBy('enrollment.sname')
        ->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::get('/api/debtors-statement', function(\Illuminate\Http\Request $request) {
    $fee = $request->fee;
    $year = $request->year;
    $data = DB::table('payment')
        ->join('enrollment', 'payment.reg', '=', 'enrollment.reg')
        ->where('payment.year', $year)
        ->where('enrollment.year', $year)
        ->where('payment.balance', '!=', '0')
        ->where('payment.category', 'like', "%{$fee}%")
        ->select('enrollment.reg', 'enrollment.sname', 'enrollment.level', 'enrollment.class', 'payment.year', 'payment.category', 'payment.amount', 'payment.balance')
        ->orderBy('enrollment.class')->orderBy('enrollment.sname')
        ->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::get('/api/income-expenses', function(\Illuminate\Http\Request $request) {
    $year = $request->year;
    $income = DB::table('allpayment')->select('category', DB::raw('SUM(amount) as amount'))->where('amount', '!=', '0')->where('year', $year)->groupBy('category')->get();
    $expense = DB::table('expenditure')->select('part', DB::raw('SUM(cost) as cost'))->where('year', $year)->groupBy('part')->get();
    return response()->json(['success' => true, 'income' => $income, 'expense' => $expense]);
});

Route::get('/api/tax-return', function(\Illuminate\Http\Request $request) {
    $dts = $request->dts;
    $dtf = $request->dtf;
    $query = DB::table('zra_taxes');
    if ($dts && $dtf) {
        $query->whereBetween(DB::raw('DATE(issue_date)'), [$dts, $dtf]);
    } else {
        $mnt = date('Y-m');
        $query->where('issue_date', 'like', "%{$mnt}%");
    }
    $data = $query->orderBy('issue_date', 'desc')->get();
    return response()->json(['success' => true, 'data' => $data]);
});

Route::get('/api/dashboard-revenue', function() {
    $revenue = DB::table('receipt')->sum('cost');
    return response()->json(['success' => true, 'revenue' => $revenue]);
});

Route::post('/api/change-password', function(\Illuminate\Http\Request $request) {
    $user = Auth::user();
    $password = $request->password;
    if (strlen($password) < 6) {
        return response()->json(['success' => false, 'message' => 'Password must be at least 6 characters']);
    }
    DB::table('staff')->where('id', $user->id)->update(['password' => Hash::make($password)]);
    return response()->json(['success' => true, 'message' => 'Password changed successfully!']);
});

Route::get('/change-password', function() {
    if (!Auth::check()) return redirect('/staff-login');
    return view('change-password');
})->name('change.password');

Route::post('/api/change-password', function(\Illuminate\Http\Request $request) {
    $user = Auth::user();
    $password = $request->password;
    if (strlen($password) < 6) {
        return response()->json(['success' => false, 'message' => 'Password must be at least 6 characters']);
    }
    DB::table('staff')->where('id', $user->id)->update(['password' => Hash::make($password)]);
    return response()->json(['success' => true, 'message' => 'Password changed successfully!']);
});

Route::get('/api/dashboard-stats', function() {    $students = DB::table('enrollment')->where('year', date('Y'))->count();    $attendance = DB::table('attendance')->count();    return response()->json(['success' => true, 'students' => $students, 'attendance' => $attendance]);});







Route::get('/api/student/payment-years', function(\Illuminate\Http\Request $request) {
    $years = DB::table('payment')->where('reg', $request->reg)->select('year')->distinct()->orderBy('year','desc')->pluck('year');
    return response()->json(['success' => true, 'data' => $years]);
});

Route::get('/api/student/payment', function(\Illuminate\Http\Request $request) {
    $reg = $request->reg; $year = $request->year;
    $bills = DB::table('payment')->where('reg', $reg)->when($year, fn($q) => $q->where('year', $year))->get();
    $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');
    return response()->json(['success' => true, 'data' => ['bills' => $bills, 'total_balance' => $totalBalance]]);
});

Route::get('/api/student/exam-filters', function(\Illuminate\Http\Request $request) {
    $years = DB::table('matokeo')->where('reg', $request->reg)->select('acyear')->distinct()->orderBy('acyear','desc')->pluck('acyear');
    $types = DB::table('matokeo')->where('reg', $request->reg)->select('examtp')->distinct()->pluck('examtp');
    return response()->json(['success' => true, 'data' => ['years' => $years, 'types' => $types]]);
});

Route::get('/api/student/examinations', function(\Illuminate\Http\Request $request) {
    $results = DB::table('matokeo')->where('reg', $request->reg)
        ->when($request->year, fn($q) => $q->where('acyear', $request->year))
        ->when($request->exam_type, fn($q) => $q->where('examtp', $request->exam_type))
        ->orderBy('acyear','desc')->get();
    return response()->json(['success' => true, 'data' => ['results' => $results]]);
});

Route::get('/api/student/attendance-years', function(\Illuminate\Http\Request $request) {
    $years = DB::table('attendance')->where('reg', $request->reg)->select('year')->distinct()->orderBy('year','desc')->pluck('year');
    return response()->json(['success' => true, 'data' => $years]);
});

Route::get('/api/student/attendance', function(\Illuminate\Http\Request $request) {
    $reg = $request->reg; $year = $request->year;
    $present = DB::table('attendance')->where('reg', $reg)->where('status','Present')->when($year, fn($q) => $q->where('year', $year))->count();
    $absent = DB::table('attendance')->where('reg', $reg)->where('status','Absent')->when($year, fn($q) => $q->where('year', $year))->count();
    $reason = DB::table('attendance')->where('reg', $reg)->where('status','Reason')->when($year, fn($q) => $q->where('year', $year))->count();
    $records = DB::table('attendance')->where('reg', $reg)->when($year, fn($q) => $q->where('year', $year))->orderBy('date','desc')->limit(100)->get();
    return response()->json(['success' => true, 'data' => ['present' => $present, 'absent' => $absent, 'reason' => $reason, 'year' => $year, 'records' => $records]]);
});
