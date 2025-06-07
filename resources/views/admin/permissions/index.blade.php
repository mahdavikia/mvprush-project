@extends('admin.layout')

@section('content')
    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">مدیران سیستم</a></li>
                <li class="breadcrumb-item"><a href="{{route("admin.roles.index")}}">نقش ها</a></li>
                <li class="breadcrumb-item">{{$role->title}}</li>
                <li class="breadcrumb-item active" aria-current="page">دسترسی ها</li>
            </ol>
        </nav>
    </div>

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

                    <div class="row">
                        <div class="form-group col-md-12"  style="padding:0px;text-align: right;">
                            <button type="button" class="btn btn-info btn-icon-text mb-2 mb-md-0" onclick="document.location.href='{{route('admin.permissions.create',$role)}}'">
                                <i class="btn-icon-prepend" data-feather="file-plus"></i>
                                جدید
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <h6 class="card-title">لیست</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                        <tr>
                            <th>
                                عملیات
                            </th>
                            <th>
                                عنوان
                            </th>
                            <th>
                                مسیر
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <div class="dropdown mb-2">
                                        <a type="button" id="dropdownMenuButton_{{$permission->id}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$permission->id}}">

                                            <a class="dropdown-item d-flex align-items-center" href="{{route('admin.permissions.edit',[$role,$permission])}}"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">ویرایش</span></a>
                                            <a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_{{$permission->id}}"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">حذف</span></a>

                                        </div>
                                    </div>

                                    <div class="modal fade" id="modal_{{$permission->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <form class="me-0 w-full" action="{{ route('admin.permissions.destroy',[$role,$permission]) }}" method="POST" >
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
                                <td>
                                    <a href="{{route('admin.permissions.edit',[$role,$permission])}}">{{ $permission->title }}</a>
                                </td>
                                <td>
                                    {{ $permission->route }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>

            </div>
        </div>
            {{ $permissions->links() }}
    </div>

@endsection
