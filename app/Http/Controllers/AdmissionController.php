<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        return view('admission');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'parent_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'class_applying_for' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'previous_school' => 'nullable|string|max:255',
            'address' => 'required|string',
        ]);

        // Create admission in student_app table
        Admission::create([
            'sname' => $validated['student_name'],
            'pgname' => $validated['parent_name'],
            'pgmob' => $validated['phone'],
            'email' => $validated['email'],
            'class' => $validated['class_applying_for'],
            'dob' => $validated['date_of_birth'],
            'school' => $validated['previous_school'] ?? '',
            'address' => $validated['address'],
            'status' => 'pending',
            'date' => now()->toDateString(),
        ]);

        return redirect()->route('admission')->with('success', 'Admission application submitted successfully!');
    }
}
