@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Promotion Details</h3>
        <div>
            <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <th style="width: 220px;">Employee</th>
                        <td>{{ $promotion->employee->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Promotion Type</th>
                        <td>{{ $promotion->promotion_type ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Promotion Date</th>
                        <td>{{ $promotion->promotion_date?->format('Y-m-d') ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Old Department</th>
                        <td>{{ $promotion->oldDepartment->department_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>New Department</th>
                        <td>{{ $promotion->newDepartment->department_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Old Designation</th>
                        <td>{{ $promotion->oldDesignation->designation_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>New Designation</th>
                        <td>{{ $promotion->newDesignation->designation_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Old Salary</th>
                        <td>{{ number_format((float) $promotion->old_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <th>New Salary</th>
                        <td>{{ number_format((float) $promotion->new_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Approved By</th>
                        <td>{{ $promotion->approved_by ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Remarks</th>
                        <td>{{ $promotion->remarks ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection