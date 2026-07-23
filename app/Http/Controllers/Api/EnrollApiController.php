<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EnrollApiController extends Controller
{
    public function getClasses()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $classes = DB::table('class')
                ->select('class', 'level')
                ->orderBy('level', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getStudentsByClass(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $class = $request->get('class');
            $year = $request->get('year');

            if (empty($class) || empty($year)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class and year are required'
                ], 400);
            }

            $students = DB::table('student')
                ->select('sid', 'reg', 'sname', 'level', 'class', 'gender', 'status')
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

    public function enrollStudent(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'class' => 'required|string',
                'year' => 'required|string'
            ]);

            // Store in session
            session(['mwaka' => $validated['year']]);
            session(['class' => $validated['class']]);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment processed successfully!',
                'redirect' => '/enroll-step2'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
