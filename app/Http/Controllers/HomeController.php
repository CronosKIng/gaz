<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $activeStudents = Student::where('status', 'active')->count();
        
        return view('home', compact('totalStudents', 'totalTeachers', 'activeStudents'));
    }
}
