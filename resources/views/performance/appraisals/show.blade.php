
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Appraisal Details</h3>
        <a href="{{ route('performance.appraisals.index') }}"
           class="btn btn-secondary">Back</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5>Employee Information</h5>
            <hr>

            <p><strong>Name:</strong>
                {{ $appraisal->employee->full_name ?? 'N/A' }}</p>

            <p><strong>Cycle:</strong>
                {{ $appraisal->cycle->name ?? 'N/A' }}</p>

            <hr>

            <h5>Performance Rating</h5>

            <div class="row g-3 mt-2">
                @foreach([
                    'Self Rating' => $appraisal->self_rating,
                    'Manager Rating' => $appraisal->manager_rating,
                    'HR Rating' => $appraisal->hr_rating,
                    'Final Rating' => $appraisal->final_rating
                ] as $label => $rating)

                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">{{ $label }}</small>
                            <h3 class="fw-bold text-primary">
                                {{ $rating ?? 'Pending' }}
                            </h3>
                        </div>
                    </div>

                @endforeach
            </div>

            <hr>

            <p><strong>Self Comments:</strong>
                {{ $appraisal->self_comments ?? 'N/A' }}</p>

            <p><strong>Manager Comments:</strong>
                {{ $appraisal->manager_comments ?? 'N/A' }}</p>

            <p><strong>HR Comments:</strong>
                {{ $appraisal->hr_comments ?? 'N/A' }}</p>

            <p><strong>Status:</strong>
                {{ ucfirst(str_replace('_', ' ', $appraisal->status)) }}</p>

            <a href="{{ route('performance.appraisals.edit', $appraisal->id) }}"
               class="btn btn-primary mt-3">Edit Rating</a>

        </div>
    </div>

</div>

@endsection
