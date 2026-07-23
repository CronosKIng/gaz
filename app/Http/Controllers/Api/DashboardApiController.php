<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardApiController extends Controller
{
    public function getDashboardData()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            $totalStudents = DB::table('student')->count();
            $totalStaff = DB::table('staff')->count();
            $totalApplications = DB::table('student_app')->count();
            
            $recentStudents = DB::table('student')
                ->select('sid', 'reg', 'sname', 'level', 'class', 'gender')
                ->orderBy('sid', 'desc')
                ->limit(6)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalStudents' => $totalStudents,
                    'totalStaff' => $totalStaff,
                    'totalApplications' => $totalApplications,
                    'recentStudents' => $recentStudents
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
