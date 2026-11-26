@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Company Assets</h3>
        <a href="{{ route('company-assets.create') }}" class="btn btn-primary">+ Add Asset</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Search code / name / serial">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach(\App\Models\CompanyAsset::statusLabels() as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Category</option>
                @foreach(['Laptop','Phone','Other'] as $c)
                    <option value="{{ $c }}" @selected(request('category') == $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Code</th><th>Name</th><th>Category</th><th>Serial</th>
                <th>Employee</th><th>Given</th><th>Return</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($assets as $asset)
            <tr>
                <td>{{ $asset->asset_code }}</td>
                <td>{{ $asset->asset_name }}</td>
                <td>{{ $asset->category }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->employee->name ?? '—' }}</td>
                <td>{{ $asset->given_date?->format('Y-m-d') }}</td>
                <td>{{ $asset->return_date?->format('Y-m-d') }}</td>
                <td>{{ \App\Models\CompanyAsset::statusLabels()[$asset->status] }}</td>
                <td>
                    <a href="{{ route('company-assets.show', $asset) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('company-assets.edit', $asset) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('company-assets.destroy', $asset) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this asset?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" class="text-center">No assets found.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $assets->links() }}
</div>
@endsection