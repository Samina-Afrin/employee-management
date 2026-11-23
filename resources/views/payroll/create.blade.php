@extends('layouts.master')

@section('title', 'Generate Payroll')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Generate Payroll</h4>
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
                <input type="number" name="year" value="{{ old('year', now()->year) }}" class="form-control" required>
            </div>
        </div>

        <div class="alert alert-info">
            <p class="mb-1"><strong>Total Active Employees:</strong> {{ $employees->count() }}</p>
            <p class="mb-0"><em>Each employee's monthly salary will be picked from their profile.</em></p>
        </div>

        <h5>Default Allowances (Optional)</h5>
        <p class="text-muted small">Ei allowance gula sob employee er payroll e add hobe.</p>
        <div id="allowance-wrapper">
            <div class="row mb-2 allowance-row">
                <div class="col-md-6">
                    <input type="text" name="default_allowances[0][title]" class="form-control" placeholder="e.g. House Rent">
                </div>
                <div class="col-md-5">
                    <input type="number" step="0.01" name="default_allowances[0][amount]" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.allowance-row').remove()">×</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addAllowanceRow()">+ Add Allowance</button>

        <h5>Default Deductions (Optional)</h5>
        <p class="text-muted small">Ei deduction gula sob employee er payroll e add hobe.</p>
        <div id="deduction-wrapper">
            <div class="row mb-2 deduction-row">
                <div class="col-md-6">
                    <input type="text" name="default_deductions[0][title]" class="form-control" placeholder="e.g. Tax">
                </div>
                <div class="col-md-5">
                    <input type="number" step="0.01" name="default_deductions[0][amount]" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.deduction-row').remove()">×</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addDeductionRow()">+ Add Deduction</button>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Generate Payroll</button>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function addAllowanceRow() {
        const wrapper = document.getElementById('allowance-wrapper');
        const count = wrapper.querySelectorAll('.allowance-row').length;
        const row = document.createElement('div');
        row.className = 'row mb-2 allowance-row';
        row.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="default_allowances[${count}][title]" class="form-control" placeholder="e.g. House Rent">
            </div>
            <div class="col-md-5">
                <input type="number" step="0.01" name="default_allowances[${count}][amount]" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.allowance-row').remove()">×</button>
            </div>`;
        wrapper.appendChild(row);
    }

    function addDeductionRow() {
        const wrapper = document.getElementById('deduction-wrapper');
        const count = wrapper.querySelectorAll('.deduction-row').length;
        const row = document.createElement('div');
        row.className = 'row mb-2 deduction-row';
        row.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="default_deductions[${count}][title]" class="form-control" placeholder="e.g. Tax">
            </div>
            <div class="col-md-5">
                <input type="number" step="0.01" name="default_deductions[${count}][amount]" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.deduction-row').remove()">×</button>
            </div>`;
        wrapper.appendChild(row);
    }
</script>
@endpush