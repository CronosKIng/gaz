<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayBillsController extends Controller
{
    public function index()
    {
        $bills = DB::table('bills')->orderBy('date', 'desc')->get();
        return view('accounts.pay_bills', compact('bills'));
    }

    public function getCategories()
    {
        $categories = ['SALARIES AND WAGES', 'ADMINISTRATIVE EXPENSES', 'OTHER COST OF OPERATION', 'INTEREST AND FINANCIAL EXPENSES', 'REPAIR AND MAINTAINANCE', 'DEPRECIATION'];
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function getItems(Request $request)
    {
        $category = $request->category;
        $items = DB::table('bitems')
            ->where('category', $category)
            ->orWhere('category', 'ALL')
            ->orderBy('item', 'asc')
            ->get();
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function saveBill(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'item' => 'required',
            'supplier' => 'required',
            'date' => 'required',
            'amount' => 'required|numeric'
        ]);

        $staff = Auth::user()->user ?? 'STAFF';
        $voucherNo = 'VCH' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        DB::table('bills')->insert([
            'voucher_no' => $voucherNo,
            'category' => $request->category,
            'item' => $request->item,
            'supplier' => $request->supplier,
            'date' => $request->date,
            'amount' => $request->amount,
            'staff' => $staff,
            'status' => 'Pending',
            'created_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Bill saved successfully! Voucher: ' . $voucherNo]);
    }

    public function getBills(Request $request)
    {
        $search = $request->search ?? '';
        $query = DB::table('bills')->orderBy('date', 'desc');
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        
        $bills = $query->limit(100)->get();
        return response()->json(['success' => true, 'data' => $bills]);
    }

    public function deleteBill(Request $request)
    {
        $id = $request->id;
        DB::table('bills')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Bill deleted']);
    }
}
