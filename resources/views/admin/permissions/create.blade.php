@extends('admin.layout')

@section('content')
    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">مدیران سیستم</a></li>
                <li class="breadcrumb-item"><a href="{{route("admin.roles.index")}}">نقش ها</a></li>
                <li class="breadcrumb-item">{{$role->title}}</li>
                <li class="breadcrumb-item"><a href="{{route("admin.permissions.index",$role)}}">دسترسی ها</a></li>
                <li class="breadcrumb-item active" aria-current="page">جدید</li>
            </ol>
        </nav>
    </div>

    @if($message = Session::get('error') || $message = Session::get('success'))
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if ($message = Session::get('error'))

                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>خطا!</strong>{{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        </div>

                    @endif
                    @if ($message = Session::get('success'))

                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">ایجاد نقش جدید</h6>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>خطا!</strong> لطفا موارد زیر را تکمیل فرمایید<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-responsive">

                        <form action="{{ route('admin.permissions.store',$role) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-body col-md-12" >

                                <div class="form-body  col-md-12 @error('title') has-error @enderror">
                                    <div class="form-group col-md-6">
                                        <label>عنوان</label>
                                        <input type="text" name="title" value="" class="form-control">
                                        @error('title')
                                        <span class="help-block">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 @error('route') has-error @enderror">
                                        <label>مسیر</label>
                                        <input type="text" name="route" value="" class="form-control ">
                                        @error('route')
                                        <span class="help-block">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div style="padding:10px;text-align: left;">
                                <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.permissions.index',$role->id)}}'">انصراف</button>
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div></div></div></div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">

                        <form action="{{ route('admin.permissions.storeAll',$role) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-body col-md-12" >

                                <input type="hidden" name="role_id" value="{{$role->id}}" />
                                <div class="form-body  col-md-12" >
                                    <div class="form-group col-md-6">
                                        <h6 class="card-title">ایجاد دسته ای برای یک بخش</h6>
                                        <select name="controller" class="form-select">
                                            @foreach ($controller_bank as $controller)
                                                <option value="{{$controller->name}}|{{$controller->title}}">{{$controller->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" >
                                        <p><br></p>
                                        <div class="checkbox-list">

                                            @php
                                            $check_values = [
                                                "full|کامل",
                                                "index|لیست",
                                                "create|فرم ایجاد",
                                                "edit|فرم ذخیره",
                                                "store|ایجاد",
                                                "show|نمایش",
                                                "update|بروزرسانی",
                                                "destroy|حذف",
                                                "active|فعال یا غیرفعال سازی رکورد"
                                            ];
                                            $check_titles = [
                                                "کامل",
                                                "لیست",
                                                "فرم ایجاد",
                                                "فرم ذخیره",
                                                "ایجاد",
                                                "نمایش",
                                                "بروزرسانی",
                                                "حذف",
                                                "فعال یا غیرفعال سازی رکورد"
                                            ];
                                            $c=0;
                                            @endphp
                                            @foreach($check_values as $checkbox)
                                                <div class="form-check mb-3">
                                                    <input type="checkbox" name="action[]" value="{{$checkbox}}" class="form-check-input" id="check{{$c}}">
                                                    <label class="form-check-label" for="check{{$c}}">
                                                        {{$check_titles[$c]}}
                                                    </label>
                                                </div>
                                                @php
                                                    $c++
                                                @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div style="padding:10px;text-align: left;">
                                <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.permissions.index',$role->id)}}'">انصراف</button>
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
