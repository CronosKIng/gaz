<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentPaymentApiController extends Controller
{
    public function getProfile(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'No student selected'], 400);
            }

            $student = DB::table('student')->where('reg', $reg)->first();
            $enrollment = DB::table('enrollment')
                ->where('reg', $reg)
                ->orderBy('sid', 'desc')
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'student' => $student,
                    'enrollment' => $enrollment
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getPayments(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'No student selected'], 400);
            }

            $categories = DB::table('payment')
                ->where('reg', $reg)
                ->where('balance', '!=', '0')
                ->get();

            $totalBalance = DB::table('payment')
                ->where('reg', $reg)
                ->sum('balance');

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories,
                    'totalBalance' => $totalBalance
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getControlNumbers(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'No student selected'], 400);
            }

            $controlNumbers = DB::table('namba')
                ->where('reg', $reg)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $controlNumbers
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getTransactions(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'No student selected'], 400);
            }

            $transactions = DB::table('receipt')
                ->where('reg', $reg)
                ->orderBy('date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getBills(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'No student selected'], 400);
            }

            $bills = DB::table('allpayment')
                ->where('reg', $reg)
                ->orderBy('date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bills
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getReceipts(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'No student selected'], 400);
            }

            $receipts = DB::table('receipt')
                ->where('reg', $reg)
                ->orderBy('date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $receipts
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getInvoices(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = Session::get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'No student selected'], 400);
            }

            $invoices = DB::table('allpayment')
                ->where('reg', $reg)
                ->select('invo')
                ->distinct()
                ->orderBy('date', 'desc')
                ->get();

            $result = [];
            foreach ($invoices as $inv) {
                $items = DB::table('allpayment')
                    ->where('invo', $inv->invo)
                    ->get();
                $result[] = [
                    'invo' => $inv->invo,
                    'total' => $items->sum('amount'),
                    'items' => $items->count(),
                    'date' => $items->first()->date ?? '',
                    'details' => $items
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
