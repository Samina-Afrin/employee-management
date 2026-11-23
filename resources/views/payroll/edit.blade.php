@extends('layouts.master')

@section('title', 'Edit Payroll')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Payroll - {{ $payroll->employee->name }}</h4>
        <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-secondary">Back</a>
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

    <form method="POST" action="{{ route('payroll.update', $payroll) }}">
        @csrf @method('PUT')

        <table class="table table-bordered">
            <tr><th style="width:200px;">Month</th><td>{{ date('F', mktime(0,0,0,$payroll->month,1)) }} {{ $payroll->year }}</td></tr>
            <tr><th>Basic Salary</th><td>{{ number_format($payroll->basic_salary, 2) }}</td></tr>
        </table>

        <h5>Allowances</h5>
        @php $allowances = $payroll->details->where('type', 'allowance'); @endphp
        @forelse($allowances as $i => $d)
            <div class="row mb-2">
                <div class="col-md-6"><input type="text" name="allowances[{{ $i }}][title]" value="{{ $d->title }}" class="form-control"></div>
                <div class="col-md-6"><input type="number" step="0.01" name="allowances[{{ $i }}][amount]" value="{{ $d->amount }}" class="form-control"></div>
            </div>
        @empty
            <div class="row mb-2">
                <div class="col-md-6"><input type="text" name="allowances[0][title]" placeholder="House Rent" class="form-control"></div>
                <div class="col-md-6"><input type="number" step="0.01" name="allowances[0][amount]" placeholder="0.00" class="form-control"></div>
            </div>
        @endforelse

        <h5 class="mt-3">Deductions</h5>
        @php $deductions = $payroll->details->where('type', 'deduction'); @endphp
        @forelse($deductions as $i => $d)
            <div class="row mb-2">
                <div class="col-md-6"><input type="text" name="deductions[{{ $i }}][title]" value="{{ $d->title }}" class="form-control"></div>
                <div class="col-md-6"><input type="number" step="0.01" name="deductions[{{ $i }}][amount]" value="{{ $d->amount }}" class="form-control"></div>
            </div>
        @empty
            <div class="row mb-2">
                <div class="col-md-6"><input type="text" name="deductions[0][title]" placeholder="Tax" class="form-control"></div>
                <div class="col-md-6"><input type="number" step="0.01" name="deductions[0][amount]" placeholder="0.00" class="form-control"></div>
            </div>
        @endforelse

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Payroll</button>
            <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection