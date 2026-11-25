
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <h3 class="fw-bold mb-4">Update Performance Rating</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('performance.appraisals.update', $appraisal->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Employee</label>
                    <input type="text" class="form-control"
                           value="{{ $appraisal->employee->full_name ?? 'N/A' }}"
                           disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Appraisal Cycle</label>
                    <input type="text" class="form-control"
                           value="{{ $appraisal->cycle->name ?? 'N/A' }}"
                           disabled>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Self Rating</label>
                        <input type="number" name="self_rating"
                               class="form-control" min="1" max="5" step="0.1"
                               value="{{ old('self_rating', $appraisal->self_rating) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Manager Rating</label>
                        <input type="number" name="manager_rating"
                               class="form-control" min="1" max="5" step="0.1"
                               value="{{ old('manager_rating', $appraisal->manager_rating) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">HR Rating</label>
                        <input type="number" name="hr_rating"
                               class="form-control" min="1" max="5" step="0.1"
                               value="{{ old('hr_rating', $appraisal->hr_rating) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft','submitted','manager_review','hr_review','completed'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('status', $appraisal->status) == $status)>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Self Comments</label>
                        <textarea name="self_comments" class="form-control">{{ old('self_comments', $appraisal->self_comments) }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Manager Comments</label>
                        <textarea name="manager_comments" class="form-control">{{ old('manager_comments', $appraisal->manager_comments) }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">HR Comments</label>
                        <textarea name="hr_comments" class="form-control">{{ old('hr_comments', $appraisal->hr_comments) }}</textarea>
                    </div>

                </div>

                <button class="btn btn-primary mt-4">Update Appraisal</button>

                <a href="{{ route('performance.appraisals.index') }}"
                   class="btn btn-secondary mt-4">Cancel</a>

            </form>
        </div>
    </div>
</div>

@endsection
