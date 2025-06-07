<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>DGT DashView 1.0</title>

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
    <link rel="stylesheet" href="{{ asset('') }}assets/vendors/sweetalert2/sweetalert2.min.css">
    @yield('header')
    <meta name="csrf-token-global" content="{{ csrf_token() }}" />
</head>

<body class="{{$sidebar_theme}}">
<div class="main-wrapper">

    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="{{route('admin.versions.index')}}" class="sidebar-brand">
                Dash<strong>View</strong>
            </a>
            <div class="sidebar-toggler not-active">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="sidebar-body">
            <ul class="nav">
                <li class="nav-item nav-category">سیستم</li>
                @if(Melorain::getAccess('Global@dashboard'))
                    <li class="nav-item @if(in_array(Request::segment(2), ['dashboard'])) active @endif">
                        <a href="{{route('admin.dashboard')}}" class="nav-link ">
                            <i class="link-icon" data-feather="box"></i>
                            <span class="link-title">داشبورد</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item nav-category ">مدیریت</li>
                @if(Melorain::getAccess('UserController@index'))
                    <li class="nav-item @if(in_array(Request::segment(2), ['users','roles','permissions'])) active @endif">
                        <a class="nav-link" data-bs-toggle="collapse" href="#users" role="button" aria-expanded="false"
                           aria-controls="users">
                            <i class="link-icon" data-feather="users"></i>
                            <span class="link-title">مدیران سیستم</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse @if(in_array(Request::segment(2), ['users','roles','permissions'])) show @endif" id="users">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{route('admin.users.index')}}" class="nav-link @if(in_array(Request::segment(2), ['users'])) active @endif">لیست مدیران</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.roles.index')}}" class="nav-link @if(in_array(Request::segment(2), ['roles','permissions'])) active @endif">مدیریت نقش ها</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <li class="nav-item nav-category">سفارشی سازی</li>
                @if(Melorain::getAccess('SettingController@full'))
                    <li class="nav-item @if(in_array(Request::segment(2), ['setting'])) active @endif">
                        <a href="{{route('admin.settings.index')}}" class="nav-link">
                            <i class="link-icon" data-feather="settings"></i>
                            <span class="link-title">تنظیمات</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
    @if(in_array(Request::segment(2), ['dashboard']))
    <nav class="settings-sidebar">
        <div class="sidebar-body">
            <a href="#" class="settings-sidebar-toggler">
                <i data-feather="settings"></i>
            </a>
            <h6 class="text-muted mb-2">تنظیمات کلی : </h6>
            <div class="mb-3 pb-3">
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" onchange="sidebarUpdate()" name="sidebarThemeSettings" id="sidebarLight"
                           value="sidebar-light" @if($sidebar_theme == 'sidebar-light') checked @endif>
                    <label class="form-check-label" for="sidebarLight">
                        تم روشن
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" onchange="sidebarUpdate()" name="sidebarThemeSettings" id="sidebarDark"
                           value="sidebar-dark" @if($sidebar_theme == 'sidebar-dark') checked @endif>
                    <label class="form-check-label" for="sidebarDark">
                        تم تاریک
                    </label>
                </div>
            </div>
        </div>
    </nav>
    @endif
    <!-- partial -->

    <div class="page-wrapper">

        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar">
            <a href="#" class="sidebar-toggler">
                <i data-feather="menu"></i>
            </a>
            <div class="navbar-content">
                <form action="{{route('admin.search')}}" method="POST" onsubmit="return check_search()" class="search-form">
                    @csrf
                    @method('POST')
                    <div class="input-group">
                        <div class="input-group-text">
                            <i data-feather="search"></i>
                        </div>

                        <input type="text" class="form-control" id="filter_title" name="filter_title" placeholder="جستجو ...">

                    </div>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <img class="wd-30 ht-30 rounded-circle" src="{{ asset('uploads/avatars') }}/{{$current_admin->profile->avatar}}" alt="profile">
                        </a>
                        <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                            <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                                <div class="mb-3">
                                    <img class="wd-80 ht-80 rounded-circle" src="{{ asset('uploads/avatars') }}/{{$current_admin->profile->avatar}}" alt="">
                                </div>
                                <div class="text-center">
                                    <p class="tx-16 fw-bolder">{{$current_admin->profile->firstname}} {{$current_admin->profile->lastname}}</p>
                                    <p class="tx-12 text-muted">{{$current_admin->team->name}}</p>
                                    <p class="tx-12 text-muted">{{$current_admin->email}}</p>
                                </div>
                            </div>
                            <ul class="list-unstyled p-1">
                                <li class="dropdown-item py-2">
                                    <a href="{{route("admin.users.edit",$current_admin->id)}}" class="text-body ms-0">
                                        <i class="me-2 icon-md" data-feather="edit"></i>
                                        <span>ویرایش پروفایل</span>
                                    </a>
                                </li>
                                <li class="dropdown-item py-2">
                                    <a href="{{route('admin.logout')}}" class="text-body ms-0">
                                        <i class="me-2 icon-md" data-feather="log-out"></i>
                                        <span>خروج</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- partial -->

        <div class="page-content">
            @yield('breadcrumb')
            @yield('content')
        </div>

        <!-- partial:../../partials/_footer.html -->
        <footer
                class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
            <p class="text-muted mb-1 mb-md-0">کپی رایت ©
                <?=date('Y')?>
                ، تمام حقوق محفوظ است
                نسخه {{$last_version->name}} |
                <a href="{{route('admin.versions.index')}}">مشاهده تغییرات</a>
            </p>
        </footer>
        <!-- partial -->
    </div>
</div>

<!-- core:js -->
<script src="{{ asset('') }}assets/vendors/core/core.js"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('') }}assets/vendors/feather-icons/feather.min.js"></script>
<script src="{{ asset('') }}assets/js/template.js"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<!-- End custom js for this page -->
@yield('footer')
<script type="text/javascript">

        function sidebarUpdate() {
            let data;

            if (document.getElementById('sidebarLight').checked) {
                data = {
                    value: document.getElementById('sidebarLight').value,
                    _token: $('meta[name="csrf-token-global"]').attr('content')
                };
            }
            if (document.getElementById('sidebarDark').checked) {
                data = {
                    value: document.getElementById('sidebarDark').value,
                    _token: $('meta[name="csrf-token-global"]').attr('content')
                };
            }

            $.post('{{route('admin.user_options.ajax_update',['name'=>'sidebar_theme'])}}', data,
                function (data, status) {
                    // console.log('data: '+data+', status: '+status);
                    if (data === '1' && status === 'success') {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'با موفقیت تغییر کرد'
                        });
                    } else {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'خطا در ثبت تنظیمات'
                        });
                    }
                });
        }

</script>
<script src="{{ asset('') }}assets/vendors/sweetalert2/sweetalert2.min.js"></script>
</body>
</html>
