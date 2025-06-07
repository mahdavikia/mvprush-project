<!--
 Created By: Melorain Component Maker 0.1
 [[date]]
 Component: [[component_name_controller]]
 edit.blade.php
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
                <li class="breadcrumb-item active" aria-current="page">ویرایش {{$[[component_name_single]]->title}}</li>
            </ol>
        </nav>
    </div>


    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">ویرایش</h6>

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

                        <form role="form"  action="{{ route('admin.[[component_name]].update',$[[component_name_single]]) }}" method="POST" enctype="multipart/form-data" onsubmit="return check_form()" class="was-validated">
                            @csrf
                            @method('PUT')

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
                                            <select name="content_cat_id" class="form-select">
                                                <option value="0" @if($[[component_name_single]]->[[component_name_single]]_cat_id == 0) selected @endif>بدون دسته بندی</option>
                                                @foreach($content_cats as $[[component_name_single]]_cat)
                                                    <option value="{{$[[component_name_single]]_cat->id}}" @if($[[component_name_single]]_cat->id == $[[component_name_single]]->content_cat_id) selected @endif>{{$[[component_name_single]]_cat->title}}</option>
                                                @endforeach
                                            </select>
                                            @error('content_cat_id')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3 @error('title') has-error @enderror">
                                            <label>عنوان</label>
                                            <input type="text" name="title" id="title" value="{{$[[component_name_single]]->title}}" class="form-control" required>
                                            <div class="invalid-feedback">این فیلد اجباری است</div>
                                            @error('title')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3 @error('name') has-error @enderror">
                                            <label>نام</label>
                                            <input type="text" name="name" value="{{$[[component_name_single]]->name}}" class="form-control" >
                                            @error('name')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('brief') has-error @enderror">
                                            <label>خلاصه</label>
                                            <textarea  name="brief" class="form-control textarea">{{$[[component_name_single]]->brief}}</textarea>
                                            @error('brief')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('content') has-error @enderror">
                                            <label>متن</label>
                                            <textarea  name="content" id="ck1" class="form-control textarea">{{$[[component_name_single]]->content}}</textarea>
                                            @error('content')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="active" class="form-label">وضعیت</label>
                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check" name="active" value="0" id="active1" autocomplete="off" @if($[[component_name_single]]->active == 0) checked @endif>
                                                <label class="btn btn-outline-secondary" for="active1">غیرفعال</label>

                                                <input type="radio" class="btn-check" name="active" value="1" id="active2" autocomplete="off" @if($[[component_name_single]]->active == 1) checked @endif>
                                                <label class="btn btn-outline-success" for="active2">فعال</label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-12 @error('link') has-error @enderror">
                                            <label>لینک</label>
                                            <textarea  name="link" class="form-control textarea" style="text-align: left;direction: ltr;">{{$[[component_name_single]]->link}}</textarea>
                                            @error('link')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12" >
                                            <div class="form-group col-md-2">
                                                <label>تصویر</label>
                                                <input type="file" accept="image/*" name="image" id="upload_image" class="form-control" />
                                                <br>
                                                <input type="hidden" value="{{$[[component_name_single]]->image ?? null}}" name="old_image" id="old_image" />
                                                <img id="image_preview" @if(!is_null($[[component_name_single]]->image)) src="{{asset('uploads/[[component_name]]').'/'.$[[component_name_single]]->image}}" @endif style="width:200px;border:1px #dddddd solid;border-spacing: 3px;display: @if(!is_null($[[component_name_single]]->image)) block @else none @endif" />
                                                @if(!is_null($[[component_name_single]]->image))
                                                    <br/>
                                                    <a href="javascript:void(0);" onclick="deleteImage()">[ حذف ]</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="tab2">
                                    <div class="mb-3 col-md-12" >

                                        <div class="form-group col-md-3 @error('title_en') has-error @enderror">
                                            <label>Title</label>
                                            <input type="text" name="title_en" value="{{$[[component_name_single]]->title_en}}" class="form-control">
                                            @error('title_en')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('brief_en') has-error @enderror">
                                            <label>Brief</label>
                                            <textarea  name="brief_en" class="form-control textarea">{{$[[component_name_single]]->brief_en}}</textarea>
                                            @error('brief_en')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-12 @error('content_en') has-error @enderror">
                                            <label>Content</label>
                                            <textarea  name="content_en" id="ck2" class="form-control textarea">{{$[[component_name_single]]->content_en}}</textarea>
                                            @error('content_en')
                                            <span class="help-block">{{$message}}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="tab3">
                                    <div class="form-body col-md-12" >
                                        @if (isset($images))
                                            <table class="table table-hover table-bordered">
                                                <tr>
                                                    <th>تصویر</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                @foreach ($images as $image)
                                                    <tr id="record_{{$image->id}}">
                                                        <td>
                                                            <a href="{{asset('uploads/[[component_name]]/'.$[[component_name_single]]->id).'/'.$image->file_name}}" target="_blank">
                                                                <img src="{{asset('uploads/[[component_name]]/'.$[[component_name_single]]->id).'/'.$image->file_name}}" style="width: 50px;height:50px;" />
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#image_modal_{{$image->id}}" class="btn btn-danger" style="font-family: 'IranSANS">حذف تصویر</a>
                                                            {{--                                                            @if (!$image->is_main)--}}
                                                            {{--                                                                <a href="{{route('admin.contents.image_set_main',[$content,$image])}}" class="btn btn-primary">تعیین بعنوان عکس اصلی</a>--}}
                                                            {{--                                                            @endif--}}

                                                            <div class="modal fade" id="image_modal_{{$image->id}}" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">

                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel1">تایید</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            از حذف این تصویر مطئن هستید؟
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                                                            <button type="button" class="btn btn-danger" onclick="doPost('{{ route('admin.[[component_name]].ajax_attachment_delete',[$[[component_name_single]],$image]) }}',{{$image->id}})">بله، حذف شود</button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                        <div class="form-body col-md-12" >

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
                                </div>

                                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="tab4">
                                    <div class="form-body col-md-12" >
                                        @if (isset($files))
                                            <table class="table table-hover table-bordered">
                                                <tr>
                                                    <th>فایل</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                @foreach ($files as $file)
                                                    <tr id="record_{{$file->id}}">
                                                        <td>
                                                            <a href="{{asset('uploads/[[component_name]]/'.$[[component_name_single]]->id).'/'.$file->file_name}}" target="_blank">
                                                                لینک مستقیم فایل
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{--                                                    <a href="{{route('admin.contents.image_delete',[$content,$file])}}" class="btn btn-danger">حذف فایل</a>--}}
                                                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#file_modal_{{$file->id}}" class="btn btn-danger" style="font-family: 'IranSANS">حذف فایل</a>
                                                            @if (!$file->is_main)
                                                                <a href="{{route('admin.[[component_name]].doc_set_main',[$[[component_name_single]],$file])}}" class="btn btn-primary">تعیین بعنوان فایل اصلی</a>
                                                            @endif


                                                            <div class="modal fade" id="file_modal_{{$file->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">تایید</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            از حذف این مورد مطئن هستید؟
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                                                            <button type="button" class="btn btn-danger" onclick="doPost('{{ route('admin.[[component_name]].ajax_attachment_delete',[$[[component_name_single]],$file]) }}',{{$file->id}})">بله، حذف شود</button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>
                                    <div class="form-body col-md-12" >

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
                                    <div class="form-body col-md-12" >
                                        <table class="table table-hover table-bordered">
                                            <tr>
                                                <th>عنوان</th>
                                                <th>مقدار</th>
                                            </tr>
                                            @php
                                                $c=1;
                                            @endphp
                                            @foreach($morefields_full as $m)
                                                <tr id="morefield_row_{{$c}}">
                                                    <td>
                                                        <input type="text" class="form-control" readonly="readonly" name="morefield_arr[{{$c}}][title]" value="{{$m->title}}" /></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="morefield_arr[{{$c}}][value]" value="{{$m->value}}" /></td>
                                                    <td>[ <a href="javascript:deleteRow({{$c}})">حذف</a> ]</td>
                                                </tr>
                                                @php
                                                    $c++;
                                                @endphp
                                            @endforeach
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
                                                    <button type="button" class="btn btn-warning" onclick="saveMorefield()">ثبت موقت</button>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                                <div style="padding:10px;text-align: left;">
                                    <button type="button" class="btn btn-secondary" onclick="document.location.href='{{route('admin.[[component_name]].index')}}'">انصراف</button>
                                    <input type="hidden" id="stay_here" name="stay_here" />
                                    <button type="submit" class="btn btn-success" onclick="document.getElementById('stay_here').value='stay_here'">بروزرسانی</button>
                                    <button type="submit" class="btn btn-info">بروزرسانی و بازگشت</button>
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
        function deleteImage(){
            let c = window.confirm('از حذف تصویر مطمئن هستید؟');
            if(c){
                $('#upload_image').replaceWith($('#upload_image').val('').clone(true));
                document.getElementById('image_preview').style.display = 'none';
                document.getElementById('old_image').value = '';
                document.getElementById('image_preview').src = '';
                image_delete_link.style.display = 'none';
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