@extends('admin.layout')

@section('content')

    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">مدیران سیستم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">نقش ها</a></li>
                <li class="breadcrumb-item active">ویرایش {{$role->title}}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">ویرایش نقش</h6>

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

                        <form role="form" class="forms-sample" action="{{ route('admin.roles.update',$role) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body col-md-12" >
                                <div class="mb-3 col-md-4 @error('title') has-error @enderror">
                                    <label for="title" class="form-label">عنوان</label>
                                    <input type="text" name="title" id="title" value="{{ $role->title }}" class="form-control">
                                    @error('title')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4 @error('name') has-error @enderror">
                                    <label for="name" class="form-label">نام</label>
                                    <input type="text" name="name" id="name" value="{{ $role->name }}" style="text-align:left;direction:ltr;" class="form-control">
                                    @error('name')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="active" class="form-label">وضعیت</label>
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="active" value="0" id="active1" autocomplete="off" @if($role->active == 0) checked @endif>
                                        <label class="btn btn-outline-secondary" for="active1">غیرفعال</label>

                                        <input type="radio" class="btn-check" name="active" value="1" id="active2" autocomplete="off" @if($role->active == 1) checked @endif>
                                        <label class="btn btn-outline-success" for="active2">فعال</label>
                                    </div>
                                </div>
                            </div>
                            <div style="padding:10px;text-align: left;">
                                <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.roles.index')}}'">انصراف</button>
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
