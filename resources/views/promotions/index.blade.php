@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Promotion History</h3>
        <a href="{{ route('promotions.create') }}" class="btn btn-primary">+ Add Promotion</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Search employee name">
        </div>
        <div class="col-md-4">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                @foreach(['Promotion','Increment','Transfer'] as $t)
                    <option value="{{ $t }}" @selected(request('type') == $t)>{{ $t }}</option>
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
                <th>Employee</th>
                <th>Date</th>
                <th>Type</th>
                <th>Old Designation</th>
                <th>New Designation</th>
                <th>Old Salary</th>
                <th>New Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($promotions as $p)
            <tr>
                <td>{{ $p->employee->name ?? '—' }}</td>
                <td>{{ $p->promotion_date?->format('Y-m-d') }}</td>
                <td>{{ $p->promotion_type }}</td>
                <td>{{ $p->oldDesignation->designation_name ?? '—' }}</td>
                <td>{{ $p->newDesignation->designation_name ?? '—' }}</td>
                <td>{{ number_format((float) $p->old_salary, 2) }}</td>
                <td>{{ number_format((float) $p->new_salary, 2) }}</td>
                <td>
                    <a href="{{ route('promotions.show', $p) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('promotions.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('promotions.destroy', $p) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this promotion?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center">No promotion records found.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $promotions->links() }}
</div>
@endsection