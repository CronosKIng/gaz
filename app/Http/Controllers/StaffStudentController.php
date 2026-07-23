<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffStudentController extends Controller
{
    public function index()
    {
        return view('students.staff');
    }

    public function getStaffStudents(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $search = $request->get('search', '');
            $year = date('Y');

            $query = DB::table('student')
                ->select('reg', 'sname', 'staff', 'class', 'gender')
                ->where('staff', '!=', 'NONE')
                ->where('reg', '!=', 'NULL');

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('reg', 'like', "%{$search}%")
                        ->orWhere('sname', 'like', "%{$search}%")
                        ->orWhere('class', 'like', "%{$search}%")
                        ->orWhere('staff', 'like', "%{$search}%");
                });
            }

            $students = $query->orderBy('staff', 'asc')->get();

            foreach ($students as $student) {
                $firstTerm = DB::table('payment')
                    ->where('reg', $student->reg)
                    ->where('category', 'First term')
                    ->where('year', $year)
                    ->first();
                
                $student->first_balance = $firstTerm->balance ?? 0;
                $student->first_status = ($firstTerm && $firstTerm->balance == 0) ? 'Paid' : 'First';
                $student->first_color = ($firstTerm && $firstTerm->balance == 0) ? 'red' : 'orange';
                $student->first_disabled = ($firstTerm && $firstTerm->balance == 0) ? 'disabled' : '';

                $secondTerm = DB::table('payment')
                    ->where('reg', $student->reg)
                    ->where('category', 'Second term')
                    ->where('year', $year)
                    ->first();
                
                $student->second_balance = $secondTerm->balance ?? 0;
                $student->second_status = ($secondTerm && $secondTerm->balance == 0) ? 'Paid' : 'Second';
                $student->second_color = ($secondTerm && $secondTerm->balance == 0) ? 'red' : 'orange';
                $student->second_disabled = ($secondTerm && $secondTerm->balance == 0) ? 'disabled' : '';
            }

            return response()->json([
                'success' => true,
                'data' => $students,
                'count' => $students->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function processPayment(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = $request->get('reg');
            $term = $request->get('term');
            $staff = Auth::user()->user ?? 'staff';
            $dt = date('Y-m-d');
            $year = date('Y');

            $student = DB::table('student')->where('reg', $reg)->first();
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }

            $staffData = DB::table('staff')->where('user', $staff)->first();
            $stid = $staffData->id ?? 'STAFF';

            $category = ($term === 'first') ? 'First term' : 'Second term';
            $payment = DB::table('payment')
                ->where('reg', $reg)
                ->where('category', $category)
                ->where('year', $year)
                ->first();

            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Payment record not found'], 404);
            }

            $invoiceCount = DB::table('receipt')->count();
            $invoid = $invoiceCount + 1;
            $invo = "GAS{$stid}{$invoid}";

            $amount = $payment->balance;
            $cat = $payment->category;
            $year_pay = $payment->year;
            $catid = $payment->id;

            DB::table('allpayment')->insert([
                'reg' => $reg,
                'name' => $student->sname,
                'category' => $cat,
                'amount' => $amount,
                'year' => $year_pay,
                'class' => $student->class,
                'level' => $student->level,
                'date' => $dt,
                'invo' => $invo
            ]);

            DB::table('payment')
                ->where('reg', $reg)
                ->where('id', $catid)
                ->update(['balance' => 0]);

            DB::table('receipt')->insert([
                'invoid' => $invo,
                'reg' => $reg,
                'name' => $student->sname,
                'cost' => $amount,
                'date' => $dt,
                'user' => $staff,
                'mode' => 'PBZ BANK',
                'year' => $year
            ]);

            DB::table('logs')->insert([
                'user' => $staff,
                'action' => "Receive {$amount} receipt {$invo}",
                'date' => $dt
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully!',
                'invo' => $invo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function viewStudent(Request $request)
    {
        try {
            $reg = $request->get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'Registration number required'], 400);
            }
            session(['reg' => $reg]);
            return response()->json(['success' => true, 'redirect' => '/payment/account']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAllStudents(Request $request)
    {
        $excludeStaff = $request->get('exclude_staff', 'NONE');
        $students = DB::table('student')
            ->select('reg', 'sname', 'class')
            ->where('staff', '!=', $excludeStaff)
            ->where('class', '!=', 'Unknown')
            ->orderBy('sname', 'asc')
            ->limit(50)
            ->get();
        return response()->json(['success' => true, 'data' => $students]);
    }

    public function searchStudents(Request $request)
    {
        $key = $request->get('search', '');
        $excludeStaff = $request->get('exclude_staff', '');
        $query = DB::table('student')
            ->select('reg', 'sname', 'class')
            ->where('class', '!=', 'Unknown');
        if (!empty($excludeStaff)) $query->where('staff', '!=', $excludeStaff);
        if (!empty($key)) {
            $query->where(function($q) use ($key) {
                $q->where('reg', 'like', "%{$key}%")
                  ->orWhere('sname', 'like', "%{$key}%")
                  ->orWhere('class', 'like', "%{$key}%");
            });
        }
        $students = $query->orderBy('sname', 'asc')->limit(50)->get();
        return response()->json(['success' => true, 'data' => $students]);
    }

    public function listStaffStudents(Request $request)
    {
        $staff = $request->get('staff', '');
        $students = DB::table('student')
            ->select('reg', 'sname', 'class')
            ->where('staff', $staff)
            ->orderBy('sname', 'asc')
            ->get();
        return response()->json(['success' => true, 'data' => $students]);
    }

    public function addStaffStudent(Request $request)
    {
        $reg = $request->get('reg');
        $staff = $request->get('staff');
        if (empty($reg) || empty($staff)) {
            return response()->json(['success' => false, 'message' => 'Missing data']);
        }
        DB::table('student')->where('reg', $reg)->update(['staff' => $staff]);
        DB::table('payment')->where('reg', $reg)->update(['staff' => $staff]);
        DB::table('allpayment')->where('reg', $reg)->update(['staff' => $staff]);
        return response()->json(['success' => true, 'message' => 'Student added to ' . $staff]);
    }

    public function removeStaffStudent(Request $request)
    {
        $reg = $request->get('reg');
        if (empty($reg)) {
            return response()->json(['success' => false, 'message' => 'Missing registration number']);
        }
        DB::table('student')->where('reg', $reg)->update(['staff' => 'NONE']);
        DB::table('payment')->where('reg', $reg)->update(['staff' => 'NONE']);
        DB::table('allpayment')->where('reg', $reg)->update(['staff' => 'NONE']);
        return response()->json(['success' => true, 'message' => 'Student removed']);
    }
}
