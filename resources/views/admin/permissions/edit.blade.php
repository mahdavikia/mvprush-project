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
                <li class="breadcrumb-item active" aria-current="page">ویرایش {{$permission->title}}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
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

                        <form role="form" class="forms-sample" action="{{ route('admin.permissions.update',[$role,$permission]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body col-md-12" >

                                <div class="form-group col-md-3">
                                    <label>عنوان</label>
                                    <input type="text" name="title" value="{{$permission->title}}" class="form-control">
                                    @error('title')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3 @error('route') has-error @enderror">
                                    <label>مسیر</label>
                                    <input type="text" name="route" value="{{$permission->route}}" class="form-control ">
                                    @error('route')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
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
