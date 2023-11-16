@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <form action="{{ route('category.deleteAll') }}" method="post" enctype="multipart/form-data">
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
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-danger" type="submit" name="DELETE_ALL">
                                    <i class="fa-solid fa-trash-can"></i></i> Xóa đã chọn
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="text-right">
                                    <a class="btn btn-sm btn-success" href="{{ route('category.create') }}">
                                        <i class="fas fa-plus"></i> Thêm
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="{{ route('category.trash') }}">
                                        <i class="fas fa-trash" aria-hidden="true"></i> Thùng rác
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @includeIf('backend.messageAlert', ['some' => 'data'])
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">
                                        <div class="form-group select-all">
                                            <input type="checkbox" class="" name="checkAll" id="checkAll">
                                        </div>
                                    </th>
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">image</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Tên danh mục</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Slug</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Chức năng</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Ngày tạo</th>
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">id</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_category as $category)
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <input type="checkbox" name="checkId[]" value="{{ $category->id }}"
                                                    id="categoryCheck{{ $category->id }}" class="CheckItem">
                                            </div>
                                        </td>
                                        <td>
                                            @if ($category->image)
                                                <img class="img-fluid"
                                                    style="width: 100px; height: 100px; object-fit: cover;"
                                                    src="{{ asset('images/category/' . $category->image) }}"
                                                    alt="User profile picture">
                                            @else
                                                <img class=" img-fluid "
                                                    style="width: 100px; height: 100px; object-fit: cover;"
                                                    src="{{ asset('images/No-Image-Placeholder.svg.png') }}" alt="">
                                            @endif
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td class="text-center">
                                            @if ($category->status == 1)
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('category.status', ['category' => $category->id]) }}">
                                                    <i class="fas fa-toggle-on"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-danger"
                                                    href="{{ route('category.status', ['category' => $category->id]) }}">
                                                    <i class="fas fa-toggle-off"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('category.edit', ['category' => $category->id]) }}"
                                                class="btn btn-sm btn-info" title="edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="{{ route('category.show', ['category' => $category->id]) }}"
                                                class="btn btn-sm btn-primary" title="show">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                            <a href="{{ route('category.delete', ['category' => $category->id]) }}"
                                                class="btn btn-sm btn-danger" title="delete">
                                                <i class="fa-solid fa-delete-left"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ $category->created_at }}
                                        </td>
                                        <td class="text-center">{{ $category->id }}</td>
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
            <!-- /.content -->
        </div>
    </form>
@endsection
@section('footer')

@endsection
