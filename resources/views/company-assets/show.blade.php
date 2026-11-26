@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Asset Details</h3>

    <table class="table table-bordered">
        <tr><th>Code</th><td>{{ $companyAsset->asset_code }}</td></tr>
        <tr><th>Name</th><td>{{ $companyAsset->asset_name }}</td></tr>
        <tr><th>Category</th><td>{{ $companyAsset->category }}</td></tr>
        <tr><th>Serial</th><td>{{ $companyAsset->serial_number }}</td></tr>
        <tr><th>Employee</th><td>{{ $companyAsset->employee->name ?? '—' }}</td></tr>
        <tr><th>Given Date</th><td>{{ $companyAsset->given_date?->format('Y-m-d') }}</td></tr>
        <tr><th>Return Date</th><td>{{ $companyAsset->return_date?->format('Y-m-d') }}</td></tr>
        <tr><th>Status</th><td>{{ \App\Models\CompanyAsset::statusLabels()[$companyAsset->status] }}</td></tr>
        <tr><th>Note</th><td>{{ $companyAsset->note }}</td></tr>
    </table>

    <a href="{{ route('company-assets.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('company-assets.edit', $companyAsset) }}" class="btn btn-warning">Edit</a>
</div>
@endsection