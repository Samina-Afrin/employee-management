@extends('layouts.master')

@section('title', 'Generate Payroll')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Generate Monthly Payroll (Monthly Basic Salary Based)</h4>
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

   
    <form action="{{ route('payroll.store') }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Salary Month *</label>
                <input type="month" name="salary_month" class="form-control" required
                       value="{{ old('salary_month', $month) }}">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="40">#</th>
                        <th>Employee</th>
                        <th width="140">Basic Salary (Monthly) *</th>
                        <th width="90">Working Days</th>
                        <th width="90">Tax %</th>
                        <th width="100">Loan</th>
                        <th width="100">Other</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $i => $emp)
                        @php $ex = $existing->get($emp->id); @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $emp->employee_code }}</strong><br><small>{{ $emp->name }}</small></td>
                            <td>
                                <input type="number" step="0.01" min="0"
                                       name="payroll[{{ $emp->id }}][per_day_salary]"
                                       class="form-control form-control-sm"
                                       value="{{ old("payroll.{$emp->id}.per_day_salary", ($ex->per_day_salary ?? 0) * 30) }}" required>
                            </td>
                            <td>
                                <input type="number" min="1"
                                       name="payroll[{{ $emp->id }}][working_days]"
                                       class="form-control form-control-sm"
                                       value="{{ old("payroll.{$emp->id}.working_days", $ex->working_days ?? 30) }}" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" max="100"
                                       name="payroll[{{ $emp->id }}][tax_percent]"
                                       class="form-control form-control-sm"
                                       value="{{ old("payroll.{$emp->id}.tax_percent", $ex->tax_percent ?? 5) }}">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0"
                                       name="payroll[{{ $emp->id }}][loan_deduction]"
                                       class="form-control form-control-sm"
                                       value="{{ old("payroll.{$emp->id}.loan_deduction", $ex->loan_deduction ?? 0) }}">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0"
                                       name="payroll[{{ $emp->id }}][other_allowance]"
                                       class="form-control form-control-sm"
                                       value="{{ old("payroll.{$emp->id}.other_allowance", $ex->other_allowance ?? 0) }}">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->count())
            <button class="btn btn-primary">Generate Payroll</button>
        @endif
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection