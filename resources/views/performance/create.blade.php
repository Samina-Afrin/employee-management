@extends('layouts.master')

@section('title', 'New Performance Review')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>New Performance Review</h4>
            <p class="text-muted small mb-0">Create a new appraisal cycle for an employee</p>
        </div>
        <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('performance.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Manager</label>
                        <select name="manager_id" class="form-select">
                            <option value="">-- Select Manager --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('manager_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Review Year <span class="text-danger">*</span></label>
                        <input type="number" name="review_year"
                               value="{{ old('review_year', date('Y')) }}"
                               class="form-control @error('review_year') is-invalid @enderror"
                               min="2000" max="2100" required>
                        @error('review_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Period <span class="text-danger">*</span></label>
                        <select name="period" class="form-select" required>
                            <option value="jan_dec" {{ old('period') == 'jan_dec' ? 'selected' : '' }}>
                                January – December (Calendar Year)
                            </option>
                            <option value="jul_jun" {{ old('period') == 'jul_jun' ? 'selected' : '' }}>
                                July – June (Fiscal Year)
                            </option>
                        </select>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('performance.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Create Review
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection