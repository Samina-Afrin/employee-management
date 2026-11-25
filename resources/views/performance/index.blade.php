
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Performance Management</h3>
            <p class="text-muted">Employee Performance & Career Overview</p>
        </div>

        <a href="{{ route('performance.appraisals.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Appraisal
        </a>
    </div>

    <div class="row g-3">

        @php
            $cards = [
                ['Total Appraisals', $totalAppraisals, 'bi-clipboard-check'],
                ['Completed', $completedAppraisals, 'bi-check-circle'],
                ['Pending Reviews', $pendingAppraisals, 'bi-clock'],
                ['Total Goals', $totalGoals, 'bi-bullseye'],
                ['Goals Completed', $completedGoals, 'bi-trophy'],
                ['Pending Probation', $pendingProbations, 'bi-person-check'],
                ['Pending Approval', $pendingRecommendations, 'bi-award'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-2">{{ $card[0] }}</p>
                                <h3 class="fw-bold">{{ $card[1] }}</h3>
                            </div>
                            <i class="bi {{ $card[2] }} fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Appraisal Cycles</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cycle Name</th>
                            <th>Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($cycles as $cycle)
                            <tr>
                                <td>{{ $cycle->name }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $cycle->cycle_type)) }}</td>
                                <td>{{ $cycle->start_date }}</td>
                                <td>{{ $cycle->end_date }}</td>
                                <td>{{ $cycle->review_deadline ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($cycle->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No appraisal cycle found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Recent Appraisals</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Appraisal Cycle</th>
                        <th>Final Rating</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentAppraisals as $appraisal)
                        <tr>
                            <td>{{ $appraisal->employee->full_name ?? 'N/A' }}</td>
                            <td>{{ $appraisal->cycle->name ?? 'N/A' }}</td>
                            <td>{{ $appraisal->final_rating ?? 'Pending' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $appraisal->status)) }}</td>
                            <td>
                                <a href="{{ route('performance.appraisals.show', $appraisal->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No appraisal records available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
