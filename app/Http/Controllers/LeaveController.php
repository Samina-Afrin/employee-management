<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Leave list
     */
    public function index(Request $request)
    {
        $query = Leave::with('employee', 'approver');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('year')) {
            $query->where('leave_year', $request->year);
        }

        $leaves = $query->latest()->paginate(15);
        return view('leave.index', compact('leaves'));
    }

    /**
     * Apply leave form
     */
    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        return view('leave.create', compact('employees'));
    }

    /**
     * Store leave
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'leave_type'     => 'required|in:1,2,3,4,5,6,7,8',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'reason'         => 'nullable|string|max:255',
            'yearly_limit_days' => 'nullable|numeric|min:0',
        ]);

        $start = \Carbon\Carbon::parse($data['start_date']);
        $end   = \Carbon\Carbon::parse($data['end_date']);
        $totalDays = $start->diffInDays($end) + 1;

        $data['leave_year'] = $start->year;
        $data['total_days'] = $totalDays;
        $data['yearly_limit_days'] = $data['yearly_limit_days'] ?? Leave::defaultLimit($data['leave_type']);
        $data['status'] = 1; // pending

        Leave::create($data);

        return redirect()->route('leave.index')
            ->with('success', 'Leave application submitted successfully.');
    }

    /**
     * Show leave
     */
    public function show(Leave $leave)
    {
        $leave->load('employee', 'approver');
        return view('leave.show', compact('leave'));
    }

    /**
     * Edit leave
     */
    public function edit(Leave $leave)
    {
        $employees = Employee::orderBy('name')->get();
        return view('leave.edit', compact('leave', 'employees'));
    }

    /**
     * Update leave
     */
    public function update(Request $request, Leave $leave)
    {
        $data = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'leave_type'     => 'required|in:1,2,3,4,5,6,7,8',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'reason'         => 'nullable|string|max:255',
            'yearly_limit_days' => 'nullable|numeric|min:0',
        ]);

        $start = \Carbon\Carbon::parse($data['start_date']);
        $end   = \Carbon\Carbon::parse($data['end_date']);
        $data['leave_year'] = $start->year;
        $data['total_days'] = $start->diffInDays($end) + 1;

        $leave->update($data);

        return redirect()->route('leave.index')
            ->with('success', 'Leave updated successfully.');
    }

    /**
     * Approve leave
     */
    public function approve(Request $request, Leave $leave)
    {
        $request->validate([
            'decision_note' => 'nullable|string|max:255',
        ]);

        $remaining = ($leave->yearly_limit_days ?? 0) - ($leave->total_days ?? 0);
        $remaining = $remaining < 0 ? 0 : $remaining;

        $leave->update([
            'status'            => 2,
            'approved_by'       => Auth::id(),
            'decision_note'     => $request->decision_note,
            'remaining_balance' => $remaining,
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave approved successfully.');
    }

    /**
     * Reject leave
     */
    public function reject(Request $request, Leave $leave)
    {
        $request->validate([
            'decision_note' => 'nullable|string|max:255',
        ]);

        $leave->update([
            'status'        => 3,
            'approved_by'   => Auth::id(),
            'decision_note' => $request->decision_note,
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave rejected.');
    }

    /**
     * Delete leave
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leave.index')
            ->with('success', 'Leave deleted successfully.');
    }
}