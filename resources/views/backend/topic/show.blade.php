@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 style="text-transform: uppercase;">{{ $title ?? 'trang quản lý' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">

            <!-- Default box -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('topic.index') }}">
                                        <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                    </a>
                                    <a href="{{ route('topic.edit', ['topic' => $topic->id]) }}" class="btn btn-sm btn-info"
                                        title="edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('topic.delete', ['topic' => $topic->id]) }}"
                                        class="btn btn-sm btn-danger" title="delete">
                                        <i class="fa-solid fa-delete-left"></i>
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">

                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">


                                        <h3 class="profile-username text-center">{{ $topic->name }}</h3>

                                        <p class="text-muted text-center">id: {{ $topic->id }}</p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Số lượng bài viết</b> <a class="float-right">{{ $total }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                                <!-- About Me Box -->

                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-9">
                                <div class="cards">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#activity"
                                                    data-toggle="tab">Thông
                                                    tin khác</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#timeline"
                                                    data-toggle="tab">Timeline</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="#post_topic"
                                                    data-toggle="tab">Sản phẩm</a>
                                            </li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-bodys">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="activity">
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        <strong><i class="fas fa-pencil-alt mr-1"></i> Từ khóa tìm
                                                            kiếm</strong>
                                                        <p class="text-muted">
                                                            <span class="tag tag-danger">{{ $topic->metakey }}</span>
                                                        </p>
                                                        <hr>
                                                        <strong><i class="far fa-file-alt mr-1"></i> Mô tả</strong>
                                                        <p class="text-muted">{!! $topic->metadesc !!}</p>
                                                        <hr>
                                                        <strong><i class="fa-solid fa-list-ol"></i> Vị trí:
                                                            {{ $topic->sort_order }}</strong>

                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="timeline">
                                                <!-- The timeline -->
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        <strong>
                                                            <i class="fa-regular fa-calendar-plus"></i> Ngày tham gia:
                                                            <span class="text-muted">
                                                                {{ $topic->created_at }}
                                                            </span>
                                                        </strong>

                                                        <hr>
                                                        <strong>
                                                            <i class="fa-topics fa-creative-commons-by"></i> Người đăng:
                                                            <span class="text-muted">{{ $topic->created_name }}</span>
                                                        </strong>

                                                        <hr>
                                                        <strong>
                                                            <i class="fa-solid fa-calendar-check"></i> Ngay sửa cuối:
                                                            <span class="text-muted">{{ $topic->updated_at }}</span>
                                                        </strong>

                                                        <hr>
                                                        <strong>
                                                            <i class="fa-topics fa-creative-commons-by"></i> Người sửa cuối:
                                                            <span class="text-muted">{{ $topic->updated_name }}</span>
                                                        </strong>



                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="post_topic">
                                                <!-- The timeline -->
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        <table class="table table-bordered" id="dataTable">
                                                            <thead>
                                                                <tr class="text-center ">

                                                                    <th
                                                                        class="col-md-1 col-sm-1 col-1 align-middle text-center">
                                                                        image</th>
                                                                    <th
                                                                        class="col-md-7 col-sm-7 col-7 align-middle text-center">
                                                                        Tên bài viết</th>

                                                                    <th
                                                                        class="col-md-1 col-sm-1 col-1 align-middle text-center">
                                                                        id</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($post_topic as $post)
                                                                    <tr>
                                                                        <td width="20%">
                                                                            <img src="{{ asset('images/post/' . $post->image) }}"
                                                                                alt="" class="w-100">
                                                                        </td>
                                                                        <td>{{ $post->post_name }}</td>

                                                                        <td class="text-center">{{ $post->post_id }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>

                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card -->

        </section>
    </div>
@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $('.post-image-thumb').on('click', function() {
                var $image_element = $(this).find('img')
                $('.post-image').prop('src', $image_element.attr('src'))
                $('.post-image-thumb.active').removeClass('active')
                $(this).addClass('active')
            })
        })
    </script>

@endsection
