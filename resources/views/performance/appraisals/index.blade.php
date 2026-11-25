
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Employee Appraisals</h3>

        <a href="{{ route('performance.appraisals.create') }}"
           class="btn btn-primary">
            + Add Appraisal
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Cycle</th>
                        <th>Self Rating</th>
                        <th>Manager Rating</th>
                        <th>HR Rating</th>
                        <th>Final Rating</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($appraisals as $item)
                        <tr>
                            <td>{{ $item->employee->full_name ?? 'N/A' }}</td>
                            <td>{{ $item->cycle->name ?? 'N/A' }}</td>
                            <td>{{ $item->self_rating ?? '-' }}</td>
                            <td>{{ $item->manager_rating ?? '-' }}</td>
                            <td>{{ $item->hr_rating ?? '-' }}</td>
                            <td>{{ $item->final_rating ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('performance.appraisals.show', $item->id) }}"
                                   class="btn btn-sm btn-outline-info">View</a>

                                <a href="{{ route('performance.appraisals.edit', $item->id) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('performance.appraisals.destroy', $item->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this appraisal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No appraisal found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $appraisals->links() }}

        </div>
    </div>

</div>

@endsection
