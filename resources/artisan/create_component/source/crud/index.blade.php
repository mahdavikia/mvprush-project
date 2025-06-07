<!--
 Created By: Melorain Component Maker 0.1
 [[date]]
 Component: [[component_name_controller]]
 index.blade.php
-->
@extends('admin.layout')
@if(Melorain::getAccess('[[component_name_controller]]Controller@active'))
@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@endif
@section('content')
<div class="example">
    <nav class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">محتوا</a></li>
            <li class="breadcrumb-item"><a href="#">[[component_title]]</a></li>
            <li class="breadcrumb-item active" aria-current="page">لیست [[component_title]] ها</li>
        </ol>
    </nav>
</div>
<!--

FILTERS

-->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <!--

                ERRORS DISPLAY

                -->

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

                <div class="row">
                    <div class="form-group col-md-12"  style="padding:0px;text-align: right;">
                        <form role="form"  action="{{ route('admin.[[component_name]].index') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="form-group col-md-2" >
                                <input type="text" name="filter_title" value="{{$filter_title}}" placeholder="جستجو" class="form-control">
                            </div>
                            <div class="form-group col-md-2" >
                                <select name="filter_category" class="form-select">
                                    <option value="0"  @if($filter_category == "0") selected @endif>همه</option>
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}"  @if ($filter_category == $category->id) selected @endif >{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2" >
                                <select name="filter_active" class="form-select">
                                    <option value="2"  @if($filter_active == "2") selected @endif>همه وضعیت ها</option>
                                    <option value="0" @if($filter_active == "0") selected @endif>غیرفعال</option>
                                    <option value="1" @if($filter_active == "1") selected @endif>فعال</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6"  style="text-align: left;">
                                <button type="submit" class="btn btn-secondary btn-icon-text mb-2 mb-md-0">
                                    <i class="btn-icon-prepend" data-feather="filter"></i>
                                    فیلتر
                                </button>
                                <button type="button" class="btn btn-info btn-icon-text mb-2 mb-md-0" onclick="document.location.href='{{ route('admin.[[component_name]].create') }}'">
                                <i class="btn-icon-prepend" data-feather="file-plus"></i>
                                جدید
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--

GRID LIST

-->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <h6 class="card-title">لیست</h6>
                </div>

                <div class="table-responsive"  style="min-height:400px;">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                        <tr>
                            <th>
                                عملیات
                            </th>
                            <th>
                                دسته بندی
                            </th>
                            <th>
                                عنوان
                            </th>
                            <th>
                                عنوان لاتین
                            </th>
                            <th>
                                نام
                            </th>
                            <th>
                                وضعیت
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($[[component_name]] as $[[component_name_single]])
                        <tr>
                            <td>
                                <div class="dropdown mb-2">
                                    <a type="button" id="dropdownMenuButton_{{$[[component_name_single]]->id}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$[[component_name_single]]->id}}">
                                        {{--                                            <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">مشاهده</span></a>--}}
                                        <a class="dropdown-item d-flex align-items-center" href="{{route('admin.[[component_name]].edit',$[[component_name_single]])}}"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">ویرایش</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_{{$[[component_name_single]]->id}}"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">حذف</span></a>

                                    </div>
                                </div>

                                <div class="modal fade" id="modal_{{$[[component_name_single]]->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <form class="me-0 w-full" action="{{ route('admin.[[component_name]].destroy',$[[component_name_single]]) }}" method="POST" >
                                    @csrf
                                    @method('DELETE')
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
                                                <button type="submit" class="btn btn-danger" >بله، حذف شود</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </td>
                            <td title="{{$[[component_name_single]]->content_cat->title_en ?? null}}">
                                {{$[[component_name_single]]->content_cat->title ?? null}}
                            </td>
                            <td>
                                <a href="{{route('admin.[[component_name]].edit',$[[component_name_single]])}}">{{$[[component_name_single]]->title}}</a>
                                @if(!is_null($[[component_name_single]]->image))
                                <a href="{{asset('uploads/[[component_name]]').'/'.$[[component_name_single]]->image}}" target="_blank" title="مشاهده عکس اصلی"><i class="fa fa-image"></i></a>
                                @endif
                            </td>
                            <td>
                                {{$[[component_name_single]]->title_en}}
                            </td>
                            <td>
                                {{$[[component_name_single]]->name}}
                            </td>
                            <td>
                                @if(Melorain::getAccess('[[component_name_controller]]Controller@active'))
                                <div class="form-check form-switch mb-2">
                                    <input type="checkbox" name="news_active" onchange="callActive({{$[[component_name_single]]->id}})" value="1" class="form-check-input" @if($[[component_name_single]]->active == '1') checked @endif id="formSwitch{{$[[component_name_single]]->id}}">
                                    <label class="form-check-label" for="formSwitch{{$[[component_name_single]]->id}}">فعال</label>
                                </div>
                                @else
                                <div class="form-check form-switch mb-2" title="دسترسی ندارید">
                                    <input type="checkbox" name="news_active" disabled class="form-check-input" @if($[[component_name_single]]->active == '1') checked @endif id="formSwitch{{$[[component_name_single]]->id}}">
                                    <label class="form-check-label" for="formSwitch{{$[[component_name_single]]->id}}">فعال</label>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{ $[[component_name]]->links() }}
</div>

@endsection
@if(Melorain::getAccess('[[component_name_controller]]Controller@active'))
@section('footer')

<script type="text/javascript">

        function callActive (id){
            let data;

            if(document.getElementById('formSwitch'+id).checked){
                data = {
                    id:id,
                    state:1,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
            } else {
                data = {
                    id:id,
                    state:0,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
            }

            $.post('{{route('admin.[[component_name]].active')}}',data,
                function(data, status){
                    if(data === '1' && status == 200){
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'با موفقیت تغییر کرد'
                        });
                    } else {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'با موفقیت تغییر کرد'
                        });
                    }
                });
        }

    </script>
@endsection
@endif