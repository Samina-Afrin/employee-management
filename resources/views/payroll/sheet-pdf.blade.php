<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Sheet</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 16px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #999; padding: 4px 6px; }
        table.data th { background: #f0f0f0; text-align: left; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .total-row th { background: #e0e0e0; }
        .footer { margin-top: 30px; font-size: 10px; }
        .footer table { width: 100%; }
        .footer td { text-align: center; border-top: 1px solid #333; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>EMPLOYEE MANAGEMENT SYSTEM</h2>
        <p>Final Payroll Sheet — {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Department</th>
                <th class="text-end">Per Day</th>
                <th class="text-center">Present</th>
                <th class="text-center">Absent</th>
                <th class="text-end">Basic</th>
                <th class="text-end">House</th>
                <th class="text-end">Medical</th>
                <th class="text-end">Transport</th>
                <th class="text-end">Other</th>
                <th class="text-end">Gross</th>
                <th class="text-end">Tax</th>
                <th class="text-end">Absence</th>
                <th class="text-end">Loan</th>
                <th class="text-end">Net</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->employee->employee_code ?? '—' }} - {{ $p->employee->name ?? '—' }}</td>
                <td>{{ $p->employee->department->department_name ?? '—' }}</td>
                <td class="text-end">{{ number_format($p->per_day_salary, 2) }}</td>
                <td class="text-center">{{ $p->present_days }}</td>
                <td class="text-center">{{ $p->absent_days }}</td>
                <td class="text-end">{{ number_format($p->basic_salary, 2) }}</td>
                <td class="text-end">{{ number_format($p->house_rent, 2) }}</td>
                <td class="text-end">{{ number_format($p->medical_allowance, 2) }}</td>
                <td class="text-end">{{ number_format($p->transport_allowance, 2) }}</td>
                <td class="text-end">{{ number_format($p->other_allowance, 2) }}</td>
                <td class="text-end">{{ number_format($p->gross_salary, 2) }}</td>
                <td class="text-end">{{ number_format($p->tax_deduction, 2) }}</td>
                <td class="text-end">{{ number_format($p->absence_deduction, 2) }}</td>
                <td class="text-end">{{ number_format($p->loan_deduction, 2) }}</td>
                <td class="text-end"><strong>{{ number_format($p->net_salary, 2) }}</strong></td>
                <td>{{ $p->payment_status == 2 ? 'Paid' : 'Pending' }}</td>
            </tr>
            @empty
            <tr><td colspan="17" class="text-center">No records.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="6" class="text-end">Total:</th>
                <th class="text-end">{{ number_format($records->sum('basic_salary'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('house_rent'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('medical_allowance'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('transport_allowance'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('other_allowance'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('gross_salary'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('tax_deduction'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('absence_deduction'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('loan_deduction'), 2) }}</th>
                <th class="text-end">{{ number_format($records->sum('net_salary'), 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>Prepared By</td>
                <td>HR Manager</td>
                <td>Finance Manager</td>
                <td>Authorized Signature</td>
            </tr>
        </table>
    </div>

</body>
</html>