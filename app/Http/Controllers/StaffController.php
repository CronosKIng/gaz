<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('staff.login');
        }

        // Get data from database using DB facade
        $totalStudents = DB::table('student')->count();
        $totalStaff = DB::table('staff')->count();
        $totalApplications = DB::table('student_app')->count();
        
        // Get recent 6 students
        $recentStudents = DB::table('student')
            ->select('sid', 'reg', 'sname', 'level', 'class', 'gender')
            ->orderBy('sid', 'desc')
            ->limit(6)
            ->get();

        return view('dashboard', compact(
            'totalStudents', 
            'totalStaff', 
            'totalApplications', 
            'recentStudents'
        ));
    }
}
