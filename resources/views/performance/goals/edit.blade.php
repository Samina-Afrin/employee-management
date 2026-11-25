
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <h3 class="fw-bold mb-4">Update Employee Goal</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('performance.goals.update', $goal->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Employee</label>
                    <input type="text" class="form-control"
                           value="{{ $goal->employee->full_name ?? 'N/A' }}"
                           disabled>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Goal Title</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $goal->title) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">KPI</label>
                        <input type="text" name="kpi" class="form-control"
                               value="{{ old('kpi', $goal->kpi) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Target Value</label>
                        <input type="number" name="target_value"
                               class="form-control" step="0.01"
                               value="{{ old('target_value', $goal->target_value) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Achieved Value</label>
                        <input type="number" name="achieved_value"
                               class="form-control" step="0.01"
                               value="{{ old('achieved_value', $goal->achieved_value) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Progress (%)</label>
                        <input type="number" name="progress"
                               class="form-control" min="0" max="100"
                               value="{{ old('progress', $goal->progress) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending','in_progress','completed','cancelled'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('status', $goal->status) == $status)>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date"
                               class="form-control"
                               value="{{ old('start_date', $goal->start_date) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date"
                               class="form-control"
                               value="{{ old('due_date', $goal->due_date) }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $goal->description) }}</textarea>
                    </div>

                </div>

                <button class="btn btn-primary mt-4">Update Goal</button>
                <a href="{{ route('performance.goals.index') }}"
                   class="btn btn-secondary mt-4">Cancel</a>

            </form>
        </div>
    </div>
</div>

@endsection
