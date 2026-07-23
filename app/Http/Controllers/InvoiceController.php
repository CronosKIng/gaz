<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('invoices.index');
    }

    public function getInvoices($reg = null)
    {
        try {
            if (!$reg) {
                $reg = Session::get('reg', '');
            }

            if (empty($reg)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student selected'
                ], 400);
            }

            // Get all invoices for this student
            $invoices = DB::table('allpayment')
                ->where('reg', $reg)
                ->orderBy('date', 'desc')
                ->get();

            // Get student details
            $student = DB::table('student')
                ->where('reg', $reg)
                ->first();

            return response()->json([
                'success' => true,
                'invoices' => $invoices,
                'student' => $student
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getInvoice($id)
    {
        try {
            $invoice = DB::table('allpayment')
                ->where('id', $id)
                ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            // Get student details
            $student = DB::table('student')
                ->where('reg', $invoice->reg)
                ->first();

            // Get payment details
            $payments = DB::table('payment')
                ->where('reg', $invoice->reg)
                ->where('category', $invoice->category)
                ->where('year', $invoice->year)
                ->get();

            return response()->json([
                'success' => true,
                'invoice' => $invoice,
                'student' => $student,
                'payments' => $payments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function printInvoice(Request $request)
    {
        try {
            $id = $request->get('id');
            
            $invoice = DB::table('allpayment')
                ->where('id', $id)
                ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            $student = DB::table('student')
                ->where('reg', $invoice->reg)
                ->first();

            return view('invoices.print', compact('invoice', 'student'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
