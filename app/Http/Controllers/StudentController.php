<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $year = $request->get('year');
        
        $query = DB::table('student')
            ->select('sid', 'reg', 'sname', 'level', 'class', 'year', 'gender', 'pgmob', 'status');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('reg', 'like', "%{$search}%")
                    ->orWhere('sname', 'like', "%{$search}%")
                    ->orWhere('class', 'like', "%{$search}%");
            });
        }
        
        if ($year) {
            $query->where('year', $year);
        }
        
        $students = $query->orderBy('sname', 'asc')->get();

        $years = DB::table('student')
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('students.index', compact('students', 'search', 'year', 'years'));
    }

    public function show($id, Request $request)
    {
        $student = DB::table('student')->where('sid', $id)->first();
        
        if (!$student) {
            return redirect()->route('students.index')->with('error', 'Student not found');
        }

        $selectedYear = $request->get('year', date('Y'));
        
        $payments = DB::table('payment')
            ->where('reg', $student->reg)
            ->when($selectedYear, function($query) use ($selectedYear) {
                return $query->where('year', $selectedYear);
            })
            ->get();

        $paymentYears = DB::table('payment')
            ->where('reg', $student->reg)
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('students.show', compact(
            'student', 
            'payments', 
            'selectedYear', 
            'paymentYears'
        ));
    }

    public function getStudents(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $search = $request->get('search', '');
            
            $query = DB::table('student')
                ->select('sid', 'reg', 'sname', 'level', 'class', 'gender', 'status');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('reg', 'like', "%{$search}%")
                        ->orWhere('sname', 'like', "%{$search}%")
                        ->orWhere('class', 'like', "%{$search}%");
                });
            }
            
            $students = $query->orderBy('sname', 'asc')->get();

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

            $reg = $request->get('reg');
            if (empty($reg)) {
                return response()->json(['success' => false, 'message' => 'Registration number is required'], 400);
            }

            DB::table('student')->where('reg', $reg)->delete();
            DB::table('enrollment')->where('reg', $reg)->delete();
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
