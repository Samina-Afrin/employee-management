@extends('layouts.master')

@section('title', 'Generate Payroll')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Generate Payroll</h4>
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('payroll.generate') }}">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Month *</label>
                <select name="month" class="form-select" required>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$m,1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Year *</label>
                <input type="number" name="year" value="{{ now()->year }}" class="form-control" required>
            </div>
        </div>

        <h5>Default Allowances</h5>
        <div id="allowance-wrapper">
            <div class="row mb-2 allowance-row">
                <div class="col-md-6"><input type="text" name="default_allowances[0][title]" class="form-control" placeholder="House Rent"></div>
                <div class="col-md-5"><input type="number" step="0.01" name="default_allowances[0][amount]" class="form-control" placeholder="0.00"></div>
                <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="removeAllowance(this)">×</button></div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addAllowance()">+ Add Allowance</button>

        <h5>Default Deductions</h5>
        <div id="deduction-wrapper">
            <div class="row mb-2 deduction-row">
                <div class="col-md-6"><input type="text" name="default_deductions[0][title]" class="form-control" placeholder="Tax"></div>
                <div class="col-md-5"><input type="number" step="0.01" name="default_deductions[0][amount]" class="form-control" placeholder="0.00"></div>
                <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="removeDeduction(this)">×</button></div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addDeduction()">+ Add Deduction</button>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Generate Payroll</button>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

{{-- JavaScript EKHANE DIRECT --}}
<script>
function addAllowance() {
    var w = document.getElementById('allowance-wrapper');
    var i = w.querySelectorAll('.allowance-row').length;
    var r = document.createElement('div');
    r.className = 'row mb-2 allowance-row';
    r.innerHTML = '<div class="col-md-6"><input type="text" name="default_allowances['+i+'][title]" class="form-control" placeholder="House Rent"></div>' +
        '<div class="col-md-5"><input type="number" step="0.01" name="default_allowances['+i+'][amount]" class="form-control" placeholder="0.00"></div>' +
        '<div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="removeAllowance(this)">×</button></div>';
    w.appendChild(r);
}
function addDeduction() {
    var w = document.getElementById('deduction-wrapper');
    var i = w.querySelectorAll('.deduction-row').length;
    var r = document.createElement('div');
    r.className = 'row mb-2 deduction-row';
    r.innerHTML = '<div class="col-md-6"><input type="text" name="default_deductions['+i+'][title]" class="form-control" placeholder="Tax"></div>' +
        '<div class="col-md-5"><input type="number" step="0.01" name="default_deductions['+i+'][amount]" class="form-control" placeholder="0.00"></div>' +
        '<div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="removeDeduction(this)">×</button></div>';
    w.appendChild(r);
}
function removeAllowance(btn) { btn.closest('.allowance-row').remove(); }
function removeDeduction(btn) { btn.closest('.deduction-row').remove(); }
</script>
@endsection