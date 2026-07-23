<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeleteStudentController extends Controller
{
    private $actionCode = 'G1988';

    public function index()
    {
        return view('students.delete');
    }

    public function getStudents(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $search = $request->get('search', '');

            $query = DB::table('student')
                ->select('sid', 'reg', 'sname', 'class', 'gender', 'date', 'status', 'pgmob')
                ->where('status', 'Active');

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('reg', 'like', "%{$search}%")
                        ->orWhere('sname', 'like', "%{$search}%")
                        ->orWhere('class', 'like', "%{$search}%")
                        ->orWhere('gender', 'like', "%{$search}%");
                });
            }

            $students = $query->orderBy('date', 'desc')->limit(20)->get();

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteStudent(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'sid' => 'required|string',
                'reg' => 'required|string',
                'sname' => 'required|string',
                'class' => 'required|string',
                'mob' => 'required|string',
                'action_code' => 'required|string'
            ]);

            // Check action code
            if ($validated['action_code'] !== $this->actionCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action code. Please enter the correct code.'
                ], 403);
            }

            $reg = $validated['reg'];
            $sname = $validated['sname'];
            $class = $validated['class'];
            $mob = $validated['mob'];
            $dt = date('Y-m-d');
            $year = date('Y');

            // Delete from student table
            DB::table('student')->where('reg', $reg)->delete();

            // Insert into transfer table
            DB::table('transfer')->insert([
                'reg' => $reg,
                'sname' => $sname,
                'class' => $class,
                'mob' => $mob,
                'date' => $dt,
                'year' => $year,
                'status' => 'Deleted'
            ]);

            // Delete from payment
            DB::table('payment')->where('reg', $reg)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
