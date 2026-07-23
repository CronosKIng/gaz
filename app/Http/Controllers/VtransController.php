<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VtransController extends Controller
{
    public function index()
    {
        $class = Session::get('class', '');
        $year = Session::get('year', '');
        
        return view('students.vtrans', compact('class', 'year'));
    }

    public function getAvailableStudents(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $search = $request->get('search', '');
            $class = Session::get('class', '');
            $year = Session::get('year', '');

            $query = DB::table('enrollment')
                ->select('sid', 'reg', 'sname', 'class')
                ->where('class', '!=', $class);

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('reg', 'like', "%{$search}%")
                        ->orWhere('sname', 'like', "%{$search}%")
                        ->orWhere('class', 'like', "%{$search}%");
                });
            }

            $students = $query->limit(20)->get();

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

    public function getEnrolledStudents(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $class = Session::get('class', '');
            $year = Session::get('year', '');

            $students = DB::table('enrollment')
                ->select('sid', 'reg', 'sname')
                ->where('class', $class)
                ->where('year', $year)
                ->orderBy('sname', 'asc')
                ->get();

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

    public function addStudent(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = $request->get('reg');
            $sid = $request->get('sid');
            $class = Session::get('class', '');
            $year = Session::get('year', '');

            if (empty($sid) || empty($reg)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student information is required'
                ], 400);
            }

            // Get class level
            $classData = DB::table('class')->where('class', $class)->first();
            $level = $classData->level ?? '';

            // Update enrollment
            DB::table('enrollment')
                ->where('sid', $sid)
                ->update([
                    'class' => $class,
                    'level' => $level,
                    'year' => $year
                ]);

            // Update student
            DB::table('student')
                ->where('reg', $reg)
                ->update([
                    'class' => $class,
                    'level' => $level
                ]);

            // Update payment
            DB::table('payment')
                ->where('reg', $reg)
                ->where('year', $year)
                ->update([
                    'class' => $class,
                    'level' => $level
                ]);

            // Update allpayment
            DB::table('allpayment')
                ->where('reg', $reg)
                ->where('year', $year)
                ->update([
                    'class' => $class,
                    'level' => $level
                ]);

            // Get updated lists
            $availableStudents = DB::table('enrollment')
                ->select('sid', 'reg', 'sname', 'class')
                ->where('class', '!=', $class)
                ->limit(20)
                ->get();

            $enrolledStudents = DB::table('enrollment')
                ->select('sid', 'reg', 'sname')
                ->where('class', $class)
                ->where('year', $year)
                ->orderBy('sname', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Student added successfully!',
                'available' => $availableStudents,
                'enrolled' => $enrolledStudents,
                'enrolled_count' => $enrolledStudents->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function removeStudent(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $sid = $request->get('sid');
            $class = Session::get('class', '');
            $year = Session::get('year', '');

            if (empty($sid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID is required'
                ], 400);
            }

            DB::table('enrollment')
                ->where('sid', $sid)
                ->delete();

            // Get updated lists
            $availableStudents = DB::table('enrollment')
                ->select('sid', 'reg', 'sname', 'class')
                ->where('class', '!=', $class)
                ->limit(20)
                ->get();

            $enrolledStudents = DB::table('enrollment')
                ->select('sid', 'reg', 'sname')
                ->where('class', $class)
                ->where('year', $year)
                ->orderBy('sname', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Student removed successfully!',
                'available' => $availableStudents,
                'enrolled' => $enrolledStudents,
                'enrolled_count' => $enrolledStudents->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateClass(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $class = Session::get('class', '');
            $year = Session::get('year', '');

            if (empty($class) || empty($year)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class and year are required'
                ], 400);
            }

            // Get all students in this class
            $students = DB::table('enrollment')
                ->where('class', $class)
                ->where('year', $year)
                ->get();

            foreach ($students as $student) {
                // Update student
                DB::table('student')
                    ->where('reg', $student->reg)
                    ->update([
                        'class' => $class,
                        'level' => $student->level
                    ]);

                // Update payment
                DB::table('payment')
                    ->where('reg', $student->reg)
                    ->where('year', $year)
                    ->update([
                        'class' => $class,
                        'level' => $student->level
                    ]);

                // Update allpayment
                DB::table('allpayment')
                    ->where('reg', $student->reg)
                    ->where('year', $year)
                    ->update([
                        'class' => $class,
                        'level' => $student->level
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
