
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Increment & Promotion</h3>

        <a href="{{ route('performance.career.create') }}"
           class="btn btn-primary">+ New Recommendation</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Current Salary</th>
                        <th>Proposed Salary</th>
                        <th>New Designation</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                        <th>Approval</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recommendations as $item)
                        <tr>
                            <td>{{ $item->employee->full_name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($item->type) }}</td>
                            <td>{{ $item->current_salary ? '৳ '.number_format($item->current_salary, 2) : '-' }}</td>
                            <td>{{ $item->proposed_salary ? '৳ '.number_format($item->proposed_salary, 2) : '-' }}</td>
                            <td>{{ $item->proposed_designation ?? '-' }}</td>
                            <td>{{ $item->effective_date->format('d M Y') }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>
                                @if($item->status === 'pending')
                                    <form action="{{ route('performance.career.approve', $item->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Approve this recommendation?')">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('performance.career.reject', $item->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Reject this recommendation?')">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No recommendations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $recommendations->links() }}

        </div>
    </div>
</div>

@endsection
