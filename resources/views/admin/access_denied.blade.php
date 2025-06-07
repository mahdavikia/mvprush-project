@extends('admin.layout')

@section('content')

    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item active" aria-current="page">دسترسی ها</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="alert alert-warning">
                        <i class="link-icon" data-feather="info"></i>
                        دسترسی شما به این بخش از سیستم محدود شده است.
                    </p>
                    <p>
                        <a class="btn btn-info" href="{{url()->previous()}}">

                            بازگشت </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
