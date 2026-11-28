@extends('layouts.master')

@section('title', 'Performance Review')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-clipboard-check me-2"></i>Performance Review</h4>
            <p class="text-muted small mb-0">Manage employee performance appraisals</p>
        </div>
        <a href="{{ route('performance.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Review
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
                            <th>Year</th>
                            <th>Period</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th class="text-end px-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $r)
                        <tr>
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                         style="width: 36px; height: 36px; font-size: 14px;">
                                        {{ strtoupper(substr($r->employee->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $r->employee->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td>{{ $r->review_year }}</td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis">
                                    {{ $r->period === 'jan_dec' ? 'Jan–Dec' : 'Jul–Jun' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-warning">{{ number_format($r->rating, 2) }}</span>
                                <small class="text-muted">/5</small>
                            </td>
                            <td>
                                @if($r->status === 'approved')
                                    <span class="badge bg-success-subtle text-success-emphasis">Approved</span>
                                @elseif($r->status === 'submitted')
                                    <span class="badge bg-warning-subtle text-warning-emphasis">Submitted</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">Draft</span>
                                @endif
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('performance.show', $r) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No reviews yet. Click "New Review" to create one.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reviews->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>

</div>
@endsection