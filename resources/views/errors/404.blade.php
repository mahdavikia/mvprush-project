<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>صفحه/مطلب یافت نشد</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('site_assets')}}/images/favicon.svg" />

    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="{{asset('site_assets')}}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{asset('site_assets')}}/css/LineIcons.2.0.css" />
    <link rel="stylesheet" href="{{asset('site_assets')}}/css/animate.css" />
    <link rel="stylesheet" href="{{asset('site_assets')}}/css/tiny-slider.css" />
    <link rel="stylesheet" href="{{asset('site_assets')}}/css/glightbox.min.css" />
    <link rel="stylesheet" href="{{asset('site_assets')}}/css/main.css" />

</head>

<body>
<!--[if lte IE 9]>
<p class="browserupgrade">
    You are using an <strong>outdated</strong> browser. Please
    <a href="https://browsehappy.com/">upgrade your browser</a> to improve
    your experience and security.
</p>
<![endif]-->

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <div class="preloader-icon">
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<!-- /End Preloader -->

<!-- Start Error Area -->
<div class="error-area">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="error-content">
                    <h1>404</h1>
                    <h2>صفحه یا مطلب مورد نظر شما یافت نشد</h2>
                    <p>ممکن است آدرس را اشتباه زده باشید و یا این مطلب یا صفحه حذف شده باشد</p>
                    <div class="button">
                        <a href="javascript:history.back(-1)" class="btn">بازگشت</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Error Area -->

<!-- ========================= JS here ========================= -->
<script src="{{asset('site_assets')}}/js/bootstrap.min.js"></script>
<script src="{{asset('site_assets')}}/js/wow.min.js"></script>
<script src="{{asset('site_assets')}}/js/tiny-slider.js"></script>
<script src="{{asset('site_assets')}}/js/glightbox.min.js"></script>
<script>
    window.onload = function () {
        window.setTimeout(fadeout, 500);
    }

    function fadeout() {
        document.querySelector('.preloader').style.opacity = '0';
        document.querySelector('.preloader').style.display = 'none';
    }
</script>
</body>

</html>