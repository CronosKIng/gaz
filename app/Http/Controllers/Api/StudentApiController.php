<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentApiController extends Controller
{
    public function getStudents(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            $search = $request->get('search', '');
            
            $query = DB::table('student')
                ->select(
                    'sid', 
                    'reg', 
                    DB::raw("IFNULL(sname, '') as sname"),
                    DB::raw("IFNULL(level, '-') as level"),
                    DB::raw("IFNULL(class, '-') as class"),
                    DB::raw("IFNULL(gender, '-') as gender"),
                    DB::raw("IFNULL(status, 'Applicant') as status")
                );
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('reg', 'like', "%{$search}%")
                        ->orWhere('sname', 'like', "%{$search}%")
                        ->orWhere('class', 'like', "%{$search}%");
                });
            }
            
            $students = $query->orderBy('sname', 'asc')->limit(100)->get();
            
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

    public function getStudent($id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            $student = DB::table('student')
                ->select(
                    'sid', 
                    'reg', 
                    DB::raw("IFNULL(sname, '') as sname"),
                    DB::raw("IFNULL(level, '-') as level"),
                    DB::raw("IFNULL(class, '-') as class"),
                    DB::raw("IFNULL(gender, '-') as gender"),
                    DB::raw("IFNULL(status, 'Applicant') as status"),
                    'address',
                    'dob',
                    'religion',
                    'national',
                    'school',
                    'pgname',
                    'pgaddress',
                    'pgmob',
                    'relation',
                    'spname',
                    'spaddress',
                    'spmob',
                    'accupation',
                    'year'
                )
                ->where('sid', $id)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $student
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
