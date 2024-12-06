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

    <style>
        #register {
            background-color: green; /* Green background */
            color: white; /* White text */
            border: none; /* Remove border */
            padding: 10px 20px; /* Add some padding */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Change cursor on hover */
            font-size: 16px; /* Font size */
        }

        #register:hover {
            background-color: darkgreen; /* Darker green on hover */
        }
    </style>

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
                               <x-logo />
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

                                    <div class="form-group mb-3 text-start">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="phone_no"
                                            placeholder="Enter Your Phone Number" required>
                                    </div>

                                    <div class="form-group mb-3 text-start">
                                        <label>Organization</label>
                                        <input type="text" class="form-control" name="organisation"
                                            placeholder="Organisation" required>
                                    </div>

                                    <div class="">
                                        <button type="submit" class="" id="register" style="">Register
                                            attendance</button>
                                    </div>

                                </form>

                                    {{-- message --}}
                                    <div class="mt-3">
                                        <p class="text-muted text-danger" id="message"></p>
                                    </div>

                                    <div class="spinner-border" id="spinner" role="status" style="display:none;">
                                        <span class="sr-only">Loading...</span>
                                    </div>

                                    {{-- thank you for attendng section that will be displayed after the certificate is generated --}}
                                    <div class="mt-3" id="thank-you" style="display:none;">
                                        <p class="text-muted text-success">Thank you for attending the event</p>
                                    </div>
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

            // Show the spinner when submitting the form
            $('#registration-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Show the spinner and hide the form
                $('#spinner').show();
                $('#registration-form').hide();
                // Get the values
                const name = $('input[name="name"]').val();
                const email = $('input[name="email"]').val();
                const type = $('input[name="type"]').val();
                const id = $('input[name="id"]').val();
                const phone_no = $('input[name="phone_no"]').val();
                const organisation = $('input[name="organisation"]').val();

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
                        id: id,
                        phone_no: phone_no,
                        organisation: organisation
                    },
                    success: function(response) {
                        console.log(response.success);
                        $('#spinner').hide(); // Hide the spinner
                        if (response.success) {
                            $('#message').text(response.message).removeClass('text-danger')
                                .addClass('text-success');

                        } else {
                            $('#message').text(response.message).removeClass('text-success')
                                .addClass('text-danger');
                        }
                    },
                    error: function(error) {
                        $('#spinner').hide(); // Hide the spinner
                        $('#message').text(error.responseJSON.message).removeClass(
                            'text-success').addClass('text-danger');
                    }
                });
            });

            Echo.channel('certificate-generated')
                .listen('CertificateGenerated', (e) => {
                    console.log(e);

                    const downloadUrl = '{{ url('images/') }}' + '/' + e.download_link;
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = e.download_link;
                    document.body.appendChild(link);

                    // Show a Swal alert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Certificate generated successfully',
                        icon: 'success',
                        confirmButtonText: 'Ok',
                    })

                    // Handle promise resolution after alert is closed
                    link.click();
                    document.body.removeChild(link);

                    // Stop the spinner and show the thank you message
                    $('#spinner').hide();
                    $('#thank-you').show();

                    // Option 2: Disconnect immediately (if no further communication is expected)
                    Echo.leave('certificate-generated');

                    //stop listening to the event

                    // Option 1: Disconnect after a delay (if further communication is expected)


                });



        });
    </script>
</body>

</html>
