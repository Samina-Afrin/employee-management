<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('employee');

        // Filter by type
        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->loan_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $loans = $query->latest()->paginate(20);

        // Stats
        $totalLongTerm = Loan::where('loan_type', 'long_term')->sum('amount');
        $totalShortTerm = Loan::where('loan_type', 'short_term')->sum('amount');
        $pendingCount = Loan::where('status', 'pending')->count();

        return view('loan.index', compact('loans', 'totalLongTerm', 'totalShortTerm', 'pendingCount'));
    }

    public function create()
    {
        $employees = Employee::where('status', 1)->get();
        return view('loan.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_type' => 'required|in:long_term,short_term',
            'amount' => 'required|numeric|min:0',
            'loan_date' => 'required|date',
            'purpose' => 'nullable|string|max:255',

            // Long-term fields
            
            'total_installments' => 'required_if:loan_type,long_term|nullable|integer|min:1',
            'emi_amount' => 'required_if:loan_type,long_term|nullable|numeric|min:0',

            // Short-term fields
            'month' => 'required_if:loan_type,short_term|nullable|integer|between:1,12',
            'year' => 'required_if:loan_type,short_term|nullable|integer|digits:4',

            'status' => 'nullable|in:pending,approved,rejected,paid',
            'remarks' => 'nullable|string',
        ]);

        // Auto-calculate EMI for long-term (if not provided)
        if ($request->loan_type === 'long_term' && empty($validated['emi_amount']) && !empty($validated['total_installments'])) {
            $validated['emi_amount'] = round($validated['amount'] / $validated['total_installments'], 2);
        }

        $validated['status'] = $validated['status'] ?? 'pending';

        Loan::create($validated);

        return redirect()->route('loan.index')
            ->with('success', 'Loan created successfully.');
    }

    public function show(Loan $loan)
    {
        $loan->load('employee');
        return view('loan.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $employees = Employee::where('status', 1)->get();
        return view('loan.edit', compact('loan', 'employees'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_type' => 'required|in:long_term,short_term',
            'amount' => 'required|numeric|min:0',
            'loan_date' => 'required|date',
            'purpose' => 'nullable|string|max:255',
            'lender_name' => 'required_if:loan_type,long_term|nullable|string|max:255',
            'total_installments' => 'required_if:loan_type,long_term|nullable|integer|min:1',
            'emi_amount' => 'required_if:loan_type,long_term|nullable|numeric|min:0',
            'month' => 'required_if:loan_type,short_term|nullable|integer|between:1,12',
            'year' => 'required_if:loan_type,short_term|nullable|integer|digits:4',
            'status' => 'required|in:pending,approved,rejected,paid',
            'remarks' => 'nullable|string',
        ]);

        if ($request->loan_type === 'long_term' && empty($validated['emi_amount']) && !empty($validated['total_installments'])) {
            $validated['emi_amount'] = round($validated['amount'] / $validated['total_installments'], 2);
        }

        $loan->update($validated);

        return redirect()->route('loan.index')
            ->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return back()->with('success', 'Loan deleted successfully.');
    }

    // Approve/Reject quick actions
    public function approve(Loan $loan)
    {
        $loan->update(['status' => 'approved']);
        return back()->with('success', 'Loan approved.');
    }

    public function reject(Loan $loan)
    {
        $loan->update(['status' => 'rejected']);
        return back()->with('success', 'Loan rejected.');
    }

    public function markPaid(Loan $loan)
    {
        $loan->update(['status' => 'paid']);
        return back()->with('success', 'Loan marked as paid.');
    }
}