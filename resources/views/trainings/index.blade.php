@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Training Records</h3>
        <a href="{{ route('trainings.create') }}" class="btn btn-primary">+ Add Training</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Search title / institute">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach(\App\Models\Training::statusLabels() as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee</th><th>Title</th><th>Type</th><th>Institute</th>
                <th>Country</th><th>Start</th><th>End</th><th>Cost (BDT)</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($trainings as $t)
            <tr>
                <td>{{ $t->employee->name ?? '—' }}</td>
                <td>{{ $t->training_title }}</td>
                <td>{{ $t->training_type }}</td>
                <td>{{ $t->institute_name }}</td>
                <td>{{ $t->country }}</td>
                <td>{{ $t->start_date?->format('Y-m-d') }}</td>
                <td>{{ $t->end_date?->format('Y-m-d') }}</td>
                <td>{{ number_format($t->cost, 2) }}</td>
                <td>{{ \App\Models\Training::statusLabels()[$t->status] }}</td>
                <td>
                    <a href="{{ route('trainings.show', $t) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('trainings.edit', $t) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('trainings.destroy', $t) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this training?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="10" class="text-center">No training records found.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $trainings->links() }}
</div>
@endsection