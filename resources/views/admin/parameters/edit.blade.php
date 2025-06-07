@extends('admin.layout')

@section('content')

    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">محتوا</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.parameters.index') }}">پارامترها</a></li>
                <li class="breadcrumb-item active" aria-current="page">ویرایش {{$parameter->name}}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">ایجاد</h6>

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

                    <div class="table-responsive">

                        <form role="form"  action="{{ route('admin.parameters.update',$parameter) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body  col-md-12 @error('name') has-error @enderror">
                                <div class="form-group col-md-3">
                                    <label>نام</label>
                                    <input type="text" name="name" value="{{$parameter->name}}" class="form-control" required>
                                    <div class="invalid-feedback">این فیلد اجباری است</div>
                                    @error('name')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3 @error('value') has-error @enderror">
                                    <label>مقدار</label>
                                    <input type="text" name="value" value="{{$parameter->value}}" class="form-control " required>
                                    <div class="invalid-feedback">این فیلد اجباری است</div>
                                    @error('value')
                                    <span class="help-block">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div style="padding:10px;text-align: left;">
                                <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.parameters.index')}}'">انصراف</button>
                                <input type="hidden" id="stay_here" name="stay_here" />
                                <button type="submit" class="btn btn-success" onclick="document.getElementById('stay_here').value='stay_here'">بروزرسانی</button>
                                <button type="submit" class="btn btn-info">بروزرسانی و بازگشت</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection