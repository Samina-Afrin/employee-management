@extends('layouts.master')

@section('title', 'Post New Vacancy')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Post New Vacancy</h4>
        <a href="{{ route('recruitment.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('recruitment.store') }}">
        @csrf

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Job Title *</label>
                        <input type="text" name="job_title" class="form-control"
                               value="{{ old('job_title') }}" required>
                        @error('job_title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Total Posts *</label>
                        <input type="number" name="total_posts" class="form-control"
                               value="{{ old('total_posts', 1) }}" min="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department *</label>
                        <select name="department_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Designation *</label>
                        <select name="designation_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($designations as $des)
                                <option value="{{ $des->id }}" {{ old('designation_id') == $des->id ? 'selected' : '' }}>
                                    {{ $des->designation_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Apply Date *</label>
                        <input type="date" name="last_apply_date" class="form-control"
                               value="{{ old('last_apply_date') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Job Description</label>
                    <textarea name="job_description" class="form-control" rows="5">{{ old('job_description') }}</textarea>
                </div>

            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Vacancy
                </button>
                <a href="{{ route('recruitment.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection