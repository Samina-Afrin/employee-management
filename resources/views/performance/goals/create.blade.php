
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <h3 class="fw-bold mb-4">Add Employee Goal</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('performance.goals.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Employee *</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    @selected(old('employee_id') == $employee->id)>
                                    {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Goal Title *</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">KPI</label>
                        <input type="text" name="kpi" class="form-control"
                               placeholder="Example: Monthly Sales Target">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Target Value</label>
                        <input type="number" name="target_value"
                               class="form-control" step="0.01" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Achieved Value</label>
                        <input type="number" name="achieved_value"
                               class="form-control" step="0.01" min="0" value="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Progress (%) *</label>
                        <input type="number" name="progress" class="form-control"
                               min="0" max="100" value="0" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Date *</label>
                        <input type="date" name="start_date"
                               class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Due Date *</label>
                        <input type="date" name="due_date"
                               class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                                  rows="3"></textarea>
                    </div>

                </div>

                <button class="btn btn-primary mt-4">Save Goal</button>

                <a href="{{ route('performance.goals.index') }}"
                   class="btn btn-secondary mt-4">Cancel</a>

            </form>

        </div>
    </div>

</div>

@endsection
