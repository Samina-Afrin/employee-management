<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $query = Designation::with('department');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('designation_name', 'like', "%{$search}%")
                  ->orWhere('designation_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $designations = $query->latest()->paginate(10);
        $departments = Department::orderBy('department_name')->get();

        return view('designation.index', compact('designations', 'departments'));
    }

    public function create()
    {
        return view('designation.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Designation::create($data);

        return redirect()->route('designation.index')
            ->with('success', 'Designation created successfully.');
    }

    public function show(Designation $designation)
    {
        return view('designation.show', compact('designation'));
    }

    public function edit(Designation $designation)
    {
        return view('designation.edit', compact('designation'));
    }

    public function update(Request $request, Designation $designation)
    {
        $data = $this->validateData($request, $designation->id);
        $designation->update($data);

        return redirect()->route('designation.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('designation.index')
            ->with('success', 'Designation deleted successfully.');
    }

    private function validateData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'designation_name' => 'required|string|max:100',
            'designation_code' => 'nullable|string|max:20',
            'description'      => 'nullable|string|max:255',
            'status'           => 'required|in:0,1',
        ]);
    }
}