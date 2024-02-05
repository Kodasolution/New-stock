<!DOCTYPE html>



<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"

    data-assets-path="../../assets/" data-template="vertical-menu-template-no-customizer">



<head>

    <meta charset="utf-8" />

    <meta name="viewport"

        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />



    <title>Login | Paradise</title>



    <meta name="description" content="" />



    <!-- Favicon -->

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/faviconp.ico') }}" />



    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link

        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"

        rel="stylesheet" />



    <!-- Icons -->

    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />

    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />



    <!-- Core CSS -->

    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" />

    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" />

    <link rel="stylesheet" href="../../assets/css/demo.css" />



    <!-- Vendors CSS -->

    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />

    <!-- Vendor -->

    <link rel="stylesheet" href="../../assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />



    <!-- Page CSS -->

    <!-- Page -->

    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->

    <script src="../../assets/vendor/js/helpers.js"></script>



    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="../../assets/js/config.js"></script>

</head>



<body>

    <!-- Content -->



    <div class="container-xxl">

        <div class="authentication-wrapper authentication-basic container-p-y">

            <div class="authentication-inner py-4">

                <!-- Register -->

                @include('admin.includes.show-msg')

                <div class="card">

                    <div class="card-body">



                        <!-- Logo -->

                        <div class="app-brand justify-content-center">

                            <a href="{{ route('admin.login') }}" class="app-brand-link gap-2">

                                <span class="app-brand-logo demo">

                                    <svg width="26px" height="26px" viewBox="0 0 26 26" version="1.1"

                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

                                        <title>icon</title>

                                        <defs>

                                            <linearGradient x1="50%" y1="0%" x2="50%" y2="100%"

                                                id="linearGradient-1">

                                                <stop stop-color="#5A8DEE" offset="0%"></stop>

                                                <stop stop-color="#699AF9" offset="100%"></stop>

                                            </linearGradient>

                                            <linearGradient x1="0%" y1="0%" x2="100%" y2="100%"

                                                id="linearGradient-2">

                                                <stop stop-color="#FDAC41" offset="0%"></stop>

                                                <stop stop-color="#E38100" offset="100%"></stop>

                                            </linearGradient>

                                        </defs>

                                        <img src="{{ asset('assets/img/favicon/faviconp.ico') }}" style="margin: 7px auto; height:21px; " alt="">

                                    </svg>

                                </span>

                                <span class="app-brand-text demo h3 mb-0 fw-bold">PARADISE</span>

                            </a>

                        </div>

                        <!-- /Logo -->

                        <h4 class="mb-2">Welcome to Paradise! 👋</h4>

                        <p class="mb-4">Please sign-in to your account and start the adventure</p>

                        <form class="mb-3" action="{{ route('admin.login.store') }}" method="POST">

                            @csrf

                            <div class="mb-3">

                                <label for="email" class="form-label">Email or Username</label>

                                <input type="text" class="form-control @error('email') is-invalid @enderror"

                                    id="email" name="email" placeholder="Enter your email or username"

                                    autofocus />

                                @error('email')

                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>

                                @enderror

                            </div>

                            <div class="mb-3 form-password-toggle">

                                <div class="d-flex justify-content-between">

                                    <label class="form-label" for="password"> Password </label>

                                    {{--  <a href="auth-forgot-password-basic.html">

                                        <small>Forgot Password?</small>

                                    </a>  --}}

                                </div>

                                <div class="input-group input-group-merge">

                                    <input type="password" id="password"

                                        class="form-control @error('password') is-invalid @enderror" name="password"

                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"

                                        aria-describedby="password" />

                                        <span class="input-group-text cursor-pointer"></span>

                                    @error('password')

                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>

                                    @enderror

                                </div>

                            </div>

                            <div class="mb-3">

                                {{--  <div class="form-check">

                                    <input class="form-check-input" type="checkbox" id="remember-me" />

                                    <label class="form-check-label" for="remember-me"> Remember Me </label>

                                </div>  --}}

                            </div>

                            <div class="mb-3">

                                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>

                            </div>

                        </form>

                        {{--  <p class="text-center">

                            <span>New on our platform?</span>

                            <a href="#">

                                <span>Create an account</span>

                            </a>

                        </p>  --}}

                        {{--  <div class="divider my-4">

                            <div class="divider-text">or</div>

                        </div>  --}}



                        {{--  <div class="d-flex justify-content-center">

                            <a href="#" class="btn btn-icon btn-label-facebook me-3">

                                <i class="tf-icons bx bxl-facebook"></i>

                            </a>



                            <a href="#" class="btn btn-icon btn-label-google-plus me-3">

                                <i class="tf-icons bx bxl-google-plus"></i>

                            </a>



                            <a href="#" class="btn btn-icon btn-label-twitter">

                                <i class="tf-icons bx bxl-twitter"></i>

                            </a>

                        </div>  --}}

                    </div>

                </div>

                <!-- /Register -->

            </div>

        </div>

    </div>



    <!-- / Content -->



    <!-- Core JS -->

    <!-- build:js assets/vendor/js/core.js -->

    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>

    <script src="../../assets/vendor/libs/popper/popper.js"></script>

    <script src="../../assets/vendor/js/bootstrap.js"></script>

    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>



    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>



    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>

    <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>



    <script src="../../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->



    <!-- Vendors JS -->

    <script src="../../assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>

    <script src="../../assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>

    <script src="../../assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>



    <!-- Main JS -->

    <script src="../../assets/js/main.js"></script>



    <!-- Page JS -->

    <script src="../../assets/js/pages-auth.js"></script>

</body>



</html>

