<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'employee management System')</title>

    <meta name="description" content="employee management System">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- ApexCharts CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/apexcharts/apexcharts.css') }}">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">

    <!-- Spark Admin Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    @stack('styles')
</head>

<body>

    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar-wrapper" id="sidebar">

        <!-- Brand -->
        <a href="{{ url('/') }}" class="sidebar-brand">
            <i class="bi bi-asterisk"></i>
            <span>employee management</span>
        </a>

        <div class="flex-grow-1 overflow-y-auto">

            <!-- Main Menu -->
            <div class="sidebar-menu-section">

                <div class="sidebar-menu-title">Main Menu</div>

                <ul class="sidebar-menu-list">

                    <li class="sidebar-menu-item">
                        <a href="{{ url('/') }}"
                           class="sidebar-menu-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                </ul>
            </div>

            <!-- HR Management -->
            <div class="sidebar-menu-section">

                <div class="sidebar-menu-title">HR Management</div>

                <ul class="sidebar-menu-list">

                    <li class="sidebar-menu-item">
                        <a href="{{ route('employee.index') }}"
                           class="sidebar-menu-link {{ request()->is('employee*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Employee</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item">
                        <a href="{{ route('department.index') }}"
                           class="sidebar-menu-link {{ request()->is('department*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>
                            <span>Department</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item">
                        <a href="{{ route('designation.index') }}"
                           class="sidebar-menu-link {{ request()->is('designation*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Designation</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item">
                        <a href="{{ url('/attendance') }}"
                           class="sidebar-menu-link {{ request()->is('attendance*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ url('/leave') }}"
                           class="sidebar-menu-link {{ request()->is('leave*') ? 'active' : '' }}">
                            <i class="bi bi-calendar2-minus-fill"></i>
                            <span>Leave</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item">
                        <a href="{{ url('/payroll & salary') }}"
                           class="sidebar-menu-link {{ request()->is('payroll & salary*') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack"></i>
                            <span>Payroll & Salary</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item">
                        <a href="{{ url('/recruitment') }}"
                           class="sidebar-menu-link {{ request()->is('recruitment*') ? 'active' : '' }}">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Recruitment</span>
                        </a>
                    </li>

                </ul>
            </div>

        </div>

        <!-- Sidebar Profile -->
        <div class="sidebar-profile">

            <img
                src="{{ asset('assets/images/avatar.png') }}"
                alt="Administrator"
                class="sidebar-profile-img"
            >

            <div class="sidebar-profile-info">
                <div class="sidebar-profile-name">Administrator</div>
                <div class="sidebar-profile-email">admin@email.com</div>
            </div>

        </div>

    </div>

    <!-- ================= END SIDEBAR ================= -->


    <!-- ================= MAIN WRAPPER ================= -->

    <div class="main-wrapper">

        <!-- ================= TOP NAVBAR ================= -->

        <header class="navbar-custom">

            <div class="navbar-left">

                <!-- Desktop Sidebar Toggle -->
                <button
                    class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
                    id="desktop-sidebar-toggle"
                    aria-label="Minimize Sidebar"
                >
                    <i class="bi bi-chevron-bar-left"></i>
                </button>

                <!-- Mobile Sidebar Toggle -->
                <button
                    class="sidebar-toggle-btn me-2"
                    id="sidebar-toggle"
                    aria-label="Toggle Navigation"
                >
                    <i class="bi bi-list"></i>
                </button>

                <!-- Quick Actions -->
                <div class="dropdown ms-2">

                    <button
                        class="btn-quick-action dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        id="quick-actions-dropdown"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Create</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-quick-action"
                        aria-labelledby="quick-actions-dropdown">

                        <li class="dropdown-header">Quick Actions</li>

                        <li>
                            <a class="dropdown-item" href="{{ route('employee.create') }}">
                                <i class="bi bi-person-plus"></i>
                                Add Employee
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ url('/attendance/create') }}">
                                <i class="bi bi-calendar-plus"></i>
                                Add Attendance
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ url('/salary/create') }}">
                                <i class="bi bi-cash"></i>
                                Add Salary
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('department.create') }}">
                                <i class="bi bi-building"></i>
                                Add Department
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('designation.create') }}">
                                <i class="bi bi-person-badge-fill"></i>
                                Add Designation
                            </a>
                        </li>

                    </ul>
                </div>

            </div>

            <!-- Search -->
            <div class="navbar-search-wrapper">

                <input
                    type="text"
                    class="navbar-search-input"
                    placeholder="Search workers, attendance, salary..."
                    id="main-search"
                >

                <button class="navbar-search-btn" type="button" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>

            </div>

            <!-- Navbar Right -->
            <div class="navbar-actions">

                <!-- Fullscreen -->
                <button
                    class="navbar-action-btn me-1"
                    aria-label="Toggle Fullscreen"
                    id="btn-fullscreen"
                    type="button"
                >
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                <!-- Profile -->
                <div class="dropdown ms-2">

                    <button
                        class="navbar-profile-btn dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        id="profile-dropdown"
                    >
                        <img
                            src="{{ asset('assets/images/avatar.png') }}"
                            alt="Profile Image"
                            class="navbar-profile-img"
                        >

                        <span class="navbar-profile-name d-none d-md-inline">
                            Administrator
                        </span>

                        <i class="bi bi-chevron-down navbar-profile-caret"></i>
                    </button>

                    <ul
                        class="dropdown-menu dropdown-menu-end dropdown-menu-profile"
                        aria-labelledby="profile-dropdown"
                    >

                        <li class="dropdown-header">Welcome Administrator!</li>

                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person"></i>
                                My Account
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear"></i>
                                Settings
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i>
                                    {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>

                </div>

            </div>

        </header>

        <!-- ================= END NAVBAR ================= -->


        <!-- ================= PAGE CONTENT ================= -->

        @yield('content')

        <!-- ================= END PAGE CONTENT ================= -->


        <!-- ================= FOOTER ================= -->

        <footer class="footer-custom">

            <div class="footer-left">

                <span class="footer-logo">
                    <i class="bi bi-asterisk"></i>
                    Worker Management
                </span>

                <span class="footer-separator">|</span>

                <span class="footer-copy">
                    &copy; {{ date('Y') }} Worker Management System.
                    All rights reserved.
                </span>

            </div>

            <div class="footer-right">

                <ul class="footer-links">
                    <li>
                        <a href="{{ url('/dashboard') }}" class="footer-link">
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/workers') }}" class="footer-link">
                            Workers
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/report') }}" class="footer-link">
                            Reports
                        </a>
                    </li>
                </ul>

            </div>

        </footer>

        <!-- ================= END FOOTER ================= -->

    </div>

    <!-- ================= END MAIN WRAPPER ================= -->


    <!-- ================= JAVASCRIPT ================= -->

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    @stack('scripts')

</body>
</html>