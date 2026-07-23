<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChangeClassController extends Controller
{
    public function index()
    {
        return view('students.change-class');
    }

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

    public function processChangeClass(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // Validate only class and year
            $validated = $request->validate([
                'class' => 'required|string',
                'year' => 'required|string'
            ]);

            // Store in session
            Session::put('class', $validated['class']);
            Session::put('year', $validated['year']);

            return response()->json([
                'success' => true,
                'message' => 'Class change processed successfully!',
                'redirect' => '/vtrans'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
