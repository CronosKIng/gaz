<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class GloriousLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.glorious-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'pass' => 'required'
        ]);

        $username = $request->exam;
        $password = $request->pass;

        // 1. Check Staff table (Admin, Teacher, Class teacher, Section leader, Human resource, Accountant, Head master, registrar)
        $staff = Staff::where('user', $username)->first();

        if ($staff) {
            // Check password (supports both encrypted and plain text for migration)
            if (Hash::check($password, $staff->password) || $staff->password === $password) {
                // If password is plain text, update to encrypted
                if ($staff->password !== Hash::make($password)) {
                    $staff->password = Hash::make($password);
                    $staff->save();
                }

                Session::put('user', $username);
                Session::put('role', $staff->status);
                Session::put('id', $staff->id);

                // Redirect based on role
                $role = $staff->status;
                return $this->redirectByRole($role);
            }
        }

        // 2. Check Student table
        $student = Student::where('reg', $username)->first();

        if ($student) {
            // Student password is usually plain text in old system
            if ($student->password === $password) {
                // Update to encrypted
                $student->password = Hash::make($password);
                $student->save();

                Session::put('user', $username);
                Session::put('role', 'student');
                Session::put('id', $student->id);

                return redirect()->route('student.dashboard');
            }
        }

        return back()->with('error', 'Wrong username or password');
    }

    private function redirectByRole($role)
    {
        switch ($role) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'registrar':
                return redirect()->route('registrar.dashboard');
            case 'Head master':
                return redirect()->route('headmaster.dashboard');
            case 'Accountant':
                return redirect()->route('accountant.dashboard');
            case 'Human resource':
                return redirect()->route('hr.dashboard');
            case 'Section leader':
                return redirect()->route('sectionleader.dashboard');
            case 'Class teacher':
                return redirect()->route('classteacher.dashboard');
            case 'Teacher':
                return redirect()->route('teacher.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}
