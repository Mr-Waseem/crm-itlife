<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon"
        href="{{ URL::asset('dashboard/images/favicon.ico') }}">

    <title>{{ SettingsFacade::data()->system_name }} - Log in </title>

    <!-- Bootstrap 4.1-->
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Bootstrap extend-->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/bootstrap-extend.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/master_style.css') }}">

    <!-- SoftMaterial admin skins -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/skins/_all-skins.css') }}">
</head>

<body class="hold-transition bg-img" style="background-image: url({{ URL::asset('dashboard/images/gallery/full/6.jpg') }})" data-overlay="4">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-lg-5 col-md-8 col-12">
                <div class="content-top-agile">
                    <h2>Get started with Us</h2>
                    <p class="text-white">Sign in to start your session</p>
                </div>
                <div class="p-40 mt-10 bg-white content-bottom">
                    <form
                        action="{{ url('/login') }}"
                        method="post">
                        <!-- @csrf -->
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger border-danger"><i
                                            class="ti-user"></i></span>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required autofocus>
                            </div>
                            @error('email')
                            <p class="help-block">
                                <strong style="color:red;">{{ $message }}</strong>
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger border-danger"><i
                                            class="ti-lock"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control" autocomplete="off"
                                        placeholder="Password" aria-label="Password" required>
                            </div>
                            @error('password')
                            <span class="help-block">
                                <strong style="color:red;">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="basic_checkbox_1">
                                    <label for="basic_checkbox_1">Remember Me</label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                <div class="fog-pwd text-right">
                                    <a href="javascript:void(0)"><i class="ion ion-locked"></i> Forgot pwd?</a><br>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-danger btn-block margin-top-10">SIGN IN</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    {{-- <div class="text-center">
                        <p class="mt-20">- OR -</p>
                        <p class="gap-items-2 mb-20">
                            <a class="btn btn-social-icon btn-outline btn-facebook" href="#"><i
                                    class="fa fa-facebook"></i></a>
                            <a class="btn btn-social-icon btn-outline btn-twitter" href="#"><i
                                    class="fa fa-twitter"></i></a>
                            <a class="btn btn-social-icon btn-outline btn-google" href="#"><i
                                    class="fa fa-google-plus"></i></a>
                            <a class="btn btn-social-icon btn-outline btn-instagram" href="#"><i
                                    class="fa fa-instagram"></i></a>
                        </p>
                    </div>

                    <div class="text-center">
                        <p class="mb-0">Don't have an account? <a href="register.html" class="text-info ml-5">Sign
                                Up</a></p>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js') }}"></script>

    <!-- popper -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/popper/dist/popper.min.js') }}"></script>

    <!-- Bootstrap 4.1-->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>

</html>
