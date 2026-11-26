@extends('layouts.master')

@section('content')
@php
    $departmentOptions = [
        ['id' => 1, 'name' => 'HR'],
        ['id' => 3, 'name' => 'Account'],
        ['id' => 4, 'name' => 'Sales'],
        ['id' => '', 'name' => 'Operations'],
    ];

    $designationOptions = [
        ['id' => 4, 'name' => 'HR Manager'],
        ['id' => 5, 'name' => 'Accounts Officer'],
        ['id' => 6, 'name' => 'Sales Manager'],
        ['id' => '', 'name' => 'Operations Manager'],
    ];

    $selectedDate = old(
        'promotion_date',
        optional($promotion->promotion_date)->format('Y-m-d') ?? date('Y-m-d')
    );
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Edit Promotion Record</h3>
        <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('promotions.update', $promotion) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Employee -->
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" id="employee_id" class="form-select" required>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id', $promotion->employee_id) == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date -->
            <div class="col-md-6 mb-3">
                <label for="promotion_date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="promotion_date" id="promotion_date" class="form-control"
                       value="{{ $selectedDate }}" required>
            </div>

            <!-- Type -->
            <div class="col-md-6 mb-3">
                <label for="promotion_type" class="form-label">Type <span class="text-danger">*</span></label>
                <select name="promotion_type" id="promotion_type" class="form-select" required>
                    @foreach(['Promotion', 'Increment', 'Transfer'] as $type)
                        <option value="{{ $type }}" {{ old('promotion_type', $promotion->promotion_type) == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Approved By -->
            <div class="col-md-6 mb-3">
                <label for="approved_by" class="form-label">Approved By</label>
                <input type="text" name="approved_by" id="approved_by" class="form-control"
                       value="{{ old('approved_by', $promotion->approved_by) }}" placeholder="e.g. HR Manager">
            </div>

            <!-- Old Department -->
            <div class="col-md-6 mb-3">
                <label for="old_department_id" class="form-label">Old Department</label>
                <select name="old_department_id" id="old_department_id" class="form-select">
                    @foreach($departmentOptions as $dept)
                        <option value="{{ $dept['id'] }}" {{ old('old_department_id', $promotion->old_department_id) == $dept['id'] && $dept['id'] !== '' ? 'selected' : '' }}>
                            {{ $dept['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- New Department -->
            <div class="col-md-6 mb-3">
                <label for="new_department_id" class="form-label">New Department</label>
                <select name="new_department_id" id="new_department_id" class="form-select">
                    @foreach($departmentOptions as $dept)
                        <option value="{{ $dept['id'] }}" {{ old('new_department_id', $promotion->new_department_id) == $dept['id'] && $dept['id'] !== '' ? 'selected' : '' }}>
                            {{ $dept['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Old Designation -->
            <div class="col-md-6 mb-3">
                <label for="old_designation_id" class="form-label">Old Designation</label>
                <select name="old_designation_id" id="old_designation_id" class="form-select">
                    @foreach($designationOptions as $desig)
                        <option value="{{ $desig['id'] }}" {{ old('old_designation_id', $promotion->old_designation_id) == $desig['id'] && $desig['id'] !== '' ? 'selected' : '' }}>
                            {{ $desig['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- New Designation -->
            <div class="col-md-6 mb-3">
                <label for="new_designation_id" class="form-label">New Designation</label>
                <select name="new_designation_id" id="new_designation_id" class="form-select">
                    @foreach($designationOptions as $desig)
                        <option value="{{ $desig['id'] }}" {{ old('new_designation_id', $promotion->new_designation_id) == $desig['id'] && $desig['id'] !== '' ? 'selected' : '' }}>
                            {{ $desig['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Old Salary -->
            <div class="col-md-6 mb-3">
                <label for="old_salary" class="form-label">Old Salary</label>
                <input type="number" step="0.01" min="0" name="old_salary" id="old_salary" class="form-control"
                       value="{{ old('old_salary', $promotion->old_salary) }}" placeholder="e.g. 25000">
            </div>

            <!-- New Salary -->
            <div class="col-md-6 mb-3">
                <label for="new_salary" class="form-label">New Salary</label>
                <input type="number" step="0.01" min="0" name="new_salary" id="new_salary" class="form-control"
                       value="{{ old('new_salary', $promotion->new_salary) }}" placeholder="e.g. 30000">
            </div>

            <!-- Remarks -->
            <div class="col-12 mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea name="remarks" id="remarks" rows="2" class="form-control" placeholder="Optional notes...">{{ old('remarks', $promotion->remarks) }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection