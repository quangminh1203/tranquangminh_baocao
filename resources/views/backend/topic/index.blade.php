@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <form action="{{ route('topic.deleteAll') }}" method="post" enctype="multipart/form-data">
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
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-danger" type="submit" name="DELETE_ALL">
                                    <i class="fa-solid fa-trash-can"></i></i> Xóa đã chọn
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="text-right">
                                    <a class="btn btn-sm btn-success" href="{{ route('topic.create') }}">
                                        <i class="fas fa-plus"></i> Thêm
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="{{ route('topic.trash') }}">
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
                                <tr>
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">
                                        <div class="form-group select-all">
                                            <input type="checkbox" class=""  name="checkAll" id="checkAll">
                                        </div>
                                    </th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Tên chủ đề</th>
                                    <th class="col-md-3 col-sm-2 col-2 align-middle text-center">Từ khóa</th>

                                    <th class="col-md-3 col-sm-1 col-2 align-middle text-center">Ngày tạo</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Chức năng</th>
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">id</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_topic as $topic)
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <input type="checkbox" name="checkId[]" value="{{ $topic->id }}"
                                                    id="topicCheck{{ $topic->id }}" class="CheckItem">
                                            </div>
                                        </td>

                                        <td>{{ $topic->name }}</td>
                                        <td>{{ $topic->metakey }}</td>

                                        <td class="text-center">
                                            {{ $topic->created_at }}
                                        </td>
                                        <td class="text-center">
                                            @if ($topic->status == 1)
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('topic.status', ['topic' => $topic->id]) }}">
                                                    <i class="fas fa-toggle-on"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-danger"
                                                    href="{{ route('topic.status', ['topic' => $topic->id]) }}">
                                                    <i class="fas fa-toggle-off"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('topic.edit', ['topic' => $topic->id]) }}"
                                                class="btn btn-sm btn-info" title="edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="{{ route('topic.show', ['topic' => $topic->id]) }}"
                                                class="btn btn-sm btn-primary" title="view">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                            <a href="{{ route('topic.delete', ['topic' => $topic->id]) }}"
                                                class="btn btn-sm btn-danger" title="delete">
                                                <i class="fa-solid fa-delete-left"></i>
                                            </a>
                                        </td>

                                        <td class="text-center">{{ $topic->id }}</td>
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
