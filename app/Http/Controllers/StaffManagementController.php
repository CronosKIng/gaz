<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffManagementController extends Controller
{
    public function newStaff()
    {
        return view('staff.new');
    }

    public function staffProfile()
    {
        return view('staff.profile');
    }

    public function getStaffList()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $staff = DB::table('staff')
                ->select('id', 'user', 'name', 'contact', 'status')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeStaff(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'user' => 'required|string|max:30|unique:staff,user',
                'name' => 'required|string|max:50',
                'contact' => 'required|string|max:30',
                'status' => 'required|string|max:20',
                'password' => 'required|string|min:6'
            ]);

            $validated['password'] = Hash::make($validated['password']);

            DB::table('staff')->insert($validated);

            return response()->json([
                'success' => true,
                'message' => 'Staff member added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStaff(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'id' => 'required|integer',
                'name' => 'required|string|max:50',
                'contact' => 'required|string|max:30',
                'status' => 'required|string|max:20'
            ]);

            $data = [
                'name' => $validated['name'],
                'contact' => $validated['contact'],
                'status' => $validated['status']
            ];

            if (!empty($request->get('password'))) {
                $data['password'] = Hash::make($request->get('password'));
            }

            DB::table('staff')
                ->where('id', $validated['id'])
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Staff updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getStaffById($id)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $staff = DB::table('staff')
                ->where('id', $id)
                ->select('id', 'user', 'name', 'contact', 'status')
                ->first();

            if (!$staff) {
                return response()->json(['success' => false, 'message' => 'Staff not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
