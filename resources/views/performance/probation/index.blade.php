
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Probation Evaluation</h3>

        <a href="{{ route('performance.probation.create') }}"
           class="btn btn-primary">+ Add Evaluation</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Review Stage</th>
                        <th>Review Date</th>
                        <th>Rating</th>
                        <th>Recommendation</th>
                        <th>Comments</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($evaluations as $item)
                        <tr>
                            <td>{{ $item->employee->full_name ?? 'N/A' }}</td>
                            <td>{{ str_replace('_', ' ', $item->review_stage) }}</td>
                            <td>{{ $item->review_date }}</td>
                            <td>{{ $item->rating ?? '-' }}</td>
                            <td>{{ $item->recommendation ? ucfirst($item->recommendation) : 'Pending' }}</td>
                            <td>{{ $item->comments ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                No probation records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $evaluations->links() }}

        </div>
    </div>
</div>

@endsection
