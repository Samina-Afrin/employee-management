
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <h3 class="fw-bold mb-4">Create Employee Appraisal</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('performance.appraisals.store') }}"
                  method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Employee *</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }}
                                    ({{ $employee->employee_code ?? $employee->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Appraisal Cycle *</label>
                        <select name="appraisal_cycle_id" class="form-select" required>
                            <option value="">Select Cycle</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}">
                                    {{ $cycle->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Self Rating (1-5)</label>
                        <input type="number" name="self_rating"
                               class="form-control" min="1" max="5"
                               step="0.1" value="{{ old('self_rating') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Self Comments</label>
                        <textarea name="self_comments"
                                  class="form-control">{{ old('self_comments') }}</textarea>
                    </div>

                </div>

                <div class="mt-4">
                    <button class="btn btn-primary">Save Appraisal</button>
                    <a href="{{ route('performance.appraisals.index') }}"
                       class="btn btn-secondary">Cancel</a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
