<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 fixed-start bg-white shadow" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary position-absolute end-0 top-0 d-xl-none" id="iconSidenav"></i>
        <div class="navbar-brand m-0 d-flex align-items-center">
            <i class="fas fa-store me-2 text-dark"></i>
            <span class="ms-1 fw-bold text-dark">SmartStore</span>
        </div>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <!-- Main -->
            <div class="sidebar-heading text-uppercase text-muted px-3 mt-4 mb-1 font-weight-bold">
                MAIN
            </div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                        <span class="nav-icon me-2">
                            <i class="fas fa-chart-pie text-danger"></i>
                        </span>
                        <span class="text-dark">Dashboard</span>
                    </a>
                </li>
            </ul>

            <!-- Management -->
            <div class="sidebar-heading text-uppercase text-muted px-3 mt-4 mb-1 font-weight-bold">
                MANAGEMENT
            </div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('products') ? 'active' : '' }}" href="/products">
                        <i class="fas fa-box-open me-2 text-warning"></i>
                        <span class="text-dark">Products</span>
                    </a>
                </li>
                <!-- Add POS Menu Item -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('pos') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                        <i class="fas fa-cash-register me-2 text-primary"></i>
                        <span class="text-dark">POS</span>
                    </a>
                </li>
                <!-- Existing menu items continue -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('sales') ? 'active' : '' }}" href="/sales">
                        <i class="fas fa-chart-line me-2 text-success"></i>
                        <span class="text-dark">Sales</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('expenses') ? 'active' : '' }}" href="/expenses">
                        <i class="fas fa-wallet me-2 text-danger"></i>
                        <span class="text-dark">Expenses</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('stock-alerts') ? 'active' : '' }}" href="/stock-alerts">
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                        <span class="text-dark">Stock Alerts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <span class="nav-icon me-2">
                            <i class="fas fa-chart-bar text-info"></i>
                        </span>
                        <span class="text-dark">Reports & Analytics</span>
                    </a>
                </li>
            </ul>

            <!-- Administration -->
            <div class="sidebar-heading text-uppercase text-muted px-3 mt-4 mb-1 font-weight-bold">
                ADMINISTRATION
            </div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('users') ? 'active' : '' }}" href="/users">
                        <i class="fas fa-users me-2 text-danger"></i>
                        <span class="text-dark">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" 
                       data-bs-toggle="collapse" href="#settingsCollapse" role="button" 
                       aria-expanded="{{ request()->is('settings*') ? 'true' : 'false' }}" 
                       aria-controls="settingsCollapse">
                        <i class="fas fa-cog me-2 text-secondary"></i>
                        <span class="text-dark">Settings</span>
                        <i class="fas fa-chevron-down ms-auto text-xs transition-transform {{ request()->is('settings*') ? 'rotate-180' : '' }}"></i>
                    </a>
                    <div class="collapse {{ request()->is('settings*') ? 'show' : '' }}" id="settingsCollapse">
                        <ul class="nav nav-sm flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('settings/company-profile') ? 'active' : '' }}" 
                                   href="{{ route('settings.company-profile') }}">
                                    <i class="fas fa-building me-2 text-primary"></i>
                                    <span class="fw-medium text-dark">Company Profile</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('settings/site') ? 'active' : '' }}" 
                                   href="{{ route('settings.site') }}">
                                    <i class="fas fa-globe me-2 text-info"></i>
                                    <span class="fw-medium text-dark">Site Settings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('settings/units') ? 'active' : '' }}" 
                                   href="{{ route('settings.units') }}">
                                    <i class="fas fa-ruler me-2 text-success"></i>
                                    <span class="fw-medium text-dark">Units List</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('settings/payment-types') ? 'active' : '' }}" 
                                   href="{{ route('settings.payment-types') }}">
                                    <i class="fas fa-credit-card me-2 text-warning"></i>
                                    <span class="fw-medium text-dark">Payment Types List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- New Profile and Logout buttons -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                        <i class="fas fa-user me-2 text-info"></i>
                        <span class="text-dark">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); confirmLogout();">
                        <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                        <span class="text-dark">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </ul>
    </div>

    <!-- User Profile Section -->
    <div class="position-absolute bottom-0 w-100 mb-3">
        <hr class="horizontal dark">
        <div class="px-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-sm rounded-circle bg-secondary me-2">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="flex-grow-1">
                    @auth
                        <h6 class="mb-0 text-sm">{{ Auth::user()->name }}</h6>
                        <p class="mb-0 text-xs text-muted">{{ Auth::user()->email }}</p>
                    @else
                        <h6 class="mb-0 text-sm">Guest</h6>
                        <p class="mb-0 text-xs text-muted">
                            <a href="{{ route('login') }}">Login</a>
                        </p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</aside>
