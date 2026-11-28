@extends('layouts.master')

@section('title', 'Performance Review Details')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-person-badge me-2"></i>
                {{ $performance->employee->name ?? '-' }} — {{ $performance->review_year }}
            </h4>
            <p class="text-muted small mb-0">
                {{ $performance->period === 'jan_dec' ? 'Jan–Dec' : 'Jul–Jun' }} •
                Manager: {{ $performance->manager->name ?? 'N/A' }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($performance->status === 'approved')
                <span class="badge bg-success fs-6">Approved</span>
            @elseif($performance->status === 'submitted')
                <span class="badge bg-warning fs-6">Submitted</span>
            @else
                <span class="badge bg-secondary fs-6">Draft</span>
            @endif
            <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Errors --}}
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

    <div class="row g-4">

        {{-- ==================== RATING CARD ==================== --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small fw-bold mb-3">
                        <i class="bi bi-star-fill text-warning me-1"></i> Overall Rating
                    </h6>

                    <div class="text-center mb-3">
                        <div class="display-4 fw-bold text-warning">
                            {{ number_format($performance->rating, 2) }}
                            <small class="fs-6 text-muted">/ 5.00</small>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('performance.rating', $performance) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Rating (0–5)</label>
                            <input type="number" step="0.01" min="0" max="5"
                                   name="rating" value="{{ $performance->rating }}"
                                   class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Manager Comment</label>
                            <textarea name="manager_comment" rows="3" class="form-control"
                                      placeholder="Write feedback...">{{ $performance->manager_comment }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Save Rating
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ==================== GOALS / KPI + WORKFLOW ==================== --}}
        <div class="col-lg-8">

            {{-- ---------- Goals Card ---------- --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted small fw-bold mb-0">
                            <i class="bi bi-bullseye text-primary me-1"></i> Goals / KPI
                        </h6>
                        <span class="badge bg-primary-subtle text-primary-emphasis">
                            {{ $performance->goals->count() }} goal(s)
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th class="text-center">Target</th>
                                    <th class="text-center">Achieved</th>
                                    <th class="text-center">Weight</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($performance->goals as $g)
                                <tr>
                                    <td class="fw-semibold">{{ $g->title }}</td>
                                    <td class="text-center">{{ number_format($g->target, 2) }}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('performance.goals.update', $g) }}"
                                              class="d-flex gap-1 justify-content-center">
                                            @csrf @method('PATCH')
                                            <input type="number" step="0.01" name="achieved"
                                                   value="{{ $g->achieved }}"
                                                   class="form-control form-control-sm" style="width: 80px;">
                                            <button class="btn btn-sm btn-success" title="Update">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">{{ $g->weight }}%</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis">
                                            {{ number_format($g->score, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('performance.goals.destroy', $g) }}"
                                              onsubmit="return confirm('Delete this goal?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                        No goals added yet. Add one below.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    {{-- ==================== ADD GOAL FORM ==================== --}}
                    <form method="POST" action="{{ route('performance.goals.store', $performance) }}">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Goal Title</label>
                                <input name="title" class="form-control form-control-sm"
                                       placeholder="e.g. Sales Target" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Target</label>
                                <input type="number" step="0.01" name="target"
                                       class="form-control form-control-sm" placeholder="100" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Weight %</label>
                                <input type="number" name="weight"
                                       class="form-control form-control-sm" placeholder="20"
                                       min="1" max="100" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Achieved</label>
                                <input type="number" step="0.01" name="achieved"
                                       class="form-control form-control-sm" placeholder="0">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-plus-lg"></i> Add Goal
                                </button>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Score = (Achieved / Target) × Weight. Rating auto updates based on total score.
                        </small>
                    </form>
                    {{-- ==================== END ADD GOAL ==================== --}}
                </div>
            </div>

            {{-- ---------- Workflow Card ---------- --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small fw-bold mb-3">
                        <i class="bi bi-diagram-3 text-info me-1"></i> Approval Workflow
                    </h6>

                    <div class="d-flex gap-2 flex-wrap">
                        @if($performance->status === 'draft')
                            <form method="POST" action="{{ route('performance.submit', $performance) }}">
                                @csrf
                                <button class="btn btn-warning">
                                    <i class="bi bi-send me-1"></i> Submit for Approval
                                </button>
                            </form>
                        @elseif($performance->status === 'submitted')
                            <form method="POST" action="{{ route('performance.approve', $performance) }}">
                                @csrf
                                <button class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Approve
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success mb-0 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                This review has been approved on
                                {{ $performance->review_date?->format('d M Y') ?? 'N/A' }}.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection