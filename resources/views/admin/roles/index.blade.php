@extends('admin.layout')
@if(Melorain::getAccess('RoleController@active'))
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@endif
@section('content')
    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">مدیران سیستم</a></li>
                <li class="breadcrumb-item active" aria-current="page">نقش ها</li>
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
                            <button type="button" class="btn btn-info btn-icon-text mb-2 mb-md-0" onclick="document.location.href='{{ route('admin.roles.create') }}'">
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

                    <div class="table-responsive" style="min-height:400px;">
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
                                    نام
                                </th>
                                <th>
                                    وضعیت
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>

                                        <div class="dropdown mb-2">
                                            <a type="button" id="dropdownMenuButton_{{$role->id}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$role->id}}">
                                                <a class="dropdown-item d-flex align-items-center" href="{{route('admin.permissions.index',$role)}}"><i data-feather="lock" class="icon-sm me-2"></i> <span class="">دسترسی ها</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="{{route('admin.roles.edit',$role)}}"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">ویرایش</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_{{$role->id}}"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">حذف</span></a>

                                            </div>
                                        </div>

                                        <div class="modal fade" id="modal_{{$role->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form class="me-0 w-full" action="{{ route('admin.roles.destroy',$role) }}" method="POST" >
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
                                        <a href="{{route('admin.permissions.index',$role)}}">{{$role->title}}</a>
                                    </td>
                                    <td>
                                        {{$role->name}}
                                    </td>
                                    <td>
                                        @if(Melorain::getAccess('RoleController@active'))
                                            <div class="form-check form-switch mb-2">
                                                <input type="checkbox" name="news_active" onchange="callActive({{$role->id}})" value="1" class="form-check-input" @if($role->active == '1') checked @endif id="formSwitch{{$role->id}}">
                                                <label class="form-check-label" for="formSwitch{{$role->id}}">فعال</label>
                                            </div>
                                        @else
                                            <div class="form-check form-switch mb-2" title="دسترسی ندارید">
                                                <input type="checkbox" name="news_active" disabled class="form-check-input" @if($role->active == '1') checked @endif id="formSwitch{{$role->id}}">
                                                <label class="form-check-label" for="formSwitch{{$role->id}}">فعال</label>
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
        {{ $roles->links() }}
    </div>


@endsection
@if(Melorain::getAccess('RoleController@active'))
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

            $.post('{{route('admin.roles.active')}}',data,
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
