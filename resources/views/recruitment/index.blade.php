@extends('layouts.master')

@section('title', 'Recruitment')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Recruitment</h1>
            <p class="page-subtitle mb-0">Manage job vacancies and applicants</p>
        </div>
        <a href="{{ route('recruitment.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Post New Vacancy
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-x-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-briefcase fs-3 text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted">Open Vacancies</small>
                            <h4 class="mb-0 text-success">{{ $stats['open'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-lock fs-3 text-secondary"></i>
                        </div>
                        <div>
                            <small class="text-muted">Closed Vacancies</small>
                            <h4 class="mb-0">{{ $stats['closed'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-people fs-3 text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted">Total Applicants</small>
                            <h4 class="mb-0 text-primary">{{ $stats['applicants'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-check2-circle fs-3 text-warning"></i>
                        </div>
                        <div>
                            <small class="text-muted">Selected</small>
                            <h4 class="mb-0 text-warning">{{ $stats['selected'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-list-ul"></i> Job Vacancies</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th class="text-center">Posts</th>
                            <th>Last Apply</th>
                            <th>Status</th>
                            <th class="text-center">Applicants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vacancies as $vacancy)
                            @php
                                $applicantCount = \App\Models\Recruitment::where('job_title', $vacancy->job_title)
                                    ->whereNotNull('applicant_name')->count();
                            @endphp
                            <tr>
                                <td>{{ $vacancy->id }}</td>
                                <td><strong>{{ $vacancy->job_title }}</strong></td>
                                <td>{{ $vacancy->department->department_name ?? '—' }}</td>
                                <td>{{ $vacancy->designation->designation_name ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $vacancy->total_posts }}</span>
                                </td>
                                <td>{{ $vacancy->last_apply_date->format('d M Y') }}</td>
                                <td>
                                    @if($vacancy->vacancy_status == 1)
                                        <span class="badge bg-success">Open</span>
                                    @else
                                        <span class="badge bg-secondary">Closed</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('recruitment.applicants', $vacancy->id) }}"
                                       class="badge bg-primary text-decoration-none">
                                        {{ $applicantCount }} <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('recruitment.edit', $vacancy->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('recruitment.destroy', $vacancy->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this vacancy?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No vacancies posted yet.
                                    <br>
                                    <a href="{{ route('recruitment.create') }}" class="btn btn-sm btn-success mt-2">
                                        <i class="bi bi-plus-circle"></i> Post First Vacancy
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($vacancies->hasPages())
            <div class="card-footer bg-white">
                {{ $vacancies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection