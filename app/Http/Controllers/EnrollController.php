<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnrollController extends Controller
{
    public function index()
    {
        $class = Session::get('class', '');
        $year = Session::get('mwaka', '');
        
        return view('students.enroll-step2', compact('class', 'year'));
    }

    public function getStudents(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $class = Session::get('class', '');
            $year = Session::get('mwaka', '');

            // Get all enrolled students reg numbers
            $enrolledRegs = DB::table('enrollment')
                ->where('class', $class)
                ->where('year', $year)
                ->pluck('reg')
                ->toArray();

            // Get available students (not enrolled in this class for this year)
            $query = DB::table('student')
                ->select('sid', 'reg', 'sname', 'class', 'gender', 'tayar')
                ->whereNotIn('reg', $enrolledRegs);

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('reg', 'like', "%{$search}%")
                        ->orWhere('sname', 'like', "%{$search}%");
                });
            }

            $students = $query->limit(50)->get();

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

    public function getEnrolledStudents(Request $request)
    {
        try {
            $class = Session::get('class', '');
            $year = Session::get('mwaka', '');

            $students = DB::table('enrollment')
                ->select('reg', 'sname', 'gender', 'class', 'year')
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
            $reg = $request->get('reg');
            $class = Session::get('class', '');
            $year = Session::get('mwaka', '');

            if (empty($reg)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student registration number is required'
                ], 400);
            }

            // Check if already enrolled
            $existing = DB::table('enrollment')
                ->where('reg', $reg)
                ->where('year', $year)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student already enrolled in this class for this year'
                ], 400);
            }

            // Get student details
            $student = DB::table('student')->where('reg', $reg)->first();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            // Get class level
            $classData = DB::table('class')->where('class', $class)->first();
            $level = $classData->level ?? '';

            // Insert into enrollment
            DB::table('enrollment')->insert([
                'reg' => $reg,
                'sname' => $student->sname,
                'gender' => $student->gender,
                'mobile' => $student->pgmob ?? '',
                'year' => $year,
                'level' => $level,
                'class' => $class
            ]);

            // Get updated available students (excluding the one just added)
            $enrolledRegs = DB::table('enrollment')
                ->where('class', $class)
                ->where('year', $year)
                ->pluck('reg')
                ->toArray();

            $availableStudents = DB::table('student')
                ->select('sid', 'reg', 'sname', 'class', 'gender', 'tayar')
                ->whereNotIn('reg', $enrolledRegs)
                ->limit(50)
                ->get();

            // Get updated enrolled students
            $enrolledStudents = DB::table('enrollment')
                ->select('reg', 'sname', 'gender', 'class', 'year')
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

    public function deleteStudent(Request $request)
    {
        try {
            $reg = $request->get('reg');
            $class = Session::get('class', '');
            $year = Session::get('mwaka', '');

            if (empty($reg)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student registration number is required'
                ], 400);
            }

            DB::table('enrollment')
                ->where('reg', $reg)
                ->where('year', $year)
                ->delete();

            DB::table('payment')
                ->where('reg', $reg)
                ->where('class', $class)
                ->where('year', $year)
                ->where('balance', '!=', '0')
                ->delete();

            // Get updated lists
            $enrolledRegs = DB::table('enrollment')
                ->where('class', $class)
                ->where('year', $year)
                ->pluck('reg')
                ->toArray();

            $availableStudents = DB::table('student')
                ->select('sid', 'reg', 'sname', 'class', 'gender', 'tayar')
                ->whereNotIn('reg', $enrolledRegs)
                ->limit(50)
                ->get();

            $enrolledStudents = DB::table('enrollment')
                ->select('reg', 'sname', 'gender', 'class', 'year')
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

    public function deleteClass(Request $request)
    {
        try {
            $class = Session::get('class', '');
            $year = Session::get('mwaka', '');

            if (empty($class) || empty($year)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class and year are required'
                ], 400);
            }

            DB::table('enrollment')
                ->where('class', $class)
                ->where('year', $year)
                ->delete();

            DB::table('payment')
                ->where('class', $class)
                ->where('year', $year)
                ->where('balance', '!=', '0')
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
