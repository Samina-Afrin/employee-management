@extends('layouts.master')

@section('title', 'Notices')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Notice List</h4>
        <a href="{{ route('notice.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Add Notice
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Search notice title"
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="1" @selected(request('status')=='1')>Active</option>
                <option value="0" @selected(request('status')=='0')>Hidden</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('notice.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Notice Date</th>
                    <th>Expire Date</th>
                    <th>Posted By</th>
                    <th>Status</th>
                    <th width="220">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notices as $i => $n)
                <tr>
                    <td>{{ $notices->firstItem() + $i }}</td>
                    <td>{{ $n->title }}</td>
                    <td>{{ $n->notice_date?->format('d M Y') }}</td>
                    <td>{{ $n->expire_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $n->poster->name ?? '—' }}</td>
                    <td>
                        @if($n->status == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('notice.show', $n) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('notice.edit', $n) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('notice.destroy', $n) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this notice?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No notices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $notices->withQueryString()->links() }}
</div>
@endsection