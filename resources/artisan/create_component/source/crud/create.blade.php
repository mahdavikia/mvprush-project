<!--
 Created By: Melorain Component Maker 0.1
 [[date]]
 Component: [[component_name_controller]]
 create.blade.php
-->
@extends('admin.layout')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/vendors/chosen') }}/chosen.css" />
@endsection
@section('content')

    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">محتوا</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.[[component_name]].index') }}">[[component_title]] ها</a></li>
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

                        <form role="form"  action="{{ route('admin.[[component_name]].store') }}" method="POST" enctype="multipart/form-data" onsubmit="return check_form()" class="was-validated">
                            @csrf
                            @method('POST')

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#farsi" role="tab" aria-controls="farsi" aria-selected="true">فارسی</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="false">English</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab3" data-bs-toggle="tab" href="#images" role="tab" aria-controls="images" aria-selected="false">تصاویر</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab4" data-bs-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">فایل ها</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab5" data-bs-toggle="tab" href="#morefields" role="tab" aria-controls="morefields" aria-selected="false">فیلدهای بیشتر</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" id="myTabContent">

                                <div class="tab-pane fade show active" id="farsi" role="tabpanel" aria-labelledby="tab1">
                                    <div class="form-body col-md-12" >
                                        <div class="mb-3 col-md-2 @error('content_cat_id') has-error @enderror" >
                                            <label>دسته بندی</label>
                                            <select name="content_cat_id" class="form-control">
                                                <option value="0" >بدون دسته بندی</option>
                                                @foreach($content_cats as $[[component_name_single]]_cat)
                                                    <option value="{{$[[component_name_single]]_cat->id}}" >{{$[[component_name_single]]_cat->title}}</option>
                                                @endforeach
                                            </select>
                                            @error('content_cat_id')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3 @error('title') has-error @enderror">
                                            <label>عنوان</label>
                                            <input type="text" name="title" id="title" value="" class="form-control" required>
                                            <div class="invalid-feedback">این فیلد اجباری است</div>
                                            @error('title')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3 @error('name') has-error @enderror">
                                            <label>نام</label>
                                            <input type="text" name="name" value="" class="form-control" >
                                            @error('name')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('brief') has-error @enderror">
                                            <label>خلاصه</label>
                                            <textarea  name="brief" class="form-control textarea"></textarea>
                                            @error('brief')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('content') has-error @enderror">
                                            <label>متن</label>
                                            <textarea  name="content" id="ck1" class="form-control textarea"></textarea>
                                            @error('content')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="active" class="form-label">وضعیت</label>
                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check" name="active" value="0" id="active1" autocomplete="off" checked>
                                                <label class="btn btn-outline-secondary" for="active1">غیرفعال</label>

                                                <input type="radio" class="btn-check" name="active" value="1" id="active2" autocomplete="off" >
                                                <label class="btn btn-outline-success" for="active2">فعال</label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-12 @error('link') has-error @enderror">
                                            <label>لینک</label>
                                            <textarea  name="link" class="form-control textarea" style="text-align: left;direction: ltr;"></textarea>
                                            @error('link')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12" >
                                            <div class="form-group col-md-2">
                                                <label>تصویر</label>
                                                <input type="file" accept="image/*" name="image" id="upload_image" class="form-control" />
                                                <br>
                                                <img id="image_preview" style="width:200px;border:1px #dddddd solid;border-spacing: 3px;display: none" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="tab2">
                                    <div class="mb-3 col-md-12" >

                                        <div class="form-group col-md-3 @error('title_en') has-error @enderror">
                                            <label>Title</label>
                                            <input type="text" name="title_en" value="" class="form-control">
                                            @error('title_en')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('brief_en') has-error @enderror">
                                            <label>Brief</label>
                                            <textarea  name="brief_en" class="form-control textarea"></textarea>
                                            @error('brief_en')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('content_en') has-error @enderror">
                                            <label>Content</label>
                                            <textarea  name="content_en" id="ck2" class="form-control textarea"></textarea>
                                            @error('content_en')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="tab3">
                                    <div class="mb-3 col-md-12" >

                                        <label class="form-label" for="inputImage">انتخاب تصاویر:</label>
                                        <input
                                                type="file"
                                                name="images[]"
                                                id="inputImage"
                                                multiple="multiple"
                                                class="form-control @error('images') is-invalid @enderror">

                                        @error('images')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="tab4">
                                    <div class="mb-3 col-md-12" >

                                        <label class="form-label" for="inputFile">انتخاب فایل ها:</label>
                                        <input
                                                type="file"
                                                name="docs[]"
                                                id="inputFile"
                                                multiple="multiple"
                                                class="form-control @error('docs') is-invalid @enderror">

                                        @error('docs')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="morefields" role="tabpanel" aria-labelledby="tab5">
                                    <div class="mb-3 col-md-12" >
                                        <table class="table table-hover table-bordered">
                                            <tr>
                                                <th>عنوان</th>
                                                <th>مقدار</th>
                                            </tr>
                                            <tr id="morefields_create_row">
                                                <td >
                                                    <label for="morefield_title"></label>
                                                    <select class="form-control chosen-select" id="morefield_title_main" data-placeholder="" name="morefield_title_main" >
                                                        @foreach($morefields as $morefild)
                                                            <option value="{{$morefild->title}}">{{$morefild->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" name="morefield_value_main" id="morefield_value_main">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn default" onclick="saveMorefield()">ثبت موقت</button>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                                <div style="padding:10px;text-align: left;">
                                    <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.[[component_name]].index')}}'">انصراف</button>
                                    <input type="hidden" id="stay_here" name="stay_here" />
                                    <button type="submit" class="btn btn-success" onclick="document.getElementById('stay_here').value='stay_here'">ذخیره</button>
                                    <button type="submit" class="btn btn-info">ذخیره و بازگشت</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('') }}assets/vendors/chosen/chosen.jquery.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('ck1', {
            filebrowserBrowseUrl: '{{ asset('') }}assets/vendors/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl: '{{ asset('') }}assets/vendors/ckfinder/ckfinder.html?Type=Images',
            filebrowserUploadUrl: '{{ asset('') }}assets/vendors/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '{{ asset('') }}assets/vendors/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserWindowWidth : '1000',
            filebrowserWindowHeight : '700',
            height : '400'
        });

        CKEDITOR.replace('ck2', {
            filebrowserBrowseUrl: '{{ asset('') }}assets/vendors/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl: '{{ asset('') }}assets/vendors/ckfinder/ckfinder.html?Type=Images',
            filebrowserUploadUrl: '{{ asset('') }}assets/vendors/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '{{ asset('') }}assets/vendors/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserWindowWidth : '1000',
            filebrowserWindowHeight : '700',
            height : '400'
        });
        let imgInp = document.getElementById('upload_image');
        let image_delete_link = document.getElementById('image_delete_link');
        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                document.getElementById('image_preview').src = URL.createObjectURL(file);
                document.getElementById('image_preview').style.display = 'block';
                image_delete_link.style.display = 'block';
            }
        }
        function check_form(){
            let title = $('#title').val();
            if(title === ""){
                  alert('عنوان را بنویسید');
                   $('#title').focus();
                   return false;
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function doPost(url,record_id){
            $.post(url,null,
                function(data, status){
                    //alert("Data: " + data + "\nStatus: " + status);
                    if(data === "1" && status === "success"){
                        $('#record_'+record_id).remove();
                        $('.modal-backdrop').remove();
                    }
                });
        }

        $('.chosen-select').chosen({ rtl: true,no_results_text: 'یافت نشد',width:200});

        let row_counter = 0;
        function saveMorefield(){
            row_counter ++;
            const morefield_title = $('#morefield_title_main').val();
            const morefield_value = $('#morefield_value_main').val();
            $('#morefields_create_row').before('<tr id="morefield_row_'+row_counter+'"><td><input type="text" class="form-control" name="morefield_title[]" value="'+morefield_title+'" /></td><td><input type="text" class="form-control" name="morefield_value[]" value="'+morefield_value+'" /></td><td>موقت [ <a href="javascript:deleteRow('+row_counter+')">حذف</a> ]</td></tr>');
        }
        function deleteRow(r){
            $('#morefield_row_'+r).remove();
        }

    </script>

@endsection
