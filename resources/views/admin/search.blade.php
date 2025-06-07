@extends('admin.layout')

@section('content')
    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">محتوا</a></li>
                <li class="breadcrumb-item active" aria-current="page">جستجو</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-stripe">
                        <tr>
                            <th nowrap="nowrap" style="font-weight: bold">
                                نوع
                            </th>
                            <th style="width: 100%;font-weight: bold">
                                عنوان
                            </th>
                        </tr>
                        @foreach($news as $n)
                            <tr>
                                <td nowrap="nowrap">
                                    @if(is_null($n->expire_at))
                                        خبر
                                    @endif
                                    @if(!is_null($n->expire_at))
                                        رویداد
                                    @endif
                                </td>
                                <td style="width: 100%">
                                    <a href="{{route('admin.news.edit',$n)}}">
                                        {{$n->title}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($contents as $c)
                            <tr>
                                <td nowrap="nowrap">
                                    صفحه/محتوا
                                </td>
                                <td style="width: 100%">
                                    <a href="{{route('admin.contents.edit',$c)}}">
                                        {{$c->title}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($podcasts as $p)
                            <tr>
                                <td nowrap="nowrap">
                                    وبلاگ
                                </td>
                                <td style="width: 100%">
                                    <a href="{{route('admin.podcasts.edit',$p)}}">
                                        {{$p->title}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($books as $b)
                            <tr>
                                <td nowrap="nowrap">
                                    کتاب
                                </td>
                                <td style="width: 100%">
                                    <a href="{{route('admin.books.edit',$b)}}">
                                        {{$b->title}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($menus as $m)
                            <tr>
                                <td nowrap="nowrap">
                                    منو
                                </td>
                                <td style="width: 100%">
                                    <a href="{{route('admin.menus.edit',$m)}}">
                                        {{$m->title}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($galleries as $g)
                            <tr>
                                <td nowrap="nowrap">
                                    گالری
                                </td>
                                <td style="width: 100%">
                                    <a href="{{route('admin.galleries.edit',$g)}}">
                                        {{$g->title}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')
    <script type="text/javascript">
        function check_search(){
            const s = $('#filter_title').val();
            if(s === ""){
                return false;
            }
        }
        document.getElementById('filter_title').setAttribute("value", "{{$filter_title}}");
    </script>
@endsection
