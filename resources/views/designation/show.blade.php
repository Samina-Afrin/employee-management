@extends('layouts.master')

@section('title', 'Designation Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Designation Details</h4>
        <a href="{{ route('designation.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th width="250">ID</th><td>{{ $designation->id }}</td></tr>
        <tr><th>Department</th><td>{{ $designation->department->department_name ?? '—' }}</td></tr>
        <tr><th>Designation Name</th><td>{{ $designation->designation_name }}</td></tr>
        <tr><th>Designation Code</th><td>{{ $designation->designation_code }}</td></tr>
        <tr><th>Description</th><td>{{ $designation->description }}</td></tr>
        <tr>
            <th>Status</th>
            <td>
                @if($designation->status == 1)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </td>
        </tr>
        <tr><th>Created At</th><td>{{ $designation->created_at?->format('d M Y, h:i A') }}</td></tr>
    </table>

    <a href="{{ route('designation.edit', $designation) }}" class="btn btn-warning">Edit</a>
</div>
@endsection