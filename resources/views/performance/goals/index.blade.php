
@extends('layouts.master')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Employee KPI & Goals</h3>

        <a href="{{ route('performance.goals.create') }}"
           class="btn btn-primary">+ Add Goal</a>
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
                        <th>Goal</th>
                        <th>KPI</th>
                        <th>Progress</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($goals as $goal)
                        <tr>
                            <td>{{ $goal->employee->full_name ?? 'N/A' }}</td>
                            <td>{{ $goal->title }}</td>
                            <td>{{ $goal->kpi ?? '-' }}</td>
                            <td style="min-width:140px">
                                <div class="progress" style="height:8px">
                                    <div class="progress-bar bg-primary"
                                         style="width:{{ $goal->progress }}%">
                                    </div>
                                </div>
                                <small>{{ $goal->progress }}%</small>
                            </td>
                            <td>{{ $goal->due_date }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $goal->status)) }}</td>
                            <td>
                                <a href="{{ route('performance.goals.edit', $goal->id) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('performance.goals.destroy', $goal->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this goal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                No goals found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $goals->links() }}

        </div>
    </div>

</div>

@endsection
