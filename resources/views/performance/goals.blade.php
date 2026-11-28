@extends('layouts.master')

@section('title', 'Performance Goals')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><i class="bi bi-bullseye me-2"></i>Performance Goals / KPI</h4>
            <p class="text-muted small mb-0">All KPI goals across performance reviews</p>
        </div>
        <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Reviews
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Employee</th>
                            <th>Review</th>
                            <th>Goal</th>
                            <th class="text-center">Target</th>
                            <th class="text-center">Achieved</th>
                            <th class="text-center">Weight</th>
                            <th class="text-center">Score</th>
                            <th class="text-end px-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($goals as $g)
                        <tr>
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                         style="width: 32px; height: 32px; font-size: 13px;">
                                        {{ strtoupper(substr($g->review->employee->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $g->review->employee->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div class="fw-semibold">{{ $g->review->review_year ?? '-' }}</div>
                                    <div class="text-muted">
                                        {{ ($g->review->period ?? '') === 'jan_dec' ? 'Jan–Dec' : 'Jul–Jun' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $g->title }}</div>
                                @if($g->description)
                                    <div class="small text-muted">{{ Str::limit($g->description, 40) }}</div>
                                @endif
                            </td>
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
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    {{ $g->weight }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary-emphasis fs-6">
                                    {{ number_format($g->score, 2) }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('performance.show', $g->performance_review_id) }}"
                                   class="btn btn-sm btn-outline-primary" title="View Review">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('performance.goals.destroy', $g) }}"
                                      class="d-inline" onsubmit="return confirm('Delete this goal?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                                <div class="fw-semibold mb-1">No goals yet</div>
                                <div class="small">
                                    Add goals from a
                                    <a href="{{ route('performance.index') }}">performance review</a> page.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($goals->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $goals->links() }}
        </div>
        @endif
    </div>

    {{-- Summary Stats --}}
    @if($goals->count())
    <div class="row g-3 mt-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Goals</div>
                    <div class="h4 mb-0 fw-bold">{{ $goals->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Weight</div>
                    <div class="h4 mb-0 fw-bold">
                        {{ $goals->sum('weight') }}%
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Avg Score</div>
                    <div class="h4 mb-0 fw-bold">
                        {{ number_format($goals->avg('score'), 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Achieved</div>
                    <div class="h4 mb-0 fw-bold">
                        {{ number_format($goals->sum('achieved'), 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection