<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Employee;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::with('employee:id,name');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('training_title', 'like', "%{$s}%")
                  ->orWhere('institute_name', 'like', "%{$s}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $trainings = $query->latest('start_date')->paginate(15)->withQueryString();

        return view('trainings.index', compact('trainings'));
    }

    public function create()
    {
        $employees = Employee::select('id', 'name')->orderBy('name')->get();
        return view('trainings.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'training_title' => 'required|string|max:150',
            'training_type'  => 'required|string|max:50',
            'institute_name' => 'nullable|string|max:150',
            'country'        => 'nullable|string|max:50',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'duration_hours' => 'nullable|integer|min:0',
            'cost'           => 'nullable|numeric|min:0',
            'status'         => 'required|in:1,2,3',
            'remarks'        => 'nullable|string',
        ]);

        Training::create($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training record created successfully.');
    }

    public function show(Training $training)
    {
        $training->load('employee:id,name');
        return view('trainings.show', compact('training'));
    }

    public function edit(Training $training)
    {
        $employees = Employee::select('id', 'name')->orderBy('name')->get();
        return view('trainings.edit', compact('training', 'employees'));
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'training_title' => 'required|string|max:150',
            'training_type'  => 'required|string|max:50',
            'institute_name' => 'nullable|string|max:150',
            'country'        => 'nullable|string|max:50',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'duration_hours' => 'nullable|integer|min:0',
            'cost'           => 'nullable|numeric|min:0',
            'status'         => 'required|in:1,2,3',
            'remarks'        => 'nullable|string',
        ]);

        $training->update($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training record updated successfully.');
    }

    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('trainings.index')
            ->with('success', 'Training record deleted successfully.');
    }
}