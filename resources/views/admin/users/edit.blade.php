@extends('admin.layout')

@section('content')
    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">مدیران سیستم</a></li>
                <li class="breadcrumb-item active" aria-current="page">ویرایش {{$user->profile->full_name}}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">ویرایش کاربر</h6>

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

                        <form role="form"  action="{{ route('admin.users.update',$user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-body col-md-12" >

                                <div class="mb-3 form-group col-md-3 @error('email') has-error @enderror">
                                    <label class="form-label">نام کاربری</label>
                                    <input type="text" name="email" @if(Auth::user()->role_id !== 1) disabled @endif value="{{ $user->email }}" class="form-control" style="text-align: left;direction:ltr;">
                                    @error('email')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 form-group col-md-3 @error('password') has-error @enderror">
                                    <label class="form-label">رمز عبور</label>
                                    <input type="password" name="password" value="" class="form-control" style="text-align: left;direction:ltr;">
                                    @error('password')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 form-group col-md-3 @error('firstname') has-error @enderror">
                                    <label class="form-label">نام</label>
                                    <input type="text" name="firstname"  @if(Auth::user()->role_id !== 1) disabled @endif value="{{ $user->profile->firstname }}" class="form-control">
                                    @error('firstname')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 form-group col-md-3 @error('lastname') has-error @enderror">
                                    <label class="form-label">نام خانوادگی</label>
                                    <input type="text" name="lastname" @if(Auth::user()->role_id !== 1) disabled @endif value="{{ $user->profile->lastname }}" class="form-control ">
                                    @error('lastname')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="active" class="form-label">وضعیت</label>
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check"  @if(Auth::user()->role_id !== 1) disabled @endif @if($user->id == Auth::user()->id) @endif name="activated" value="0" id="active1" autocomplete="off" @if($user->activated == 0) checked @endif>
                                        <label class="btn btn-outline-secondary" for="active1">غیرفعال</label>

                                        <input type="radio" class="btn-check"  @if(Auth::user()->role_id !== 1) disabled @endif @if($user->id == Auth::user()->id)  @endif name="activated" value="1" id="active2" autocomplete="off" @if($user->activated == 1) checked @endif>
                                        <label class="btn btn-outline-success" for="active2">فعال</label>
                                    </div>
                                </div>

                                <div class="mb-3 form-group col-md-3">
                                    <label class="form-label">نقش</label>
                                    <select class="form-select" name="role_id" @if(Auth::user()->role_id !== 1) disabled @endif @if($user->id == Auth::user()->id)  @endif>
                                        @foreach($roles as $role)
                                            <option @if($role->id == $user->role_id) selected @endif value="{{$role->id}}">{{$role->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 @error('avatar') has-error @enderror">
                                    <label class="form-label">تصویر نمایه</label>
                                    <input type="file" name="avatar" class="form-control " @if(Auth::user()->role_id !== 1) disabled @endif>

                                        @if(!is_null($user->profile->avatar))
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="example">
                                                    <div class="d-flex align-items-start">
                                                        <img src="{{ asset('uploads/avatars') }}/{{$user->profile->avatar}}" class="align-self-end wd-100 wd-sm-150 me-3"
                                                             alt="...">
                                                        <div>
                                                            <h5 class="mb-2">{{ $user->profile->firstname }}</h5>
                                                            <h5 class="mb-2">{{ $user->profile->lastname }}</h5>
                                                            <h5 class="mb-2">{{ $user->email }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                    @error('avatar')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>

                            </div>
                            <div style="padding:10px;text-align: left;">
                                <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.users.index')}}'">انصراف</button>
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
