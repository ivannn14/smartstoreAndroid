<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStore Dashboard</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Theme Color -->
    <meta name="theme-color" content="#ffffff" class="theme-color">

    <!-- Material Dashboard 3 CSS -->
    <link rel="stylesheet" href="{{ asset('css/material-dashboard.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nucleo-svg.css') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Sidebar CSS -->
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

    <!-- Dark Mode CSS -->
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    @stack('styles') <!-- Additional styles from child templates -->

    <style>
        .transition-transform {
            transition: transform 0.3s ease;
        }
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar') 
        
        <div class="main-content position-relative max-height-vh-100 h-100">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Page Content -->
            <div class="container-fluid py-4">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('js/material-dashboard.min.js') }}"></script>
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSRF Token for AJAX Requests -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Set up CSRF token for all AJAX requests
            window.fetchWithCSRF = (url, options = {}) => {
                options.headers = {
                    ...options.headers,
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                };
                return fetch(url, options);
            };

            if (navigator.platform.indexOf('Win') > -1 && document.querySelector('#sidenav-main')) {
                new PerfectScrollbar('#sidenav-main');
            }

            // Chevron icon toggle
            const settingsToggle = document.querySelector('[data-bs-toggle="collapse"]');
            const chevronIcon = settingsToggle?.querySelector('.fa-chevron-down');
            const settingsCollapse = document.getElementById('settingsCollapse');

            if (settingsToggle && chevronIcon && settingsCollapse) {
                settingsToggle.addEventListener('click', function () {
                    chevronIcon.classList.toggle('rotate-180');
                });

                settingsCollapse.addEventListener('hidden.bs.collapse', function () {
                    chevronIcon.classList.remove('rotate-180');
                });

                settingsCollapse.addEventListener('shown.bs.collapse', function () {
                    chevronIcon.classList.add('rotate-180');
                });
            }
        });
    </script>

    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const html = document.documentElement;
            const icon = darkModeToggle?.querySelector('i');
            
            // Check system preference
            if (!localStorage.getItem('theme') && 
                window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.setAttribute('data-theme', 'dark');
                if (icon) icon.className = 'fas fa-sun';
            }

            // Check saved preference
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                html.setAttribute('data-theme', savedTheme);
                if (icon) icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }

            // Toggle dark mode
            darkModeToggle?.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                if (icon) icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });
        });
    </script>

    <!-- DataTables Scripts -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <!-- Confirm Logout -->
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of the system!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    @stack('scripts') <!-- Additional scripts from child templates -->
</body>
</html>

<!-- POS Navigation Link -->
<a href="{{ route('pos.index') }}" class="nav-link">
    <i class="fas fa-cash-register"></i>
    <span>POS</span>
</a>
