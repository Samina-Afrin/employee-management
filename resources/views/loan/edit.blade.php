@extends('layouts.master')

@section('title', 'Edit Loan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Loan</h4>
        <a href="{{ route('loan.index') }}" class="btn btn-secondary">Back</a>
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

    <form method="POST" action="{{ route('loan.update', $loan) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee *</label>
                <select name="employee_id" class="form-select" required>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('employee_id', $loan->employee_id) == $emp->id)>
                            {{ $emp->employee_code }} - {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Loan Type *</label>
                <select name="loan_type" id="loan_type" class="form-select" required onchange="toggleLoanFields()">
                    <option value="long_term" @selected(old('loan_type', $loan->loan_type) == 'long_term')>Long Term</option>
                    <option value="short_term" @selected(old('loan_type', $loan->loan_type) == 'short_term')>Short Term</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Amount (৳) *</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $loan->amount) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Loan Date *</label>
                <input type="date" name="loan_date" id="loan_date" class="form-control" value="{{ old('loan_date', optional($loan->loan_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Purpose</label>
                <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $loan->purpose) }}">
            </div>
        </div>

        <div id="long-term-fields">
            <h5>Long Term Details</h5>
            <div class="row">
              
                <div class="col-md-4 mb-3">
                    <label class="form-label">Total Installments</label>
                    <input type="number" name="total_installments" class="form-control" value="{{ old('total_installments', $loan->total_installments) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">EMI Amount</label>
                    <input type="number" step="0.01" name="emi_amount" class="form-control" value="{{ old('emi_amount', $loan->emi_amount) }}">
                </div>
            </div>
        </div>

        <div id="short-term-fields" style="display: none;">
            <h5>Short Term Details</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Month</label>
                    <select name="month" id="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected(old('month', $loan->month) == $m)>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" id="year" class="form-control" value="{{ old('year', $loan->year) }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" @selected(old('status', $loan->status) == 'pending')>Pending</option>
                    <option value="approved" @selected(old('status', $loan->status) == 'approved')>Approved</option>
                    <option value="rejected" @selected(old('status', $loan->status) == 'rejected')>Rejected</option>
                    <option value="paid" @selected(old('status', $loan->status) == 'paid')>Paid</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Remarks</label>
                <input type="text" name="remarks" class="form-control" value="{{ old('remarks', $loan->remarks) }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Loan</button>
        <a href="{{ route('loan.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleLoanFields() {
    var type = document.getElementById('loan_type').value;
    document.getElementById('long-term-fields').style.display = (type === 'long_term') ? 'block' : 'none';
    document.getElementById('short-term-fields').style.display = (type === 'short_term') ? 'block' : 'none';
}

function syncMonthYearFromDate() {
    var loanDateInput = document.getElementById('loan_date');
    if (!loanDateInput || !loanDateInput.value) return;

    var date = new Date(loanDateInput.value);
    if (isNaN(date.getTime())) return;

    var month = String(date.getMonth()).padStart(2, '0');
    var year = date.getFullYear();

    var monthSelect = document.getElementById('month');
    var yearInput = document.getElementById('year');

    if (monthSelect) {
        monthSelect.value = parseInt(month, 10);
    }
    if (yearInput) {
        yearInput.value = year;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    toggleLoanFields();
    var loanDateInput = document.getElementById('loan_date');
    if (loanDateInput) {
        loanDateInput.addEventListener('change', syncMonthYearFromDate);
    }
});
</script>
@endpush