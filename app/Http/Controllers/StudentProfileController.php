<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentProfileController extends Controller
{
    public function show($id)
    {
        try {
            if (!Auth::check()) {
                return redirect('/staff-login');
            }

            $student = DB::table('student')->where('sid', $id)->first();
            
            if (!$student) {
                return redirect('/students')->with('error', 'Student not found');
            }

            $enrollment = DB::table('enrollment')
                ->where('reg', $student->reg)
                ->where('year', date('Y'))
                ->first();

            $classTeacher = null;
            if ($enrollment && !empty($enrollment->class)) {
                $classData = DB::table('class')->where('class', $enrollment->class)->first();
                if ($classData && !empty($classData->class_teacher)) {
                    $classTeacher = DB::table('staff')
                        ->where('user', $classData->class_teacher)
                        ->first();
                }
            }

            $attendance = DB::table('attendance')
                ->where('reg', $student->reg)
                ->where('status', 'Present')
                ->where('year', date('Y'))
                ->orderBy('date', 'asc')
                ->first();

            $balance = DB::table('payment')
                ->where('reg', $student->reg)
                ->sum('balance');

            // Get available years for payment
            $paymentYears = DB::table('payment')
                ->where('reg', $student->reg)
                ->select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            // Get available years for attendance
            $attendanceYears = DB::table('attendance')
                ->where('reg', $student->reg)
                ->select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            // Get available years for examination
            $examYears = DB::table('matokeo')
                ->where('reg', $student->reg)
                ->select('acyear')
                ->distinct()
                ->orderBy('acyear', 'desc')
                ->pluck('acyear');

            // Get exam types for this student
            $examTypes = DB::table('matokeo')
                ->where('reg', $student->reg)
                ->select('examtp')
                ->distinct()
                ->pluck('examtp');

            return view('students.profile', compact(
                'student', 
                'enrollment', 
                'classTeacher', 
                'attendance', 
                'balance',
                'paymentYears',
                'attendanceYears',
                'examYears',
                'examTypes'
            ));
        } catch (\Exception $e) {
            Log::error('Profile error: ' . $e->getMessage());
            return redirect('/students')->with('error', $e->getMessage());
        }
    }

    public function getPayment(Request $request)
    {
        try {
            $reg = $request->get('reg');
            $year = $request->get('year', '');
            
            Log::info('Payment API called for: ' . $reg . ' year: ' . $year);
            
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $bills = DB::table('payment')
                ->where('reg', $reg)
                ->where('balance', '>', 0);
            
            if (!empty($year)) {
                $bills = $bills->where('year', $year);
            }
            $bills = $bills->get();

            $receipts = DB::table('allpayment')
                ->where('reg', $reg);
            
            if (!empty($year)) {
                $receipts = $receipts->where('year', $year);
            }
            $receipts = $receipts->orderBy('date', 'desc')->get();

            $totalBalance = DB::table('payment')
                ->where('reg', $reg);
            
            if (!empty($year)) {
                $totalBalance = $totalBalance->where('year', $year);
            }
            $totalBalance = $totalBalance->sum('balance');

            $availableYears = DB::table('payment')
                ->where('reg', $reg)
                ->select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            return response()->json([
                'success' => true,
                'data' => [
                    'bills' => $bills,
                    'receipts' => $receipts,
                    'total_balance' => $totalBalance ?? 0,
                    'year' => $year,
                    'available_years' => $availableYears
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Payment API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getExaminations(Request $request)
    {
        try {
            $reg = $request->get('reg');
            $year = $request->get('year', '');
            $examType = $request->get('exam_type', '');
            
            Log::info('Examination API called for: ' . $reg . ' year: ' . $year . ' type: ' . $examType);
            
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // First, check if student has any examination records
            $hasRecords = DB::table('matokeo')
                ->where('reg', $reg)
                ->exists();

            if (!$hasRecords) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'results' => [],
                        'year' => $year,
                        'exam_type' => $examType,
                        'available_years' => [],
                        'available_types' => [],
                        'message' => 'No examination records found for this student.'
                    ]
                ]);
            }

            // Columns available: reg, examtp, level, code, subject, marks, grade, status, point, position, class, acyear
            $query = DB::table('matokeo')
                ->where('reg', $reg)
                ->select('reg', 'subject', 'marks', 'grade', 'examtp', 'class', 'acyear');

            if (!empty($year)) {
                $query->where('acyear', $year);
            }

            if (!empty($examType)) {
                $query->where('examtp', $examType);
            }

            $results = $query->orderBy('acyear', 'desc')
                ->orderBy('subject', 'asc')
                ->get();

            // Get available years for this student
            $availableYears = DB::table('matokeo')
                ->where('reg', $reg)
                ->select('acyear')
                ->distinct()
                ->orderBy('acyear', 'desc')
                ->pluck('acyear');

            // Get available exam types for this student
            $availableTypes = DB::table('matokeo')
                ->where('reg', $reg)
                ->select('examtp')
                ->distinct()
                ->pluck('examtp');

            // If no results with filters, get all results for this student
            if ($results->isEmpty()) {
                $results = DB::table('matokeo')
                    ->where('reg', $reg)
                    ->select('reg', 'subject', 'marks', 'grade', 'examtp', 'class', 'acyear')
                    ->orderBy('acyear', 'desc')
                    ->orderBy('subject', 'asc')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'results' => $results,
                    'year' => $year,
                    'exam_type' => $examType,
                    'available_years' => $availableYears,
                    'available_types' => $availableTypes,
                    'count' => $results->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Examination API Error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getExamResults(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $reg = $request->get('reg');
            $class = $request->get('class');
            $year = $request->get('year');
            $examType = $request->get('exam_type');

            $results = DB::table('matokeo')
                ->where('reg', $reg)
                ->where('class', $class)
                ->where('acyear', $year)
                ->where('examtp', $examType)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAttendance(Request $request)
    {
        try {
            $reg = $request->get('reg');
            $year = $request->get('year', '');
            
            Log::info('Attendance API called for: ' . $reg . ' year: ' . $year);
            
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $attendance = DB::table('attendance')
                ->where('reg', $reg);
            
            if (!empty($year)) {
                $attendance = $attendance->where('year', $year);
            }
            $attendance = $attendance->orderBy('date', 'desc')->get();

            $present = $attendance->where('status', 'Present')->count();
            $absent = $attendance->where('status', 'Absent')->count();
            $reason = $attendance->where('status', 'Reason')->count();

            $availableYears = DB::table('attendance')
                ->where('reg', $reg)
                ->select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            return response()->json([
                'success' => true,
                'data' => [
                    'records' => $attendance,
                    'present' => $present ?? 0,
                    'absent' => $absent ?? 0,
                    'reason' => $reason ?? 0,
                    'year' => $year,
                    'available_years' => $availableYears
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Attendance API Error: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'data' => [
                    'records' => [],
                    'present' => 0,
                    'absent' => 0,
                    'reason' => 0,
                    'year' => '',
                    'available_years' => []
                ]
            ]);
        }
    }
}
