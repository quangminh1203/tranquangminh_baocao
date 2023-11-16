@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <form action="{{ route('slider.deleteAll') }}" method="post" enctype="multipart/form-data">
        @csrf

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
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-sm btn-danger" type="submit" name="DELETE_ALL">
                                        <i class="fas fa-trash"></i> Xóa đã chọn
                                    </button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="text-right">
                                        <a class="btn btn-sm btn-success" href="{{ route('slider.create') }}">
                                            <i class="fas fa-plus"></i> Thêm
                                        </a>
                                        <a class="btn btn-sm btn-danger" href="{{ route('slider.trash') }}">
                                            <i class="fas fa-trash"></i> Thùng rác
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @includeIf('backend.messageAlert', ['some' => 'data'])
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="col-md-1 col-sm-1 col-1 align-middle text-center">
                                            <div class="form-group select-all">
                                                <input type="checkbox" class="" name="checkAll" id="checkAll">
                                            </div>
                                        </th>
                                        <th class="text-center col-md-1">Hình</th>
                                        <th class="text-center col-md-2">Tên slider</th>
                                        <th class="text-center col-md-2">Link</th>
                                        <th class="text-center col-md-2">Ngày tạo</th>
                                        <th class="text-center col-md-2">Chức năng</th>
                                        <th class="text-center col-md-1">ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_slider as $slider)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-group">
                                                    <input type="checkbox" name="checkId[]" value="{{ $slider->id }}"
                                                        id="sliderCheck{{ $slider->id }}" class="CheckItem">
                                                </div>
                                            </td>
                                            <td class="index-img">
                                                <img src="{{ asset('images/slider/' . $slider->image) }}"
                                                    class="card-img-top index-img" alt="{{ $slider['image'] }}">
                                            </td>
                                            <td>
                                                {{ $slider['name'] }}
                                            </td>
                                            <td>
                                                {{ $slider['link'] }}
                                            </td>

                                            <td class="text-center">
                                                {{ $slider['created_at'] }}
                                            </td>
                                            <td class="text-center">
                                                @if ($slider['status'] == 1)
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('slider.status', ['slider' => $slider->id]) }}">
                                                        <i class="fas fa-toggle-on"></i>
                                                    </a>
                                                @else
                                                    <a class="btn btn-sm btn-danger"
                                                        href="{{ route('slider.status', ['slider' => $slider->id]) }}">
                                                        <i class="fas fa-toggle-off"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('slider.edit', ['slider' => $slider->id]) }}"
                                                    class="btn btn-sm btn-info" title="edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="{{ route('slider.show', ['slider' => $slider->id]) }}"
                                                    class="btn btn-sm btn-primary" title="view">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                                <a href="{{ route('slider.delete', ['slider' => $slider->id]) }}"
                                                    class="btn btn-sm btn-danger" title="delete">
                                                    <i class="fa-solid fa-delete-left"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ $slider['id'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">

                            </div>
                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Footer
                    </div>
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
