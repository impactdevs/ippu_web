<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>
    <meta charset="utf-8" />
    <title>IPPU App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-32x32.png" sizes="32x32"/>
    <link rel="icon" href="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-192x192.png" sizes="192x192"/>
    <link rel="apple-touch-icon" href="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-180x180.png"/>

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="card-body p-4 text-center">
                                <div class="text-center">
                                    <img src="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-192x192.png" alt="" width="10%" height="10%">
                                </div>
                                @if(session('success'))
                                <h3 class="mt-4 fw-semibold">Thank You for Registering!</h3>
                                <p class="text-muted mb-2 fs-14">Your attendance has been successfully recorded.</p>
                                @endif
                                
                                @if(session('error'))
                                    <div class="alert alert-danger mt-3 alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                        <i class="uil uil-exclamation-octagon me-2"></i>
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <!-- Back Home Button -->
                                <a href="{{ url('login') }}" class="btn btn-primary mt-3">Back Home</a>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
</body>
</html>
