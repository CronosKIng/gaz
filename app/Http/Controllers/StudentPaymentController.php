<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentPaymentController extends Controller
{
    public function index()
    {
        $reg = Session::get('reg');
        if (empty($reg)) {
            return redirect()->route('payment.receive')->with('error', 'No student selected');
        }

        $student = DB::table('student')->where('reg', $reg)->first();
        if (!$student) {
            return redirect()->route('payment.receive')->with('error', 'Student not found');
        }

        $enrollment = DB::table('enrollment')
            ->where('reg', $reg)
            ->orderBy('sid', 'desc')
            ->first();

        return view('std_pay_acc', compact('student', 'enrollment', 'reg'));
    }

    public function generateControlNumber(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            $catid = $request->get('catid');

            if (empty($reg) || empty($catid)) {
                return response()->json(['success' => false, 'message' => 'Missing required fields'], 400);
            }

            $student = DB::table('student')->where('reg', $reg)->first();
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }

            $payment = DB::table('payment')->where('id', $catid)->where('reg', $reg)->first();
            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Payment record not found'], 404);
            }

            $cn = rand(1000, 9999) . date('Y') . rand(100, 999);
            $controlNumber = "CTRL-{$cn}";
            $dt = date('Y-m-d');

            DB::table('namba')->insert([
                'cnamba' => $controlNumber,
                'reg' => $reg,
                'sname' => $student->sname,
                'category' => $payment->category,
                'amount' => $payment->balance,
                'year' => $payment->year,
                'class' => $student->class ?? '',
                'level' => $student->level ?? '',
                'sdate' => $dt,
                'edate' => '2050-12-31',
                'status' => 'NOT PAID',
                'user' => Auth::user()->user ?? 'STAFF',
                'telno' => $student->pgmob ?? '',
                'balance' => $payment->balance
            ]);

            DB::table('payment')
                ->where('id', $catid)
                ->where('reg', $reg)
                ->update(['cnamba' => $controlNumber]);

            return response()->json([
                'success' => true,
                'message' => 'Control number generated successfully!',
                'control_number' => $controlNumber
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteControlNumber(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $cnamba = $request->get('cnamba');
            if (empty($cnamba)) {
                return response()->json(['success' => false, 'message' => 'Control number required'], 400);
            }

            DB::table('namba')
                ->where('cnamba', $cnamba)
                ->update(['status' => 'Canceled']);

            DB::table('payment')
                ->where('cnamba', $cnamba)
                ->update(['cnamba' => 'NONE']);

            return response()->json([
                'success' => true,
                'message' => 'Control number deleted successfully!'
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

            $reg = Session::get('reg');
            $catid = $request->get('catid');
            $sname = $request->get('sname');

            if (empty($reg) || empty($catid)) {
                return response()->json(['success' => false, 'message' => 'Missing required fields'], 400);
            }

            $payment = DB::table('payment')->where('id', $catid)->where('reg', $reg)->first();
            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Payment record not found'], 404);
            }

            $student = DB::table('student')->where('reg', $reg)->first();

            $staff = Auth::user()->user ?? 'STAFF';
            $staffData = DB::table('staff')->where('user', $staff)->first();
            $stid = $staffData->id ?? 'STAFF';
            $invoiceCount = DB::table('receipt')->count();
            $invoid = $invoiceCount + 1;
            $invo = "GAS{$stid}{$invoid}";

            $amount = $payment->balance;
            $dt = date('Y-m-d');

            $enrollment = DB::table('enrollment')
                ->where('reg', $reg)
                ->where('year', $payment->year)
                ->first();

            $class = $enrollment->class ?? '';
            $level = $enrollment->level ?? '';

            DB::table('allpayment')->insert([
                'reg' => $reg,
                'name' => $sname,
                'category' => $payment->category,
                'amount' => $amount,
                'year' => $payment->year,
                'class' => $class,
                'level' => $level,
                'date' => $dt,
                'invo' => $invo
            ]);

            DB::table('payment')
                ->where('id', $catid)
                ->where('reg', $reg)
                ->update(['balance' => 0]);

            DB::table('receipt')->insert([
                'invoid' => $invo,
                'reg' => $reg,
                'name' => $sname,
                'cost' => $amount,
                'date' => $dt,
                'user' => $staff,
                'mode' => 'CASH',
                'year' => $payment->year
            ]);

            DB::table('logs')->insert([
                'user' => $staff,
                'action' => "Receive {$amount} receipt {$invo}",
                'date' => $dt
            ]);

            Session::put('invo', $invo);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
