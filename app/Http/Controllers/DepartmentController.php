<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('department_name', 'like', "%{$search}%")
                  ->orWhere('department_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $departments = $query->latest()->paginate(10);

        return view('department.index', compact('departments'));
    }

    public function create()
    {
        return view('department.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Department::create($data);

        return redirect()->route('department.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        return view('department.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('department.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $this->validateData($request, $department->id);
        $department->update($data);

        return redirect()->route('department.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('department.index')
            ->with('success', 'Department deleted successfully.');
    }

    private function validateData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'department_name' => 'required|string|max:100|unique:departments,department_name,' . $ignoreId,
            'department_code' => 'nullable|string|max:20',
            'description'     => 'nullable|string|max:255',
            'status'          => 'required|in:0,1',
        ]);
    }
}