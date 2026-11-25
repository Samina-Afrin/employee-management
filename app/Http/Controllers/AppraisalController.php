<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appraisal;
use App\Models\AppraisalCycle;
use App\Models\Employee;

class AppraisalController extends Controller
{
    public function index()
    {
        $appraisals = Appraisal::with([
            'employee', 'cycle'
        ])->latest()->paginate(10);

        return view('performance.appraisals.index',
            compact('appraisals'));
    }

    public function create()
    {
        $employees = Employee::orderBy('full_name')->get();
        $cycles = AppraisalCycle::where('status', 'active')
            ->latest()->get();

        return view('performance.appraisals.create',
            compact('employees', 'cycles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'appraisal_cycle_id' => 'required|exists:appraisal_cycles,id',
            'self_rating' => 'nullable|numeric|min:1|max:5',
            'self_comments' => 'nullable|string',
            'manager_rating' => 'nullable|numeric|min:1|max:5',
            'manager_comments' => 'nullable|string',
            'hr_rating' => 'nullable|numeric|min:1|max:5',
            'hr_comments' => 'nullable|string',
        ]);

        $data['status'] = 'draft';

        Appraisal::create($data);

        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal created successfully.');
    }

    public function show(Appraisal $appraisal)
    {
        $appraisal->load(['employee', 'cycle']);

        return view('performance.appraisals.show',
            compact('appraisal'));
    }

    public function edit(Appraisal $appraisal)
    {
        $employees = Employee::orderBy('full_name')->get();
        $cycles = AppraisalCycle::all();

        return view('performance.appraisals.edit',
            compact('appraisal', 'employees', 'cycles'));
    }

    public function update(Request $request, Appraisal $appraisal)
    {
        $data = $request->validate([
            'self_rating' => 'nullable|numeric|min:1|max:5',
            'self_comments' => 'nullable|string',
            'manager_rating' => 'nullable|numeric|min:1|max:5',
            'manager_comments' => 'nullable|string',
            'hr_rating' => 'nullable|numeric|min:1|max:5',
            'hr_comments' => 'nullable|string',
            'status' => 'required|in:draft,submitted,manager_review,hr_review,completed',
        ]);

        if ($data['status'] === 'completed') {
            if ($data['manager_rating'] === null ||
                $data['hr_rating'] === null) {
                return back()->withErrors([
                    'status' => 'Manager and HR ratings are required before completion.'
                ])->withInput();
            }

            $data['final_rating'] = round(
                ($data['manager_rating'] + $data['hr_rating']) / 2,
                1
            );
        }

        $appraisal->update($data);

        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal updated successfully.');
    }

    public function destroy(Appraisal $appraisal)
    {
        $appraisal->delete();

        return back()->with('success', 'Appraisal deleted successfully.');
    }
}