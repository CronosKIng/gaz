<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('staff.login');
        }

        $totalStudents = DB::table('student')->count();
        $totalStaff = DB::table('staff')->count();
        $totalApplications = DB::table('student_app')->count();
        
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
