<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">


<head>

    <meta charset="utf-8" />
    <title>IPPU App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="impact outsourcing" name="description" />
    <meta content="impact outsourcing" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-32x32.png"
        sizes="32x32" />
    <link rel="icon" href="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-192x192.png"
        sizes="192x192" />
    <link rel="apple-touch-icon" href="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-180x180.png" />

    <!-- Layout config Js -->
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                                    <img src="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-192x192.png"
                                        alt="" width="10%" height="10%">
                                </div>
                                <h3 class="mt-4 fw-semibold">Record Attendence</h3>
                                <p class="text-muted mb-2 fs-14">{{ $data->name }}</p>
                                <h5 class="text-warning fw-semibold">{{ $data->points }} Points</h5>
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                        role="alert">
                                        <i class = "uil uil-exclamation-octagon me-2"></i>
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if ($data->end_date == 'Future')
                                    <form id="registration-form" method="POST">
                                        <input type="hidden" name="type" value="{{ $data->type }}">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <div class="form-group mb-3 text-start">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Your name" required>
                                        </div>

                                        <div class="form-group mb-3 text-start">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email"
                                                placeholder="Email Address" required>
                                        </div>

                                        <div class="">
                                            <button type="submit" class="btn btn-primary" id="register">Register
                                                attendance</button>
                                        </div>

                                        {{-- message --}}
                                        <div class="mt-3">
                                            <p class="text-muted" id="message"></p>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-muted mb-2 fs-14">Event has already passed!</p>
                                @endif
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

    <script>
        $(document).ready(function() {
            console.log('ready');
            // Listening for the event
            Echo.channel('certificate-generated')
                .listen('CertificateGenerated', (e) => {
                    console.log(e);
                    const downloadUrl = '{{ url('images/') }}' + '/' + e.download_link;
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = e.download_link;
                    document.body.appendChild(link);


                    //show a Swal alert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Certificate generated successfully',
                        icon: 'success',
                        confirmButtonText: 'Ok',

                        //acton to be download
                        onAfterClose: () => {
                            link.click();
                            document.body.removeChild(link);
                        }

                    }).then((result) => {
                        if (result.isConfirmed) {
                            link.click();
                            document.body.removeChild(link);
                        }
                    });
                });

            // Send a POST request on form submit
            $('#registration-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Get the values
                const name = $('input[name="name"]').val();
                const email = $('input[name="email"]').val();
                const type = $('input[name="type"]').val();
                const id = $('input[name="id"]').val();

                const url = '{{ url('direct_attendence_certificate') }}';

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        name: name,
                        email: email,
                        type: type,
                        id: id
                    },
                    success: function(response) {
                        console.log(response.success);
                        if (response.success) {
                            $('#message').text(response.message).removeClass('text-danger')
                                .addClass('text-success');
                        } else {
                            $('#message').text(response.message).removeClass('text-success')
                                .addClass('text-danger');
                        }
                    },
                    error: function(error) {
                        $('#message').text(error.responseJSON.message).removeClass(
                            'text-success').addClass('text-danger');
                    }
                });
            });

        });
    </script>
</body>

</html>
