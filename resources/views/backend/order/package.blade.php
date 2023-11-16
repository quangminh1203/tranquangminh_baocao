@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')


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
                    <h3 class="card-title">Title</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @includeIf('backend.messageAlert', ['some' => 'data'])
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr class="text-center ">
                                <th class="col-md-1 col-sm-1 col-1 align-middle text-center">
                                    <div class="form-group select-all">
                                        <input type="checkbox">
                                    </div>
                                </th>
                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Khách
                                    hàng</th>
                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Email
                                </th>
                                <th class="col-md-1 col-sm-1 col-1 align-middle text-center">Phone
                                </th>
                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Ngày
                                    tạo</th>
                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Trạng
                                    thái</th>
                                <th class="col-md-1 col-sm-1 col-1 align-middle text-center">Chức
                                    năng</th>

                                <th class="col-md-1 col-sm-1 col-1 align-middle text-center">id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list_order as $order) : ?>

                            <tr>
                                <td class="text-center">
                                    <div class="form-group">
                                        <input type="checkbox" name="checkId[]" value="{{ $order->id }}">
                                    </div>
                                </td>
                                <td>
                                    {{ $order->user_name }}
                                </td>
                                <td>
                                    {{ $order->user_email }}
                                </td>
                                <td>
                                    {{ $order->user_phone }}
                                </td>

                                <td class="text-center">
                                    {{ $order->created_at }}
                                </td>
                                <td>
                                    <span class="btn btn-sm btn-{{ $list_status[$order['status']]['type'] }}">
                                        {{ $list_status[$order['status']]['text'] }}
                                    </span>
                                </td>
                                <td class=" text-center">

                                    <a href="{{ route('order.show', ['order' => $order->id]) }}"
                                        class="btn btn-sm btn-primary" title="show">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    <a href="{{ route('order.delete', ['order' => $order->id]) }}"
                                        class="btn btn-sm btn-danger" title="delete">
                                        <i class="fa-solid fa-delete-left"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{ $order->id }}
                                </td>
                            </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- /.card-body -->
                    <!-- /.card -->

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
@endsection
@section('footer')

@endsection
