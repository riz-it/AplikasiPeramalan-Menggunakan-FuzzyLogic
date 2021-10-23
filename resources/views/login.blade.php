<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Peramalan</title>
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Fonts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>

    <style>
        .slide-in-bottom {
            -webkit-animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) both;
            animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) both
        }

        .slide-in-bottom-h1 {
            -webkit-animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .5s both;
            animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .5s both
        }

        .slide-in-bottom-subtitle {
            -webkit-animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .75s both;
            animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .75s both
        }

        .fade-in {
            -webkit-animation: fade-in 1.2s cubic-bezier(.39, .575, .565, 1.000) 1s both;
            animation: fade-in 1.2s cubic-bezier(.39, .575, .565, 1.000) 1s both
        }

        .bounce-top-icons {
            -webkit-animation: bounce-top .9s 1s both;
            animation: bounce-top .9s 1s both
        }

        @-webkit-keyframes slide-in-bottom {
            0% {
                -webkit-transform: translateY(1000px);
                transform: translateY(1000px);
                opacity: 0
            }

            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
                opacity: 1
            }
        }

        @keyframes slide-in-bottom {
            0% {
                -webkit-transform: translateY(1000px);
                transform: translateY(1000px);
                opacity: 0
            }

            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
                opacity: 1
            }
        }

        @-webkit-keyframes bounce-top {
            0% {
                -webkit-transform: translateY(-45px);
                transform: translateY(-45px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in;
                opacity: 1
            }

            24% {
                opacity: 1
            }

            40% {
                -webkit-transform: translateY(-24px);
                transform: translateY(-24px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            65% {
                -webkit-transform: translateY(-12px);
                transform: translateY(-12px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            82% {
                -webkit-transform: translateY(-6px);
                transform: translateY(-6px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            93% {
                -webkit-transform: translateY(-4px);
                transform: translateY(-4px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            25%,
            55%,
            75%,
            87% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out
            }

            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
                opacity: 1
            }
        }

        @keyframes bounce-top {
            0% {
                -webkit-transform: translateY(-45px);
                transform: translateY(-45px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in;
                opacity: 1
            }

            24% {
                opacity: 1
            }

            40% {
                -webkit-transform: translateY(-24px);
                transform: translateY(-24px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            65% {
                -webkit-transform: translateY(-12px);
                transform: translateY(-12px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            82% {
                -webkit-transform: translateY(-6px);
                transform: translateY(-6px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            93% {
                -webkit-transform: translateY(-4px);
                transform: translateY(-4px);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in
            }

            25%,
            55%,
            75%,
            87% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out
            }

            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
                opacity: 1
            }
        }

        @-webkit-keyframes fade-in {
            0% {
                opacity: 0
            }

            100% {
                opacity: 1
            }
        }

        @keyframes fade-in {
            0% {
                opacity: 0
            }

            100% {
                opacity: 1
            }
        }

    </style>

</head>


<body class=" bg-gradient-primary">

    <div class="container fade-in">

        <!-- Outer Row -->
        <div class="row justify-content-center">


            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        @if (Session::get('fail'))
                            <div class="alert alert-danger d-flex justify-content-center">
                                {{ Session::get('fail') }}
                            </div>
                        @endif
                        @if (Session::get('success'))
                            <div class="alert alert-success d-flex justify-content-center">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        <!-- Nested Row within Card Body -->
                        <div class="row bounce-top-icons">
                            <div class="py-4 col-lg-6 d-none d-lg-block ">
                                <img src="{{ asset('file_data/icon.png') }}">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="{{ url('verified_login') }}" method="POST"
                                        id="logForm">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputEmail" required name="username" autocomplete="off" aria-describedby="emailHelp"
                                                placeholder="Enter Username...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password"
                                                class="form-control form-control-user" required autocomplete="off" id="exampleInputPassword"
                                                placeholder="Password">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                            </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    {{-- <script src="{{ asset('assets/js/demo/datatables-demo.js') }}"></script> --}}
    @stack('script')
</body>


</html>
