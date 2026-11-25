<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProbationController extends Controller
{
    
    // Show all probation evaluations
    public function index()
    {
        $evaluations = ProbationEvaluation::with('employee')
            ->latest()
            ->paginate(10);

        return view('performance.probation.index', compact('evaluations'));
    }

    // Show create form
    public function create()
    {
        $employees = Employee::all();

        return view('performance.probation.create', compact('employees'));
    }

    // Store probation evaluation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',

            'review_stage' => 'required|in:30_days,60_days,90_days',

            'review_date' => 'required|date',

            'rating' => 'nullable|numeric|min:0|max:10',

            'recommendation' => 'nullable|in:confirm,extend,terminate',

            'comments' => 'nullable|string',
        ]);

        ProbationEvaluation::create($validated);

        return redirect()
            ->route('performance.probation.index')
            ->with('success', 'Probation evaluation created successfully.');
    
}
}
