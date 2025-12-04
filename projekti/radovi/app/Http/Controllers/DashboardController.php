<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Logic for Teachers
        if ($user->hasRole('teacher')) {
            $myTasks = $user->tasks()
                ->with(['applications.student']) // Eager load applications and student info
                ->get();
            
            return view('dashboard', compact('myTasks'));
        }

        // 2. Logic for Students
        if ($user->hasRole('student')) {
    
            // 1. Check if the student is already accepted
            $assignedTask = $user->assignedTask()->with('professor')->first();
        
            // 2. Fetch applications (we might not need them if accepted, but good to have)
            $myApplications = $user->applications()
                ->with('task.professor')
                ->orderBy('priority')
                ->get();
        
            return view('dashboard', compact('myApplications', 'assignedTask'));
        }

        

        // 3. Fallback for Admin or others
        return view('dashboard');
    }
}