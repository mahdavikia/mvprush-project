@extends('admin.layout')

@section('content')

    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">محتوا</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">تنظیمات</a></li>
                <li class="breadcrumb-item active" aria-current="page">جدید</li>
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

                    <div class="table-responsive">

                        <form role="form"  action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return check_form()" class="was-validated">
                            @csrf
                            @method('POST')
                                <div class="form-body  col-md-12 @error('name') has-error @enderror">
                                    <div class="form-group col-md-3">
                                        <label>نام</label>
                                        <input type="text" name="name" value="" class="form-control" required>
                                        <div class="invalid-feedback">این فیلد اجباری است</div>
                                        @error('name')
                                        <span class="help-block">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 @error('value') has-error @enderror">
                                        <label>مقدار</label>
                                        <input type="text" name="value" value="" class="form-control " required>
                                        <div class="invalid-feedback">این فیلد اجباری است</div>
                                        @error('value')
                                        <span class="help-block">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div style="padding:10px;text-align: left;">
                                    <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.settings.index')}}'">انصراف</button>
                                    <button type="submit" class="btn btn-success">ذخیره</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
