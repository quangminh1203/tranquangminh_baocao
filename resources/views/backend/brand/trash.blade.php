@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <form action="{{ route('brand.trashAll') }}" method="post" enctype="multipart/form-data">
        @csrf
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
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                 <button class="btn btn-sm btn-primary" type="submit" name="RESTORE_ALL">
                                    <i class="fa-solid fa-rotate-left"></i> Khôi phục đã chọn đã chọn
                                </button>
                                <button class="btn btn-sm btn-danger" type="submit" name="DELETE_ALL">
                                    <i class="fa-solid fa-trash-can"></i> Xóa đã chọn
                                </button>
                               
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ route('brand.index') }}">
                                        <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
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
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">image</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Tên danh mục</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Slug</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Chức năng</th>
                                    <th class="col-md-2 col-sm-1 col-2 align-middle text-center">Ngày tạo</th>
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">id</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_brand as $brand)
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <input type="checkbox" name="checkId[]" value="{{ $brand->id }}"
                                                    id="brandCheck{{ $brand->id }}" class="CheckItem">
                                            </div>
                                        </td>
                                        <td>
                                            @if ($brand->image)
                                                <img class="img-fluid"
                                                    style="width: 100px; height: 100px; object-fit: cover;"
                                                    src="{{ asset('images/brand/' . $brand->image) }}"
                                                    alt="User profile picture">
                                            @else
                                                <img class=" img-fluid "
                                                    style="width: 100px; height: 100px; object-fit: cover;"
                                                    src="{{ asset('images/No-Image-Placeholder.svg.png') }}" alt="">
                                            @endif
                                        </td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->slug }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('brand.show', ['brand' => $brand->id]) }}"
                                                class="btn btn-sm btn-primary" title="view">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                            <a href="{{ route('brand.restore', ['brand' => $brand->id]) }}"
                                                class="btn btn-sm btn-info" title="restore">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>
                                            <a href="{{ route('brand.destroy', ['brand' => $brand->id]) }}"
                                                class="btn btn-sm btn-danger" title="delete">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ $brand->created_at }}
                                        </td>
                                        <td class="text-center">{{ $brand->id }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Footer
                    </div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->

            </section>
        </div>
    </form>
@endsection
@section('footer')

@endsection
