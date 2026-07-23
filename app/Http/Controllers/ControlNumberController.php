<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ControlNumberController extends Controller
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

        $totalBalance = DB::table('payment')->where('reg', $reg)->sum('balance');

        // Get control numbers - using sn (primary key) for ordering
        $controlNumbers = DB::table('namba')
            ->where('reg', $reg)
            ->orderBy('sn', 'desc')
            ->get();

        // Get payment categories for generating new control numbers
        $categories = DB::table('payment')
            ->where('reg', $reg)
            ->where('balance', '!=', '0')
            ->where('cnamba', 'NONE')
            ->get();

        return view('control_numbers', compact(
            'student',
            'enrollment',
            'totalBalance',
            'reg',
            'controlNumbers',
            'categories'
        ));
    }

    public function generate(Request $request)
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

            // Generate control number
            $cn = rand(1000, 9999) . date('Y') . rand(100, 999);
            $controlNumber = "CTRL-{$cn}";
            $dt = date('Y-m-d');

            // Insert into namba table
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
                'balance' => $payment->balance,
                'payoption' => 'EXACT'
            ]);

            // Update payment with control number
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

    public function delete(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $cnamba = $request->get('cnamba');
            if (empty($cnamba)) {
                return response()->json(['success' => false, 'message' => 'Control number required'], 400);
            }

            // Update namba status
            DB::table('namba')
                ->where('cnamba', $cnamba)
                ->update(['status' => 'Canceled']);

            // Update payment
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
}
