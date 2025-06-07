@extends('admin.layout')
@if(Melorain::getAccess('UserController@active'))
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
                <li class="breadcrumb-item active" aria-current="page">لیست مدیران</li>
            </ol>
        </nav>
    </div>

    @if($message = Session::get('error') || $message = Session::get('success'))
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if ($message = Session::get('error'))
                        <div class="note note-danger">
                            <strong>خطا!</strong>
                            <br>
                            {{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="note note-success">
                            {{ $message }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    @endif


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
                                        نقش
                                    </th>
                                    <th>
                                        نام و نام خانوادگی
                                    </th>
                                    <th>
                                        ایمیل
                                    </th>
                                    <th>
                                        تاریخ ایجاد
                                    </th>
                                    <th>
                                        وضعیت
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="dropdown mb-2">
                                            <a type="button" id="dropdownMenuButton_{{$user->id}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$user->id}}">
                                                {{--                                            <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">مشاهده</span></a>--}}
                                                <a class="dropdown-item d-flex align-items-center" href="{{route('admin.users.edit',$user)}}"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">ویرایش</span></a>
                                                @if(Auth::user()->role_id == 1){
                                                <a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_{{$user->id}}"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">حذف</span></a>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="modal fade" id="modal_{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form class="me-0 w-full" action="{{ route('admin.users.destroy',$user) }}" method="POST" >
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
                                        {{$user->role->title}}
                                    </td>
                                    <td>
                                        <a href="{{route('admin.users.edit',$user)}}">{{$user->profile->full_name}}</a>
                                    </td>
                                    <td>
                                        {{$user->email}}
                                    </td>
                                    <td>
                                        {{$user->created_at->jdate('Y/m/d')}}
                                    </td>
                                    <td>
                                        @if(Melorain::getAccess('UserController@active'))
                                            <div class="form-check form-switch mb-2">
                                                <input type="checkbox" name="news_active" onchange="callActive({{$user->id}})" value="1" class="form-check-input" @if($user->activated == '1') checked @endif id="formSwitch{{$user->id}}">
                                                <label class="form-check-label" for="formSwitch{{$user->id}}">فعال</label>
                                            </div>
                                        @else
                                            <div class="form-check form-switch mb-2" title="دسترسی ندارید">
                                                <input type="checkbox" name="news_active" disabled class="form-check-input" @if($user->activated == '1') checked @endif id="formSwitch{{$user->id}}">
                                                <label class="form-check-label" for="formSwitch{{$user->id}}">فعال</label>
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
        {{ $users->links() }}
        </div>
@endsection
@if(Melorain::getAccess('UserController@active'))
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

            $.post('{{route('admin.users.active')}}',data,
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