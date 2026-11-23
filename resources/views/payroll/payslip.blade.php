<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->employee->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; padding: 30px; }
        .payslip-box { max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 30px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="payslip-box">
    <div class="text-center mb-3">
        <h3 class="mb-1">Employee Management System</h3>
        <h5 class="text-muted">Payslip - {{ date('F', mktime(0,0,0,$payroll->month,1)) }} {{ $payroll->year }}</h5>
    </div>

    <table class="table table-bordered">
        <tr><th style="width:200px;">Employee Name</th><td>{{ $payroll->employee->name ?? '-' }}</td></tr>
        <tr><th>Employee Code</th><td>{{ $payroll->employee->employee_code ?? '-' }}</td></tr>
        <tr><th>Department</th><td>{{ $payroll->employee->department->department_name ?? '-' }}</td></tr>
        <tr><th>Designation</th><td>{{ $payroll->employee->designation->designation_name ?? '-' }}</td></tr>
        <tr><th>Joining Date</th><td>{{ $payroll->employee->joining_date ? $payroll->employee->joining_date->format('d M Y') : '-' }}</td></tr>
        <tr><th>Pay Month</th><td>{{ date('F', mktime(0,0,0,$payroll->month,1)) }} {{ $payroll->year }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($payroll->status) }}</td></tr>
    </table>

    <h5 class="mt-4">Earnings</h5>
    <table class="table table-sm table-bordered">
        <tr><th>Basic Salary</th><td class="text-end">{{ number_format($payroll->basic_salary, 2) }}</td></tr>
        @foreach($payroll->details->where('type', 'allowance') as $d)
            <tr><td>{{ $d->title }}</td><td class="text-end">{{ number_format($d->amount, 2) }}</td></tr>
        @endforeach
        <tr class="table-success"><th>Gross Salary</th><th class="text-end">{{ number_format($payroll->gross_salary, 2) }}</th></tr>
    </table>

    <h5 class="mt-4">Deductions</h5>
    <table class="table table-sm table-bordered">
        @forelse($payroll->details->where('type', 'deduction') as $d)
            <tr><td>{{ $d->title }}</td><td class="text-end">{{ number_format($d->amount, 2) }}</td></tr>
        @empty
            <tr><td colspan="2" class="text-muted">No deductions</td></tr>
        @endforelse
        <tr class="table-danger"><th>Total Deduction</th><th class="text-end">{{ number_format($payroll->total_deduction, 2) }}</th></tr>
    </table>

    <div class="alert alert-success text-center mt-4">
        <h5 class="mb-0">Net Salary: {{ number_format($payroll->net_salary, 2) }} ৳</h5>
    </div>

    <div class="text-end mt-5">
        <p class="mb-0">_______________________</p>
        <small>Authorized Signature</small>
    </div>
</div>

<div class="text-center mt-3 no-print">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Print / Save as PDF</button>
    <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-secondary">Back</a>
</div>

</body>
</html>