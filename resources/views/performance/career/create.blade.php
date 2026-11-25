
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <h3 class="fw-bold mb-4">New Increment / Promotion Recommendation</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('performance.career.store') }}"
                  method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Employee *</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Recommendation Type *</label>
                        <select name="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="increment">Salary Increment</option>
                            <option value="promotion">Promotion</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Current Salary (BDT)</label>
                        <input type="number" name="current_salary"
                               class="form-control" min="0" step="0.01">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Proposed Salary (BDT)</label>
                        <input type="number" name="proposed_salary"
                               class="form-control" min="0" step="0.01">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Current Designation</label>
                        <input type="text" name="current_designation"
                               class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Proposed Designation</label>
                        <input type="text" name="proposed_designation"
                               class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Effective Date *</label>
                        <input type="date" name="effective_date"
                               class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Reason / Recommendation</label>
                        <textarea name="reason" class="form-control"
                                  rows="4"></textarea>
                    </div>

                </div>

                <button class="btn btn-primary mt-4">
                    Submit Recommendation
                </button>

                <a href="{{ route('performance.career.index') }}"
                   class="btn btn-secondary mt-4">Cancel</a>

            </form>
        </div>
    </div>
</div>

@endsection
