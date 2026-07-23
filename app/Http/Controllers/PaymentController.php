<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment.index');
    }

    public function showPayment()
    {
        $reg = Session::get('reg');
        if (empty($reg)) {
            return redirect()->route('payment.receive')->with('error', 'No student selected');
        }

        $student = DB::table('student')->where('reg', $reg)->first();
        if (!$student) {
            return redirect()->route('payment.receive')->with('error', 'Student not found');
        }

        $payments = DB::table('payment')->where('reg', $reg)->get();
        $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');

        return view('payment.index', compact('student', 'payments', 'totalBalance', 'reg'));
    }

    public function receivePayment()
    {
        return view('payment.receive');
    }

    public function searchStudents(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $key = $request->get('key', '');
            $year = date('Y');

            $query = DB::table('enrollment')
                ->select('reg', 'sname', 'level', 'class', 'gender', 'mobile')
                ->where('year', $year);

            if (!empty($key)) {
                $query->where(function($q) use ($key) {
                    $q->where('reg', 'like', "%{$key}%")
                        ->orWhere('sname', 'like', "%{$key}%")
                        ->orWhere('class', 'like', "%{$key}%");
                });
            }

            $students = $query->orderBy('sname', 'asc')->limit(50)->get();

            return response()->json(['success' => true, 'data' => $students]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function viewPayment(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = $request->get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'Registration number required'], 400);
            }

            Session::put('reg', $reg);

            return response()->json(['success' => true, 'redirect' => '/payment/account']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function paymentAccount()
    {
        $reg = Session::get('reg');
        if (empty($reg)) {
            return redirect()->route('payment.receive')->with('error', 'No student selected');
        }

        $student = DB::table('student')->where('reg', $reg)->first();
        if (!$student) {
            return redirect()->route('payment.receive')->with('error', 'Student not found');
        }

        $enrollment = DB::table('enrollment')->where('reg', $reg)->orderBy('sid', 'desc')->first();
        $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');
        $payments = DB::table('payment')->where('reg', $reg)->where('balance', '!=', '0')->get();

        return view('payment.account', compact('student', 'enrollment', 'totalBalance', 'reg', 'payments'));
    }

    public function transid()
    {
        $reg = Session::get('reg');
        if (empty($reg)) return redirect()->route('payment.receive');

        $student = DB::table('student')->where('reg', $reg)->first();
        $enrollment = DB::table('enrollment')->where('reg', $reg)->orderBy('sid', 'desc')->first();
        $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');
        $payments = DB::table('payment')->where('reg', $reg)->where('balance', '!=', '0')->get();
        $transactions = DB::table('receipt')->where('reg', $reg)->orderBy('date', 'desc')->get();

        return view('payment.account', compact('student', 'enrollment', 'totalBalance', 'payments', 'transactions'))->with('activeTab', 'transid');
    }

    public function bills()
    {
        $reg = Session::get('reg');
        if (empty($reg)) return redirect()->route('payment.receive');
        $student = DB::table('student')->where('reg', $reg)->first();
        $enrollment = DB::table('enrollment')->where('reg', $reg)->orderBy('sid', 'desc')->first();
        $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');
        $payments = DB::table('payment')->where('reg', $reg)->where('balance', '!=', '0')->get();
        return view('payment.account', compact('student', 'enrollment', 'totalBalance', 'payments'))->with('activeTab', 'bills');
    }

    public function receipts()
    {
        $reg = Session::get('reg');
        if (empty($reg)) return redirect()->route('payment.receive');
        $student = DB::table('student')->where('reg', $reg)->first();
        $enrollment = DB::table('enrollment')->where('reg', $reg)->orderBy('sid', 'desc')->first();
        $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');
        $payments = DB::table('payment')->where('reg', $reg)->where('balance', '!=', '0')->get();
        $transactions = DB::table('receipt')->where('reg', $reg)->orderBy('date', 'desc')->get();
        return view('payment.account', compact('student', 'enrollment', 'totalBalance', 'payments', 'transactions'))->with('activeTab', 'receipts');
    }

    public function invoice()
    {
        $reg = Session::get('reg');
        if (empty($reg)) return redirect()->route('payment.receive');
        $student = DB::table('student')->where('reg', $reg)->first();
        $enrollment = DB::table('enrollment')->where('reg', $reg)->orderBy('sid', 'desc')->first();
        $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');
        $payments = DB::table('payment')->where('reg', $reg)->where('balance', '!=', '0')->get();
        $transactions = DB::table('receipt')->where('reg', $reg)->orderBy('date', 'desc')->get();
        return view('payment.account', compact('student', 'enrollment', 'totalBalance', 'payments', 'transactions'))->with('activeTab', 'invoice');
    }

    public function receipt()
    {
        $invo = Session::get('invo');
        if (empty($invo)) {
            return redirect()->route('payment.receive')->with('error', 'No receipt found');
        }

        $payments = DB::table('allpayment')->where('invo', $invo)->get();
        if ($payments->isEmpty()) {
            return redirect()->route('payment.receive')->with('error', 'Receipt not found');
        }

        $student = DB::table('student')->where('reg', $payments->first()->reg)->first();
        $total = $payments->sum('amount');

        return view('payment.receipt', compact('payments', 'student', 'invo', 'total'));
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

            $staff = Auth::user()->user ?? 'STAFF';
            $staffData = DB::table('staff')->where('user', $staff)->first();
            $stid = $staffData->id ?? 'STAFF';
            $invoiceCount = DB::table('receipt')->count();
            $invoid = $invoiceCount + 1;
            $invo = "GAS{$stid}{$invoid}";
            $amount = $payment->balance;
            $dt = date('Y-m-d');

            $enrollment = DB::table('enrollment')->where('reg', $reg)->where('year', $payment->year)->first();
            $class = $enrollment->class ?? '';
            $level = $enrollment->level ?? '';

            DB::table('allpayment')->insert([
                'reg' => $reg, 'name' => $sname, 'category' => $payment->category,
                'amount' => $amount, 'year' => $payment->year, 'class' => $class,
                'level' => $level, 'date' => $dt, 'invo' => $invo
            ]);

            DB::table('payment')->where('id', $catid)->where('reg', $reg)->update(['balance' => 0]);

            DB::table('receipt')->insert([
                'invoid' => $invo, 'reg' => $reg, 'name' => $sname,
                'cost' => $amount, 'date' => $dt, 'user' => $staff,
                'mode' => 'CASH', 'year' => $payment->year
            ]);

            DB::table('logs')->insert([
                'user' => $staff, 'action' => "Receive {$amount} receipt {$invo}", 'date' => $dt
            ]);

            Session::put('invo', $invo);

            return response()->json(['success' => true, 'message' => 'Payment processed successfully!', 'redirect' => '/payment/receipt']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
