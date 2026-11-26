<?php

namespace App\Http\Controllers;

use App\Models\CompanyAsset;
use App\Models\Employee;
use Illuminate\Http\Request;

class CompanyAssetController extends Controller
{
    public function index(Request $request)
    {
        $query = CompanyAsset::with('employee:id,name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $assets = $query->latest()->paginate(15)->withQueryString();

        return view('company-assets.index', compact('assets'));
    }

    public function create()
    {
        $employees = Employee::select('id', 'name')->orderBy('name')->get();
        $categories = ['Laptop', 'Phone', 'Other'];

        return view('company-assets.create', compact('employees', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code'    => 'required|string|max:50|unique:company_assets,asset_code',
            'asset_name'    => 'required|string|max:150',
            'category'      => 'required|string|max:50',
            'serial_number' => 'nullable|string|max:100',
            'employee_id'   => 'nullable|exists:employees,id',
            'given_date'    => 'nullable|date',
            'return_date'   => 'nullable|date|after_or_equal:given_date',
            'status'        => 'required|in:1,2,3',
            'note'          => 'nullable|string|max:255',
        ]);

        // Consistency: assigned থাকলে employee_id ও given_date থাকতে হবে
        if ($validated['status'] == CompanyAsset::STATUS_ASSIGNED) {
            if (empty($validated['employee_id'])) {
                return back()->withInput()
                    ->withErrors(['employee_id' => 'Assigned asset must have an employee.']);
            }
            $validated['given_date'] = $validated['given_date'] ?? now()->toDateString();
        }

        CompanyAsset::create($validated);

        return redirect()->route('company-assets.index')
            ->with('success', 'Asset created successfully.');
    }

   public function show(CompanyAsset $companyAsset)
{
    $companyAsset->load('employee:id,name');

    return view('company-assets.show', compact('companyAsset'));
}

    public function edit(CompanyAsset $companyAsset)
    {
        $employees = Employee::select('id', 'name')->orderBy('name')->get();
        $categories = ['Laptop', 'Phone', 'Other'];

        return view('company-assets.edit', compact('companyAsset', 'employees', 'categories'));
    }

    public function update(Request $request, CompanyAsset $companyAsset)
    {
        $validated = $request->validate([
            'asset_code'    => 'required|string|max:50|unique:company_assets,asset_code,' . $companyAsset->id,
            'asset_name'    => 'required|string|max:150',
            'category'      => 'required|string|max:50',
            'serial_number' => 'nullable|string|max:100',
            'employee_id'   => 'nullable|exists:employees,id',
            'given_date'    => 'nullable|date',
            'return_date'   => 'nullable|date|after_or_equal:given_date',
            'status'        => 'required|in:1,2,3',
            'note'          => 'nullable|string|max:255',
        ]);

        if ($validated['status'] == CompanyAsset::STATUS_ASSIGNED && empty($validated['employee_id'])) {
            return back()->withInput()
                ->withErrors(['employee_id' => 'Assigned asset must have an employee.']);
        }

        
        if ($validated['status'] == CompanyAsset::STATUS_AVAILABLE) {
            $validated['employee_id'] = null;
            $validated['return_date'] = $validated['return_date'] ?? now()->toDateString();
        }

        $companyAsset->update($validated);

        return redirect()->route('company-assets.index')
            ->with('success', 'Asset updated successfully.');
    }

    public function destroy(CompanyAsset $companyAsset)
    {
        $companyAsset->delete();

        return redirect()->route('company-assets.index')
            ->with('success', 'Asset deleted successfully.');
    }
}