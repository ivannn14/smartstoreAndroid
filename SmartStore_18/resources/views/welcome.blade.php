<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStore - Inventory & Sales Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/material-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome/index.css') }}">
</head>
<body class="bg-gray-100">
    <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent">
        <div class="container">
            <a class="navbar-brand text-white" href="/">
                <i class="fas fa-store me-2"></i>
                SmartStore
            </a>
        </div>
    </nav>

    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-6 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1 text-center">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Welcome to SmartStore</h4>
                                    <p class="text-white text-sm mb-0">Effortlessly manage inventory, track sales, and optimize your store's performance</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card feature-card">
                                            <div class="card-body p-3 text-center">
                                                <i class="fas fa-box fa-3x text-primary mb-3 feature-icon"></i>
                                                <h5 class="mb-3">Inventory Management</h5>
                                                <p class="mb-0">Track your stock levels in real-time</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card feature-card">
                                            <div class="card-body p-3 text-center">
                                                <i class="fas fa-chart-line fa-3x text-primary mb-3 feature-icon"></i>
                                                <h5 class="mb-3">Sales Analytics</h5>
                                                <p class="mb-0">Monitor your business performance</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <a href="{{ route('login') }}" class="btn bg-gradient-primary w-45 mx-1 mb-0 animated-btn">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </a>
                                    <a href="{{ route('register') }}" class="btn bg-gradient-secondary w-45 mx-1 mb-0 animated-btn">
                                        <i class="fas fa-user-plus me-2"></i>Register
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer position-absolute bottom-2 py-2 w-100">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-12 col-md-6 text-center text-white">
                            © <span class="copyright-year"></span>, SmartStore. All rights reserved.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <script src="{{ asset('js/material-dashboard.js') }}"></script>
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/welcome/index.js') }}"></script>
</body>
</html>
