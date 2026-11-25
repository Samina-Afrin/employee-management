
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <h3 class="fw-bold mb-4">Add Probation Evaluation</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('performance.probation.store') }}"
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
                        <label class="form-label">Review Stage *</label>
                        <select name="review_stage" class="form-select" required>
                            <option value="">Select Stage</option>
                            <option value="30_days">30 Days</option>
                            <option value="60_days">60 Days</option>
                            <option value="90_days">90 Days</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Review Date *</label>
                        <input type="date" name="review_date"
                               class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Performance Rating</label>
                        <input type="number" name="rating"
                               class="form-control" min="1" max="5" step="0.1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Recommendation</label>
                        <select name="recommendation" class="form-select">
                            <option value="">Select Recommendation</option>
                            <option value="confirm">Confirm Employee</option>
                            <option value="extend">Extend Probation</option>
                            <option value="terminate">End Employment</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Comments</label>
                        <textarea name="comments" class="form-control"
                                  rows="4"></textarea>
                    </div>

                </div>

                <button class="btn btn-primary mt-4">Save Evaluation</button>
                <a href="{{ route('performance.probation.index') }}"
                   class="btn btn-secondary mt-4">Cancel</a>

            </form>
        </div>
    </div>
</div>

@endsection
