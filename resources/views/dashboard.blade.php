@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<!-- Header with Live Clock -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle mb-0">Employee Management System Overview</p>
    </div>
    <div class="text-end">
        <div class="live-clock">
            <h4 class="mb-0 fw-bold text-primary" id="live-clock">--:--:--</h4>
            <small class="text-muted">
                <i class="bi bi-globe"></i> {{ $timezone }}
            </small>
        </div>
        <small class="text-muted d-block mt-1">
            <i class="bi bi-calendar3"></i> {{ $now->format('l, d F Y') }}
        </small>
    </div>
</div>

<!-- Time Zone Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                        <i class="bi bi-clock-history fs-4 text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted">Local Time ({{ $timezone }})</small>
                        <h5 class="mb-0" id="local-time">{{ $now->format('h:i:s A') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                        <i class="bi bi-geo-alt fs-4 text-success"></i>
                    </div>
                    <div>
                        <small class="text-muted">Dhaka Time (Asia/Dhaka)</small>
                        <h5 class="mb-0" id="dhaka-time">{{ $dhakaTime->format('h:i:s A') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                        <i class="bi bi-globe2 fs-4 text-info"></i>
                    </div>
                    <div>
                        <small class="text-muted">UTC Time</small>
                        <h5 class="mb-0" id="utc-time">{{ $utcTime->format('h:i:s A') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= Stat Cards Row ================= -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('employee.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Employees</p>
                            <h2 class="mb-0 text-primary">{{ $totalEmployees }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill fs-3 text-primary"></i>
                        </div>
                    </div>
                    <small class="text-muted"><i class="bi bi-arrow-right"></i> View all employees</small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('department.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Departments</p>
                            <h2 class="mb-0 text-success">{{ $totalDepartments }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-building fs-3 text-success"></i>
                        </div>
                    </div>
                    <small class="text-muted"><i class="bi bi-arrow-right"></i> View departments</small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('designation.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6f42c1 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Designations</p>
                            <h2 class="mb-0" style="color:#6f42c1;">{{ $totalDesignations }}</h2>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-badge-fill fs-3" style="color:#6f42c1;"></i>
                        </div>
                    </div>
                    <small class="text-muted"><i class="bi bi-arrow-right"></i> View designations</small>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ================= Row 2: Attendance, Leave, Payroll ================= -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('attendance.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-check-fill text-primary"></i> Attendance Today</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success mb-0">{{ $todayPresent }}</h4>
                            <small class="text-muted">Present</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger mb-0">{{ $todayAbsent }}</h4>
                            <small class="text-muted">Absent</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-0">{{ $todayLeave }}</h4>
                            <small class="text-muted">Leave</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('leave.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Leave Applications</p>
                            <h2 class="mb-0 text-warning">{{ $totalLeaves }}</h2>
                            <small class="text-danger">
                                <i class="bi bi-clock"></i> {{ $pendingLeaves }} pending
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-calendar2-minus-fill fs-3 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('payroll.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Monthly Payroll</p>
                            <h4 class="mb-0 text-success">৳ {{ number_format($monthlyPayroll, 2) }}</h4>
                            <small class="text-muted">{{ $totalPayrolls }} records</small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cash-stack fs-3 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ================= Row 3: Department-wise & Recent Data ================= -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-building"></i> Department-wise Employees</h6>
                <a href="{{ route('department.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($departmentWise as $dept)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong>{{ $dept->department_name }}</strong>
                            <span class="text-muted">{{ $dept->employees_count }} employees</span>
                        </div>
                        @php
                            $maxCount = $departmentWise->max('employees_count') ?: 1;
                            $percent = ($dept->employees_count / $maxCount) * 100;
                        @endphp
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No departments found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-people-fill"></i> Recent Employees</h6>
                <a href="{{ route('employee.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEmployees as $emp)
                                <tr>
                                    <td><span class="badge bg-primary">{{ $emp->employee_code }}</span></td>
                                    <td>{{ $emp->name }}</td>
                                    <td>{{ $emp->phone }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">No employees.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= Row 4: Recent Leaves & Payroll ================= -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-calendar2-minus-fill"></i> Recent Leave Applications</h6>
                <a href="{{ route('leave.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $lv)
                                <tr>
                                    <td>{{ $lv->employee->name ?? '—' }}</td>
                                    <td>{{ $lv->leave_type_label }}</td>
                                    <td>{{ $lv->total_days }}</td>
                                    <td>
                                        @if($lv->status == 1)
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($lv->status == 2)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No leaves.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-cash-stack"></i> Recent Payrolls</h6>
                <a href="{{ route('payroll.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Month</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayrolls as $p)
                                <tr>
                                    <td>{{ $p->employee->name ?? '—' }}</td>
                                    <td>{{ \Carbon\Carbon::create($p->year, $p->month, 1)->format('M Y') }}</td>
                                    <td>৳ {{ number_format($p->net_salary, 2) }}</td>
                                    <td>
                                        @if($p->status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($p->status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No payrolls.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= Last Updated Bar ================= -->
<div class="card border-0 shadow-sm">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    <i class="bi bi-arrow-clockwise"></i>
                    <strong>Last Updated:</strong>
                    <span id="last-updated">{{ $now->format('d M Y, h:i:s A') }}</span>
                </small>
            </div>
            <div>
                <small class="text-muted">
                    <i class="bi bi-hourglass-split"></i>
                    Auto-refresh in: <span id="refresh-countdown">60</span>s
                </small>
                <button onclick="location.reload()" class="btn btn-sm btn-outline-primary ms-2">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Now
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateClocks() {
        const now = new Date();

        const localTime = now.toLocaleTimeString('en-US', {
            hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit'
        });

        const dhakaOffset = 6 * 60;
        const utcOffset = now.getTimezoneOffset();
        const dhakaTime = new Date(now.getTime() + (dhakaOffset + utcOffset) * 60000);
        const dhakaStr = dhakaTime.toLocaleTimeString('en-US', {
            hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit'
        });

        const utcStr = now.toLocaleTimeString('en-US', {
            hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'UTC'
        });

        const liveClock = document.getElementById('live-clock');
        const localEl = document.getElementById('local-time');
        const dhakaEl = document.getElementById('dhaka-time');
        const utcEl = document.getElementById('utc-time');

        if (liveClock) liveClock.textContent = localTime;
        if (localEl) localEl.textContent = localTime;
        if (dhakaEl) dhakaEl.textContent = dhakaStr;
        if (utcEl) utcEl.textContent = utcStr;
    }

    updateClocks();
    setInterval(updateClocks, 1000);

    let countdown = 60;
    setInterval(function () {
        countdown--;
        const el = document.getElementById('refresh-countdown');
        if (el) {
            el.textContent = countdown;
            if (countdown <= 0) location.reload();
        }
    }, 1000);
</script>
@endpush