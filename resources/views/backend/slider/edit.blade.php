@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <form action="{{ route('slider.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>THÊM SLIDER</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Bảng điều khiển</a></li>
                                <li class="breadcrumb-item active">Thêm slider</li>
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
                                <button name="THEM" type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-save"></i> Save[Thêm]
                                </button>
                                <a class="btn btn-sm btn-info" href="{{ route('slider.index') }}">
                                    <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Tên slider</label>
                                    <input name="name" value="{{ old('name', $slider->name) }}" id="name"
                                        type="text" class="form-control " required placeholder="  ">
                                    @if ($errors->has('name'))
                                        <div class="text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="link">Link</label>
                                    <input name="link" value="{{ old('link', $slider->link) }}" id="link"
                                        type="text" class="form-control " required
                                        placeholder="vd: http://domain.com/index.php?option=page&cat=khuyen-mai">
                                    @if ($errors->has('link'))
                                        <div class="text-danger">
                                            {{ $errors->first('link') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row">

                                    <div class=" col-md-6 mb-3">
                                        <label for="position">Vị trí</label>
                                        <select name="position" id="position" class="form-control">
                                            <option value="slideshow">slideshow</option>
                                        </select>
                                    </div>


                                    <div class=" col-md-6 mb-3">
                                        <label for="sort_order">Vị trí</label>
                                        <select name="sort_order" id="sort_order" class="form-control">
                                            <option value="0">--chon vị trí--</option>
                                            <?= $html_sort_order ?>;
                                        </select>
                                        @if ($errors->has('sort_order'))
                                            <div class="text-danger">
                                                {{ $errors->first('sort_order') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="image">Hình ảnh</label>
                                        <input name="image" id="image" type="file" onchange="previewFile(this);"
                                            class="form-control btn-sm image-preview">
                                        @if ($slider->image)
                                            <img id="previewImg" class="mt-1" width="30%"
                                                src="{{ asset('images/slider/' . $slider->image) }}" alt="">
                                        @else
                                            <img id="previewImg" class="mt-1" width="30%"
                                                src="{{ asset('images/No-Image-Placeholder.svg.png') }}" alt="">
                                        @endif



                                        @if ($errors->has('image'))
                                            <div class="text-danger">
                                                {{ $errors->first('image') }}
                                            </div>
                                        @endif

                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="status">Trạng thái</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Xuất bản
                                            </option>
                                            <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Chưa xuất bản
                                            </option>

                                        </select>
                                    </div>
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

@endsection
