@extends('layouts.master')

@section('title', 'Applicants — ' . $recruitment->job_title)

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Applicants — {{ $recruitment->job_title }}</h4>
            <small class="text-muted">
                {{ $recruitment->department->department_name ?? '' }}
                @if($recruitment->designation) / {{ $recruitment->designation->designation_name }} @endif
            </small>
        </div>
        <a href="{{ route('recruitment.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Add applicant --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-person-plus"></i> Add New Applicant</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('recruitment.apply', $recruitment->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="applicant_name" class="form-control"
                               placeholder="Applicant Name *" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="phone" class="form-control"
                               placeholder="Phone *" required>
                    </div>
                    <div class="col-md-3">
                        <input type="email" name="email" class="form-control"
                               placeholder="Email">
                    </div>
                    <div class="col-md-2">
                        <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success w-100"><i class="bi bi-plus"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Applicants table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-people"></i> Applicants ({{ $applicants->count() }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>CV</th>
                            <th>Stage</th>
                            <th>Interview</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicants as $app)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $app->applicant_name }}</strong></td>
                                <td>{{ $app->phone }}</td>
                                <td>{{ $app->email ?? '—' }}</td>
                                <td>
                                    @if($app->cv_path)
                                        <a href="{{ asset('storage/' . $app->cv_path) }}"
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> View
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($app->stage == 1)
                                        <span class="badge bg-secondary">Applied</span>
                                    @elseif($app->stage == 2)
                                        <span class="badge bg-info">Shortlisted</span>
                                    @elseif($app->stage == 3)
                                        <span class="badge bg-warning text-dark">Interview</span>
                                    @elseif($app->stage == 4)
                                        <span class="badge bg-success">Selected</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($app->interview_date)
                                        {{ $app->interview_date->format('d M Y, h:i A') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    {{-- Stage update --}}
                                    <form action="{{ route('recruitment.stage', $app->id) }}" method="POST"
                                          class="d-flex gap-1 align-items-center mb-1">
                                        @csrf @method('PATCH')
                                        <select name="stage" class="form-select form-select-sm" style="width:130px">
                                            <option value="1" {{ $app->stage == 1 ? 'selected' : '' }}>Applied</option>
                                            <option value="2" {{ $app->stage == 2 ? 'selected' : '' }}>Shortlisted</option>
                                            <option value="3" {{ $app->stage == 3 ? 'selected' : '' }}>Interview</option>
                                            <option value="4" {{ $app->stage == 4 ? 'selected' : '' }}>Selected</option>
                                            <option value="5" {{ $app->stage == 5 ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        <input type="datetime-local" name="interview_date" class="form-control form-control-sm"
                                               style="width:180px"
                                               value="{{ $app->interview_date ? $app->interview_date->format('Y-m-d\TH:i') : '' }}">
                                        <button class="btn btn-sm btn-primary"><i class="bi bi-check"></i></button>
                                    </form>

                                    {{-- Convert to employee --}}
                                    @if($app->stage == 4 && !$app->employee_id)
                                        <form action="{{ route('recruitment.convert', $app->id) }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Convert this applicant to employee?')">
                                            @csrf
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-person-check"></i> Convert to Employee
                                            </button>
                                        </form>
                                    @elseif($app->employee_id)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Converted
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No applicants yet. Add one above.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection