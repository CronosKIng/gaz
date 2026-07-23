<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::orderBy('created_at', 'desc')->get();
        return view('admin.admissions.index', compact('admissions'));
    }

    public function create()
    {
        return view('admin.admissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Student Information
            'student_name' => 'required|string|max:255',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'religion' => 'nullable|string|max:100',
            'nationality' => 'required|string|max:100',
            'former_school' => 'nullable|string|max:255',
            'shehia' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'nullable|string',
            
            // Parent/Guardian
            'parent_full_name' => 'required|string|max:255',
            'parent_address' => 'required|string|max:255',
            'parent_mobile' => 'required|string|max:20',
            'parent_relationship' => 'required|string|max:100',
            'parent_email' => 'nullable|email|max:255',
            
            // Sponsor
            'sponsor_full_name' => 'nullable|string|max:255',
            'sponsor_address' => 'nullable|string|max:255',
            'sponsor_mobile' => 'nullable|string|max:20',
            'sponsor_occupation' => 'nullable|string|max:255',
            
            // Academic
            'academic_level' => 'required|string|max:100',
            'class_applying_for' => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'previous_school' => 'nullable|string|max:255'
        ]);

        $validated['status'] = 'pending';
        Admission::create($validated);

        return redirect()->route('admin.admissions.index')
            ->with('success', 'Admission application submitted successfully!');
    }

    public function edit($id)
    {
        $admission = Admission::findOrFail($id);
        return view('admin.admissions.edit', compact('admission'));
    }

    public function update(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);

        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'religion' => 'nullable|string|max:100',
            'nationality' => 'required|string|max:100',
            'former_school' => 'nullable|string|max:255',
            'shehia' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'nullable|string',
            'parent_full_name' => 'required|string|max:255',
            'parent_address' => 'required|string|max:255',
            'parent_mobile' => 'required|string|max:20',
            'parent_relationship' => 'required|string|max:100',
            'parent_email' => 'nullable|email|max:255',
            'sponsor_full_name' => 'nullable|string|max:255',
            'sponsor_address' => 'nullable|string|max:255',
            'sponsor_mobile' => 'nullable|string|max:20',
            'sponsor_occupation' => 'nullable|string|max:255',
            'academic_level' => 'required|string|max:100',
            'class_applying_for' => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'previous_school' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,rejected'
        ]);

        $admission->update($validated);

        return redirect()->route('admin.admissions.index')
            ->with('success', 'Admission updated successfully!');
    }

    public function destroy($id)
    {
        $admission = Admission::findOrFail($id);
        $admission->delete();

        return redirect()->route('admin.admissions.index')
            ->with('success', 'Admission deleted successfully!');
    }

    public function show($id)
    {
        $admission = Admission::findOrFail($id);
        return view('admin.admissions.show', compact('admission'));
    }
}
