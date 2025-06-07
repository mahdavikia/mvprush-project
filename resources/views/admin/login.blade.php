<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="MeloRain">

    <title>ورود به ناحیه مدیریت</title>

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('') }}assets/vendors/core/core.css">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('') }}assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/demo1/style-rtl.min.css">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('') }}assets/images/favicon.png" />
</head>

<body>
<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">

                    @if($message = Session::get('error') || $message = Session::get('success'))
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        @if ($message = Session::get('error'))
                                            <div class="note note-danger">
                                                <strong>خطا!</strong>
                                                <br>
                                                {{ $message }}
                                            </div>
                                        @endif
                                        @if ($message = Session::get('success'))
                                            <div class="note note-success">
                                                {{ $message }}
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-8 col-xl-6 mx-auto">

                        <form class="login-form" action="{{ route('admin.login.post') }}" method="post">
                            @csrf
                            @method('POST')

                        <div class="card">
                            <div class="row">
                                <div class="col-md-4 pe-md-0">
                                    <div class="auth-side-wrapper">

                                    </div>
                                </div>
                                <div class="col-md-8 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="#" class="noble-ui-logo d-block mb-2">Dash<span>View</span></a>
                                        <h5 class="text-muted fw-normal mb-4">مدیریت و نمایش داشبوردهای اطلاعاتی
                                        </h5>
                                        @if ($errors->any())
                                            <div class="alert alert-warning">
                                                <p class="mb-5"><span class="font-bold">خطا !</span><span> لطفا موارد زیر را بصورت صحیح تکمیل فرمایید.</span></p>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>- {{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if(isset($error_message)  && !is_null($error_message))
                                            <div class="alert alert-danger">
                                                {{$error_message}}
                                            </div>
                                        @endif
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
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="password" class="form-label">تیم</label>--}}
{{--                                                <select class="form-control form-select" name="team_id" id="team_id">--}}
{{--                                                    @foreach($teams as $team)--}}
{{--                                                        <option value="{{$team->id}}">{{$team->name}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </div>--}}

{{--                                            <div class="form-group">--}}
{{--                                                <div class="col-lg-6 col-md-12 col-12 captcha" style="cursor: pointer">--}}
{{--                                                    <span>{!! captcha_img('math') !!}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="control-label visible-ie8 visible-ie9">پاسخ عبارت :--}}
{{--                                                [<a href="javascript:void(0)" style="margin-right: 5px;" id="reload">عبارت جدید</a>]--}}
{{--                                                </label>--}}
{{--                                                <div class="input-icon">--}}
{{--                                                    <i class="fa fa-lock"></i>--}}
{{--                                                    <input class="form-control placeholder-no-fix left @error('captcha') has-error @enderror" type="text" autocomplete="off"  style="text-align: left;direction: ltr;" placeholder="پاسخ عبارت" name="captcha"/>--}}

{{--                                                </div>--}}
{{--                                            </div>--}}

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
<script src="{{ asset('') }}assets/vendors/core/core.js"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('') }}assets/vendors/feather-icons/feather.min.js"></script>
{{--<script src="{{ asset('') }}assets/js/template.js"></script>--}}
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
