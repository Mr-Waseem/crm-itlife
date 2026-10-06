<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon"
        href="<?php echo e(URL::asset('dashboard/images/favicon.ico')); ?>">

    <title><?php echo e(SettingsFacade::data()->system_name); ?> - Log in </title>

    <!-- Bootstrap 4.1-->
    <link rel="stylesheet"
        href="<?php echo e(URL::asset('dashboard/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css')); ?>">

    <!-- Bootstrap extend-->
    <link rel="stylesheet" href="<?php echo e(URL::asset('dashboard/css/bootstrap-extend.css')); ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('dashboard/css/master_style.css')); ?>">

    <!-- SoftMaterial admin skins -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('dashboard/css/skins/_all-skins.css')); ?>">
</head>

<body class="hold-transition bg-img" style="background-image: url(<?php echo e(URL::asset('dashboard/images/gallery/full/6.jpg')); ?>)" data-overlay="4">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-lg-5 col-md-8 col-12">
                <div class="content-top-agile">
                    <h2>Get started with Us</h2>
                    <p class="text-white">Sign in to start your session</p>
                </div>
                <div class="p-40 mt-10 bg-white content-bottom">
                    <form
                        action="<?php echo e(url('/login')); ?>"
                        method="post">
                        <!-- <?php echo csrf_field(); ?> -->
                        <?php echo e(csrf_field()); ?>

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger border-danger"><i
                                            class="ti-user"></i></span>
                                </div>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" placeholder="Email" required autofocus>
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="help-block">
                                <strong style="color:red;"><?php echo e($message); ?></strong>
                            </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="help-block">
                                <strong style="color:red;"><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="<?php echo e(URL::asset('dashboard/assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js')); ?>"></script>

    <!-- popper -->
    <script src="<?php echo e(URL::asset('dashboard/assets/vendor_components/popper/dist/popper.min.js')); ?>"></script>

    <!-- Bootstrap 4.1-->
    <script src="<?php echo e(URL::asset('dashboard/assets/vendor_components/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
</body>

</html>
<?php /**PATH D:\Axis-Coding\xampp-8.2.12\htdocs\it_life_work\it-lifee\resources\resources\views/auth/login.blade.php ENDPATH**/ ?>