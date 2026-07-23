<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NewStudentController extends Controller
{
    public function index()
    {
        return view('students.new');
    }

    public function getClasses(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $level = $request->get('level');
            
            $query = DB::table('class');
            
            if (!empty($level)) {
                $query->where('level', $level);
            }
            
            $classes = $query->select('class', 'level')
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

    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'sname' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'gender' => 'nullable|string',
                'religion' => 'nullable|string|max:100',
                'national' => 'nullable|string|max:100',
                'school' => 'nullable|string|max:255',
                'shehia' => 'nullable|string|max:100',
                'ward' => 'nullable|string|max:100',
                'level' => 'nullable|string|max:50',
                'class' => 'nullable|string|max:50',
                'ryear' => 'nullable|string|max:10',
                'pgname' => 'nullable|string|max:255',
                'pgaddress' => 'nullable|string|max:255',
                'pgmob' => 'nullable|string|max:30',
                'relation' => 'nullable|string|max:50',
                'spname' => 'nullable|string|max:255',
                'spaddress' => 'nullable|string|max:255',
                'spmob' => 'nullable|string|max:30',
                'accupation' => 'nullable|string|max:100'
            ]);

            $year = $validated['ryear'] ?? date('Y');
            $dt = date('Y-m-d');
            
            // Generate registration number
            $lastStudent = DB::table('student')
                ->where('year', $year)
                ->orderBy('sid', 'desc')
                ->first();
            
            $sid = $lastStudent ? $lastStudent->sid + 1 : 1;
            $reg = "GA/{$year}/{$sid}";

            // Hash password
            $password = Hash::make('4555');

            // Insert into student table
            DB::table('student')->insert([
                'reg' => $reg,
                'sname' => $validated['sname'],
                'address' => $validated['address'] ?? '',
                'dob' => $validated['dob'] ?? '',
                'gender' => $validated['gender'] ?? '',
                'religion' => $validated['religion'] ?? '',
                'national' => $validated['national'] ?? '',
                'school' => $validated['school'] ?? '',
                'pgname' => $validated['pgname'] ?? '',
                'pgaddress' => $validated['pgaddress'] ?? '',
                'pgmob' => $validated['pgmob'] ?? '',
                'relation' => $validated['relation'] ?? '',
                'spname' => $validated['spname'] ?? '',
                'spaddress' => $validated['spaddress'] ?? '',
                'spmob' => $validated['spmob'] ?? '',
                'accupation' => $validated['accupation'] ?? '',
                'level' => $validated['level'] ?? '',
                'class' => $validated['class'] ?? '',
                'shehia' => $validated['shehia'] ?? '',
                'ward' => $validated['ward'] ?? '',
                'date' => $dt,
                'year' => $year,
                'password' => $password,
                'status' => 'Applicant'
            ]);

            // Insert into enrollment
            DB::table('enrollment')->insert([
                'reg' => $reg,
                'sname' => $validated['sname'],
                'gender' => $validated['gender'] ?? '',
                'mobile' => $validated['pgmob'] ?? '',
                'year' => $year,
                'level' => $validated['level'] ?? '',
                'class' => $validated['class'] ?? ''
            ]);

            // Insert into payment if level is selected
            if (!empty($validated['level'])) {
                $fees = DB::table('fee')->where('level', $validated['level'])->get();
                foreach ($fees as $fee) {
                    DB::table('payment')->insert([
                        'reg' => $reg,
                        'name' => $validated['sname'],
                        'category' => $fee->name,
                        'amount' => $fee->amount,
                        'balance' => $fee->amount,
                        'year' => $year,
                        'level' => $validated['level'],
                        'class' => $validated['class'] ?? '',
                        'active' => 'NO'
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Student registered successfully! Registration Number: ' . $reg
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
