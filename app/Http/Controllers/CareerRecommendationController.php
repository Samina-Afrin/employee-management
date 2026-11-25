<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CareerRecommendation;
use App\Models\EmployeeServiceHistory;

class CareerRecommendationController extends Controller
{
    public function index()
    {
        $recommendations = CareerRecommendation::with('employee')
            ->latest()->paginate(10);

        return view('performance.career.index',
            compact('recommendations'));
    }

    public function create()
    {
        $employees = \App\Models\Employee::orderBy('full_name')->get();

        return view('performance.career.create',
            compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:increment,promotion',
            'current_salary' => 'nullable|numeric|min:0',
            'proposed_salary' => 'nullable|numeric|min:0',
            'current_designation' => 'nullable|string|max:255',
            'proposed_designation' => 'nullable|string|max:255',
            'effective_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $data['status'] = 'pending';

        CareerRecommendation::create($data);

        return redirect()->route('performance.career.index')
            ->with('success', 'Recommendation submitted for approval.');
    }

    public function approve(CareerRecommendation $recommendation)
    {
        if ($recommendation->status !== 'pending') {
            return back()->with('error', 'This recommendation has already been processed.');
        }

        DB::transaction(function () use ($recommendation) {

            $recommendation->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            EmployeeServiceHistory::create([
                'employee_id' => $recommendation->employee_id,
                'event_type' => $recommendation->type,
                'old_designation' => $recommendation->current_designation,
                'new_designation' => $recommendation->proposed_designation,
                'old_salary' => $recommendation->current_salary,
                'new_salary' => $recommendation->proposed_salary,
                'effective_date' => $recommendation->effective_date,
                'remarks' => $recommendation->reason,
            ]);
        });

        return back()->with('success', 'Recommendation approved and service history saved.');
    }

    public function reject(CareerRecommendation $recommendation)
    {
        if ($recommendation->status !== 'pending') {
            return back()->with('error', 'This recommendation has already been processed.');
        }

        $recommendation->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Recommendation rejected.');
    }
}