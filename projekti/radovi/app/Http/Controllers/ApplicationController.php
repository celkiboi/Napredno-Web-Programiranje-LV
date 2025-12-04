<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $student = Auth::user();
        $task = Task::findOrFail($request->task_id);

        if ($student->assignedTask()->exists()) {
            return back()->with('error', __('You already have a thesis.'));
        }

        if ($student->applications()->count() >= 5) {
            // Using Translation Key
            return back()->with('error', __('Application Limit Reached'));
        }

        $nextPriority = $student->applications()->count() + 1;

        Application::create([
            'user_id' => $student->id,
            'task_id' => $task->id,
            'priority' => $nextPriority
        ]);

        return back()->with('success', __('Application Successful'));
    }

    public function accept(Task $task, User $student)
    {
        if ($task->user_id !== Auth::id()) abort(403);

        try {
            DB::transaction(function () use ($task, $student) {
                
                $application = Application::where('task_id', $task->id)
                    ->where('user_id', $student->id)
                    ->firstOrFail();

                if ($application->priority !== 1) {
                    throw new \Exception(__('Only First Priority'));
                }

                $task->update(['assigned_student_id' => $student->id]);

                // Delete all applications for this winning student
                Application::where('user_id', $student->id)->delete();

                // Handle other students (Losers)
                $rejectedApplications = Application::where('task_id', $task->id)->get();

                foreach ($rejectedApplications as $rejectedApp) {
                    $affectedStudentId = $rejectedApp->user_id;
                    $rejectedApp->delete();

                    // Shift priorities for the loser
                    $remainingApps = Application::where('user_id', $affectedStudentId)
                        ->orderBy('priority', 'asc')
                        ->get();

                    foreach ($remainingApps as $index => $remApp) {
                        $remApp->update(['priority' => $index + 1]);
                    }
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', __('Student Accepted'));
    }

    public function destroy(Application $application)
    {
        $student = Auth::user();

        // Security: Ensure the user owns this application
        if ($application->user_id !== $student->id) {
            abort(403);
        }

        DB::transaction(function () use ($application, $student) {
            $deletedPriority = $application->priority;
            $task = $application->task;

            // 1. If this student was already accepted for this task, unassign them
            if ($task->assigned_student_id === $student->id) {
                $task->update(['assigned_student_id' => null]);
            }

            // 2. Delete the application
            $application->delete();

            // 3. Re-balance the remaining list
            // We find all applications by this student that had a LOWER priority number (higher rank)
            // e.g., If I deleted #2, then #3 becomes #2, #4 becomes #3.
            $remainingApps = $student->applications()
                ->where('priority', '>', $deletedPriority)
                ->get();

            foreach ($remainingApps as $app) {
                $app->decrement('priority');
            }
        });

        return back()->with('success', __('Application Cancelled'));
    }
}