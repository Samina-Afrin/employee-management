
@extends('layouts.master')

@section('title', 'Add Loan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Add Loan</h4>
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

    <form method="POST" action="{{ route('loan.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee *</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">-- Select --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>
                            {{ $emp->employee_code }} - {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Loan Type *</label>
                <select name="loan_type" id="loan_type" class="form-select" required onchange="toggleLoanFields()">
                    <option value="long_term" @selected(old('loan_type') == 'long_term')>Long Term</option>
                    <option value="short_term" @selected(old('loan_type') == 'short_term')>Short Term</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Amount (৳) *</label>
                <input type="number" step="0.01" min="0.01" name="amount" id="amount"
                       class="form-control" value="{{ old('amount') }}" required
                       oninput="calculateLoan()">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Loan Date *</label>
                <input type="date" name="loan_date" class="form-control"
                       value="{{ old('loan_date', date('Y-m-d')) }}" required>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Purpose</label>
                <input type="text" name="purpose" class="form-control"
                       value="{{ old('purpose') }}"
                       placeholder="e.g. Home renovation, Medical emergency">
            </div>
        </div>

        {{-- Long Term Fields --}}
        <div id="long-term-fields">
            <h5>Long Term Loan Details</h5>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Installment Start Date *</label>
                    <input type="date" name="start_date" id="start_date"
                           class="form-control"
                           value="{{ old('start_date') }}"
                           onchange="calculateLoan()">
                    <small class="text-muted">Date when monthly installment deduction starts.</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Installment End Date</label>
                    <input type="date" name="end_date" id="end_date"
                           class="form-control"
                           value="{{ old('end_date') }}" readonly>
                    <small class="text-muted">Automatically calculated based on installments.</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Total Installments *</label>
                    <input type="number" min="1" name="total_installments"
                           id="total_installments" class="form-control"
                           value="{{ old('total_installments') }}"
                           placeholder="e.g. 4"
                           oninput="calculateLoan()">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">EMI Amount (৳)</label>
                    <input type="number" step="0.01" name="emi_amount"
                           id="emi_amount" class="form-control"
                           value="{{ old('emi_amount') }}"
                           placeholder="Auto calculated" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Monthly Installment</label>
                    <input type="text" id="emi_display" class="form-control"
                           placeholder="Automatically calculated" readonly>
                </div>

            </div>
        </div>

        {{-- Short Term Fields --}}
        <div id="short-term-fields" style="display:none;">
            <h5>Short Term Loan Details (1 Month)</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Month *</label>
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected(old('month', now()->month) == $m)>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Year *</label>
                    <input type="number" name="year" class="form-control"
                           value="{{ old('year', now()->year) }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" @selected(old('status') == 'pending')>Pending</option>
                    <option value="approved" @selected(old('status') == 'approved')>Approved</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Remarks</label>
                <input type="text" name="remarks" class="form-control"
                       value="{{ old('remarks') }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Loan</button>
        <a href="{{ route('loan.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleLoanFields() {
    var type = document.getElementById('loan_type').value;

    document.getElementById('long-term-fields').style.display =
        (type === 'long_term') ? 'block' : 'none';

    document.getElementById('short-term-fields').style.display =
        (type === 'short_term') ? 'block' : 'none';

    calculateLoan();
}

function calculateLoan() {
    var amount = parseFloat(document.getElementById('amount').value) || 0;
    var installments = parseInt(document.getElementById('total_installments').value) || 0;

    var startDate = document.getElementById('start_date').value;

    var emiField = document.getElementById('emi_amount');
    var emiDisplay = document.getElementById('emi_display');
    var endDateField = document.getElementById('end_date');

    // Auto EMI Calculation
    if (amount > 0 && installments > 0) {
        var emi = amount / installments;

        emiField.value = emi.toFixed(2);
        emiDisplay.value = '৳ ' + emi.toFixed(2);
    } else {
        emiField.value = '';
        emiDisplay.value = '';
    }

    // Auto End Date Calculation
    if (startDate && installments > 0) {
        var date = new Date(startDate + 'T00:00:00');

        var originalDay = date.getDate();

        // Add installment months
        date.setDate(1);
        date.setMonth(date.getMonth() + installments);

        // Handle months with fewer days
        var lastDay = new Date(
            date.getFullYear(),
            date.getMonth() + 1,
            0
        ).getDate();

        date.setDate(Math.min(originalDay, lastDay));

        var year = date.getFullYear();
        var month = String(date.getMonth()).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');

        endDateField.value = year + '-' + month + '-' + day;
    } else {
        endDateField.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleLoanFields();
    calculateLoan();
});
</script>
@endpush
