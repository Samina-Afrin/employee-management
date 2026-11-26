<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Employee;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::with([
            'employee:id,name',
            'newDesignation:id,designation_name',
            'newDepartment:id,department_name',
        ]);

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->whereHas('employee', function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('promotion_type', $request->input('type'));
        }

        $promotions = $query->latest('promotion_date')->paginate(15)->withQueryString();

        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        $employees    = Employee::select('id', 'name')->orderBy('name')->get();
        $designations = Designation::select('id', 'designation_name')->orderBy('designation_name')->get();
        $departments  = Department::select('id', 'department_name')->orderBy('department_name')->get();

        return view('promotions.create', compact('employees', 'designations', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'        => 'required|exists:employees,id',
            'old_designation_id' => 'nullable|exists:designations,id',
            'new_designation_id' => 'nullable|exists:designations,id',
            'old_department_id'  => 'nullable|exists:departments,id',
            'new_department_id'  => 'nullable|exists:departments,id',
            'old_salary'         => 'nullable|numeric|min:0',
            'new_salary'         => 'nullable|numeric|min:0',
            'promotion_date'     => 'required|date',
            'promotion_type'     => 'required|string|max:50',
            'approved_by'        => 'nullable|string|max:100',
            'remarks'            => 'nullable|string',
        ]);

        Promotion::create($validated);

        return redirect()->route('promotions.index')
            ->with('success', 'Promotion record created successfully.');
    }

    public function show(Promotion $promotion)
    {
        $promotion->load([
            'employee:id,name',
            'oldDesignation:id,designation_name',
            'newDesignation:id,designation_name',
            'oldDepartment:id,department_name',
            'newDepartment:id,department_name',
        ]);

        return view('promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $employees    = Employee::select('id', 'name')->orderBy('name')->get();
        $designations = Designation::select('id', 'designation_name')->orderBy('designation_name')->get();
        $departments  = Department::select('id', 'department_name')->orderBy('department_name')->get();

        return view('promotions.edit', compact('promotion', 'employees', 'designations', 'departments'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'employee_id'        => 'required|exists:employees,id',
            'old_designation_id' => 'nullable|exists:designations,id',
            'new_designation_id' => 'nullable|exists:designations,id',
            'old_department_id'  => 'nullable|exists:departments,id',
            'new_department_id'  => 'nullable|exists:departments,id',
            'old_salary'         => 'nullable|numeric|min:0',
            'new_salary'         => 'nullable|numeric|min:0',
            'promotion_date'     => 'required|date',
            'promotion_type'     => 'required|string|max:50',
            'approved_by'        => 'nullable|string|max:100',
            'remarks'            => 'nullable|string',
        ]);

        $promotion->update($validated);

        return redirect()->route('promotions.index')
            ->with('success', 'Promotion record updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', 'Promotion record deleted successfully.');
    }
}