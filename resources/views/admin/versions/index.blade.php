@extends('admin.layout')

@section('content')
    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">سیستم</a></li>
                <li class="breadcrumb-item active" aria-current="page">نسخه ها</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="form-group col-md-12"  style="padding:0px;text-align: right;">

                            <button type="button" disabled class="btn btn-info btn-icon-text mb-2 mb-md-0">
                                <i class="btn-icon-prepend" data-feather="refresh-cw"></i>
                                بروزرسانی نرم افزار
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

                    <div class="table-responsive">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#farsi" role="tab" aria-controls="farsi" aria-selected="true">
                                    نسخه ها
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content border border-top-0 p-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="farsi" role="tabpanel" aria-labelledby="tab1">
                            <h6 class="card-title">تغییرات و نسخه های نرم افزار </h6>
                            <div class="table-responsive" style="min-height:400px;">
                                <table class="table table-condensed table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>
                                            تاریخ انتشار
                                        </th>
                                        <th>
                                            نسخه
                                        </th>
                                        <th>
                                            توضیحات
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($versions as $version)
                                        <tr>
                                            <td>
                                                {{$version->created_at->jdate('j F Y')}}
                                            </td>
                                            <td>
                                                {{$version->name}}
                                            </td>
                                            <td>
                                                {{$version->value}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>

            </div>
        </div>
        {!! $versions->links() !!}
    </div>

@endsection
