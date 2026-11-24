@extends('layouts.master')

@section('title', 'Edit Vacancy')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Vacancy</h4>
        <a href="{{ route('recruitment.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('recruitment.update', $recruitment->id) }}">
        @csrf @method('PUT')

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Job Title *</label>
                        <input type="text" name="job_title" class="form-control"
                               value="{{ old('job_title', $recruitment->job_title) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Total Posts *</label>
                        <input type="number" name="total_posts" class="form-control"
                               value="{{ old('total_posts', $recruitment->total_posts) }}" min="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department *</label>
                        <select name="department_id" class="form-select" required>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ $recruitment->department_id == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Designation *</label>
                        <select name="designation_id" class="form-select" required>
                            @foreach($designations as $des)
                                <option value="{{ $des->id }}"
                                    {{ $recruitment->designation_id == $des->id ? 'selected' : '' }}>
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
                               value="{{ old('last_apply_date', $recruitment->last_apply_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vacancy Status *</label>
                        <select name="vacancy_status" class="form-select" required>
                            <option value="1" {{ $recruitment->vacancy_status == 1 ? 'selected' : '' }}>Open</option>
                            <option value="0" {{ $recruitment->vacancy_status == 0 ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Job Description</label>
                    <textarea name="job_description" class="form-control" rows="5">{{ old('job_description', $recruitment->job_description) }}</textarea>
                </div>

            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Vacancy
                </button>
                <a href="{{ route('recruitment.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection