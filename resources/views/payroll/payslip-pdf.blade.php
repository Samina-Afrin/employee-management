<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 2px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; }
        .summary-box { background: #e7f3ff; padding: 10px; margin-bottom: 20px; }
        .summary-box table { width: 100%; text-align: center; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data th, table.data td { border: 1px solid #999; padding: 6px 8px; }
        table.data th { background: #f0f0f0; text-align: left; }
        .text-end { text-align: right; }
        .total-row th { background: #e0e0e0; }
        .net-salary { background: #d4edda; padding: 12px; text-align: center; font-size: 16px; font-weight: bold; margin-top: 20px; }
        .words { text-align: center; font-style: italic; margin-top: 5px; font-size: 11px; }
        .footer { text-align: center; margin-top: 40px; font-size: 10px; color: #666; }
        .signature { margin-top: 60px; }
        .signature table { width: 100%; }
        .signature td { text-align: center; border-top: 1px solid #333; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>EMPLOYEE MANAGEMENT SYSTEM</h2>
        <p>Payslip — {{ \Carbon\Carbon::parse($payroll->salary_month)->format('F Y') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Employee Name:</strong> {{ $payroll->employee->name ?? '—' }}</td>
            <td><strong>Employee Code:</strong> {{ $payroll->employee->employee_code ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Department:</strong> {{ $payroll->employee->department->department_name ?? '—' }}</td>
            <td><strong>Designation:</strong> {{ $payroll->employee->designation->designation_name ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Salary Month:</strong> {{ \Carbon\Carbon::parse($payroll->salary_month)->format('F Y') }}</td>
            <td><strong>Payment Status:</strong> {{ $payroll->payment_status == 2 ? 'Paid' : 'Pending' }}</td>
        </tr>
    </table>

    <div class="summary-box">
        <table>
            <tr>
                <td><strong>Per Day Salary</strong><br>৳ {{ number_format($payroll->per_day_salary, 2) }}</td>
                <td><strong>Working Days</strong><br>{{ $payroll->working_days }}</td>
                <td><strong>Present Days</strong><br>{{ $payroll->present_days }}</td>
                <td><strong>Absent Days</strong><br>{{ $payroll->absent_days }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead><tr><th colspan="2">Earnings</th></tr></thead>
        <tbody>
            <tr><td>Basic ({{ $payroll->present_days }} × {{ number_format($payroll->per_day_salary, 2) }})</td><td class="text-end">{{ number_format($payroll->basic_salary, 2) }}</td></tr>
            <tr><td>House Rent (50%)</td><td class="text-end">{{ number_format($payroll->house_rent, 2) }}</td></tr>
            <tr><td>Medical (10%)</td><td class="text-end">{{ number_format($payroll->medical_allowance, 2) }}</td></tr>
            <tr><td>Transport (5%)</td><td class="text-end">{{ number_format($payroll->transport_allowance, 2) }}</td></tr>
            <tr><td>Other</td><td class="text-end">{{ number_format($payroll->other_allowance, 2) }}</td></tr>
            <tr class="total-row"><th>Gross Salary</th><th class="text-end">{{ number_format($payroll->gross_salary, 2) }}</th></tr>
        </tbody>
    </table>

    <table class="data">
        <thead><tr><th colspan="2">Deductions</th></tr></thead>
        <tbody>
            <tr><td>Tax ({{ $payroll->tax_percent }}%)</td><td class="text-end">{{ number_format($payroll->tax_deduction, 2) }}</td></tr>
            <tr><td>Absence ({{ $payroll->absent_days }} × {{ number_format($payroll->per_day_salary, 2) }})</td><td class="text-end">{{ number_format($payroll->absence_deduction, 2) }}</td></tr>
            <tr><td>Loan Deduction</td><td class="text-end">{{ number_format($payroll->loan_deduction, 2) }}</td></tr>
            <tr class="total-row"><th>Total Deductions</th><th class="text-end">{{ number_format($payroll->tax_deduction + $payroll->absence_deduction + $payroll->loan_deduction, 2) }}</th></tr>
        </tbody>
    </table>

    <div class="net-salary">NET SALARY: ৳ {{ number_format($payroll->net_salary, 2) }}</div>
    <div class="words">{{ $payroll->net_salary_words }}</div>

    <div class="signature">
        <table>
            <tr>
                <td>Employee Signature</td>
                <td>HR Manager</td>
                <td>Authorized Signature</td>
            </tr>
        </table>
    </div>

    <div class="footer">This is a computer-generated payslip. No signature is required.</div>

</body>
</html>