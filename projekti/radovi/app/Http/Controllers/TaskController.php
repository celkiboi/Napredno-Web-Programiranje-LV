<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Enums\StudyType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class TaskController extends Controller
{
    public function create()
    {
        return view('tasks.create', ['types' => StudyType::cases()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_hr' => 'required|string',
            'name_en' => 'required|string',
            'description_hr' => 'required|string',
            'description_en' => 'required|string',
            'study_type' => ['required', new Enum(StudyType::class)],
        ]);

        $request->user()->tasks()->create($validated);

        // Using Translation Key
        return redirect()->route('dashboard')->with('success', __('Task Created'));
    }

    public function index()
    {
        // Fetch tasks that are not yet taken
        $tasks = Task::whereNull('assigned_student_id')->with('professor')->get();
        return view('tasks.index', compact('tasks'));
    }
}