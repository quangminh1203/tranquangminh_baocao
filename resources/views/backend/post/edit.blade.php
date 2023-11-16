@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <form action="{{ route('post.update', ['post' => $post->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 style="text-transform: uppercase;">{{ $title ?? 'trang quản lý' }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                                <li class="breadcrumb-item active">Blank Page</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">

                            <div class="col-md-12 text-right">
                                <div class="text-right">
                                    <button name="THEM" type="submit" class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-floppy-disk"></i> Lưu[update]
                                    </button>
                                    <a class="btn btn-sm btn-info" href="{{ route('post.index') }}">
                                        <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                    </a>


                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label for="title">Tên danh mục</label>
                                    <input name="title" id="title" type="text" class="form-control "
                                        value="{{ old('title', $post->title) }}" placeholder="  ">
                                    @if ($errors->has('title'))
                                        <div class="text-danger">
                                            {{ $errors->first('title') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="detail">Nội dung</label>
                                    <textarea name="detail" id="detail" cols="10" rows="2" class="form-control "
                                        placeholder="vd: là một thể loại văn xuôi có hư cấu, thông qua nhân vật, ">{{ old('detail', $post->detail) }}</textarea>
                                    @if ($errors->has('detail'))
                                        <div class="text-danger">
                                            {{ $errors->first('detail') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="metadesc">Mô tả</label>
                                    <textarea name="metadesc" id="metadesc" cols="10" rows="2" class="form-control "
                                        placeholder="vd: là một thể loại văn xuôi có hư cấu,...">{{ old('metadesc', $post->metadesc) }}</textarea>
                                    @if ($errors->has('metadesc'))
                                        <div class="text-danger">
                                            {{ $errors->first('metadesc') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="metakey">Từ khóa</label>
                                    <textarea name="metakey" id="metakey" cols="10" rows="2" class="form-control " placeholder=" ">{{ old('metakey', $post->metakey) }}</textarea>
                                    @if ($errors->has('metakey'))
                                        <div class="text-danger">
                                            {{ $errors->first('metakey') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="topic_id">Chủ đề cha</label>
                                    <select name="topic_id" id="topic_id" class="form-control">
                                        <option value="">--chon chủ đề--</option>
                                        {!! $html_topic_id !!}
                                    </select>
                                    @if ($errors->has('topic_id'))
                                        <div class="text-danger">
                                            {{ $errors->first('topic_id') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="image">Hình ảnh</label>
                                    <input name="image" id="image" type="file" onchange="previewFile(this);"
                                        class="form-control btn-sm image-preview">
                                    <img id="previewImg" class="mt-1" width="30%"
                                        src="{{ asset('images/post/' . $post->image) }}" alt="">
                                    @if ($errors->has('image'))
                                        <div class="text-danger">
                                            {{ $errors->first('image') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="status" class="form-control">

                                        <option value="1" {{ $post->status == 1 ? 'selected' : '' }}>Xuất bản</option>
                                        <option value="2" {{ $post->status == 2 ? 'selected' : '' }}>Chưa xuất bản
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
    </form>
@endsection
@section('footer')
    <script>
        CKEDITOR.replace('detail')
    </script>
@endsection
