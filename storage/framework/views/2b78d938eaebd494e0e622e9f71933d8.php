<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="MeloRain">

    <title>ورود به ناحیه مدیریت</title>

    <!-- core:css -->
    <link rel="stylesheet" href="<?php echo e(asset('')); ?>assets/vendors/core/core.css">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="<?php echo e(asset('')); ?>assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="<?php echo e(asset('')); ?>assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo e(asset('')); ?>assets/css/demo1/style-rtl.min.css">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="<?php echo e(asset('')); ?>assets/images/favicon.png" />
</head>

<body>
<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">

                    <?php if($message = Session::get('error') || $message = Session::get('success')): ?>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if($message = Session::get('error')): ?>
                                            <div class="note note-danger">
                                                <strong>خطا!</strong>
                                                <br>
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php endif; ?>
                                        <?php if($message = Session::get('success')): ?>
                                            <div class="note note-success">
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-8 col-xl-6 mx-auto">

                        <form class="login-form" action="<?php echo e(route('admin.login.post')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('POST'); ?>

                        <div class="card">
                            <div class="row">
                                <div class="col-md-4 pe-md-0">
                                    <div class="auth-side-wrapper">

                                    </div>
                                </div>
                                <div class="col-md-8 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="#" class="noble-ui-logo d-block mb-2">MVP<span>Rush</span></a>
                                        <h5 class="text-muted fw-normal mb-4">ناحیه مدیریت
                                        </h5>
                                        <?php if($errors->any()): ?>
                                            <div class="alert alert-warning">
                                                <p class="mb-5"><span class="font-bold">خطا !</span><span> لطفا موارد زیر را بصورت صحیح تکمیل فرمایید.</span></p>
                                                <ul>
                                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>- <?php echo e($error); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(isset($error_message)  && !is_null($error_message)): ?>
                                            <div class="alert alert-danger">
                                                <?php echo e($error_message); ?>

                                            </div>
                                        <?php endif; ?>
                                        <form class="forms-sample">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">نام کاربری</label>
                                                <input type="email" class="form-control" style="text-align: left;direction: ltr;" name="email" id="email"
                                                       placeholder="نام کاربری">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">رمز عبور</label>
                                                <input type="password" class="form-control" style="text-align: left;direction: ltr;" name="password" id="password"
                                                       autocomplete="current-password" placeholder="رمز عبور">
                                            </div>

























                                            <div class="form-group mt-2">
                                                <button type="submit"
                                                        class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                                    ورود
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>


        </div>
    </div>
</div>
<!-- core:js -->
<script src="<?php echo e(asset('')); ?>assets/vendors/core/core.js"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="<?php echo e(asset('')); ?>assets/vendors/feather-icons/feather.min.js"></script>

<!-- endinject -->
<script type="text/javascript">
   $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });
</script>

<!-- Custom js for this page -->
<!-- End custom js for this page -->

</body>

</html>
<?php /**PATH F:\wamp64_new\www\mvprush\_git\resources\views/admin/login.blade.php ENDPATH**/ ?>