@extends('layouts.master')

@section('title', 'Edit Payroll')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Payroll — {{ $payroll->employee->name ?? '—' }} ({{ \Carbon\Carbon::parse($payroll->salary_month)->format('F Y') }})</h4>
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payroll.update', $payroll) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Per Day Salary (৳) *</label>
                <input type="number" step="0.01" name="per_day_salary" class="form-control" required
                       value="{{ old('per_day_salary', $payroll->per_day_salary) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Working Days *</label>
                <input type="number" name="working_days" class="form-control" required
                       value="{{ old('working_days', $payroll->working_days) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Present Days (auto)</label>
                <input type="text" class="form-control" value="{{ $payroll->present_days }}" disabled>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Tax %</label>
                <input type="number" step="0.01" name="tax_percent" class="form-control"
                       value="{{ old('tax_percent', $payroll->tax_percent) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Loan Deduction (৳)</label>
                <input type="number" step="0.01" name="loan_deduction" class="form-control"
                       value="{{ old('loan_deduction', $payroll->loan_deduction) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Other Allowance (৳)</label>
                <input type="number" step="0.01" name="other_allowance" class="form-control"
                       value="{{ old('other_allowance', $payroll->other_allowance) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Payment Status *</label>
                <select name="payment_status" class="form-select" required>
                    <option value="1" @selected(old('payment_status', $payroll->payment_status)==1)>Pending</option>
                    <option value="2" @selected(old('payment_status', $payroll->payment_status)==2)>Paid</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Payment Date</label>
                <input type="date" name="payment_date" class="form-control"
                       value="{{ old('payment_date', $payroll->payment_date?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="alert alert-info">
            <strong>Current Values:</strong><br>
            Basic: ৳ {{ number_format($payroll->basic_salary, 2) }} |
            Gross: ৳ {{ number_format($payroll->gross_salary, 2) }} |
            Net: ৳ {{ number_format($payroll->net_salary, 2) }}
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection