<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Employee;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::with('employee')
            ->latest()->paginate(10);

        return view('performance.goals.index',
            compact('goals'));
    }

    public function create()
    {
        $employees = Employee::orderBy('full_name')->get();

        return view('performance.goals.create',
            compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'kpi' => 'nullable|string|max:255',
            'target_value' => 'nullable|numeric|min:0',
            'achieved_value' => 'nullable|numeric|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $data['achieved_value'] ??= 0;

        Goal::create($data);

        return redirect()->route('performance.goals.index')
            ->with('success', 'Goal added successfully.');
    }

    public function edit(Goal $goal)
    {
        $employees = Employee::orderBy('full_name')->get();

        return view('performance.goals.edit',
            compact('goal', 'employees'));
    }

    public function update(Request $request, Goal $goal)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'kpi' => 'nullable|string|max:255',
            'target_value' => 'nullable|numeric|min:0',
            'achieved_value' => 'nullable|numeric|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $goal->update($data);

        return redirect()->route('performance.goals.index')
            ->with('success', 'Goal updated successfully.');
    }

    public function destroy(Goal $goal)
    {
        $goal->delete();

        return back()->with('success', 'Goal deleted successfully.');
    }
}