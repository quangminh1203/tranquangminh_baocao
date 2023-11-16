@extends('layouts.admin')
@section('title', $title ?? 'Trang Quản Lý')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 style="text-transform: uppercase;  ">{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('order.index') }}"
                                    style="text-transform: capitalize;">Tất cả đơn hàng</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->

        </section>

        <!-- Main content -->
        <form action="{{ route('order.status', ['order' => $order->id]) }}" method="GET">
            @csrf
            <section class="content">

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="d-inline-block">
                                    <a class="btn btn-sm btn-info" href="{{ route('order.index') }}">
                                        <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                    </a>
                                </div>
                                <div class="d-inline-block">
                                    <button class="btn btn-sm btn-primary" name="status" type="submit" value="xacnhan"
                                        @if ($order->status != 1 && $order->status != 0) @disabled(true) @endif>
                                        <i class="fa-solid fa-clipboard-check"></i>Xác nhận
                                        @if ($order->status == 1)
                                            <i class="fa-solid fa-circle-check fa-beat" style="color: #07e921;"></i>
                                        @endif
                                    </button>
                                </div>
                                <div class="d-inline-block">
                                    <button class="btn btn-sm btn-info" name="status" type="submit" value="donggoi"
                                        @if ($order->status != 2 && $order->status != 1) @disabled(true) @endif>
                                        <i class="fa-solid fa-box"></i> Đóng gói
                                        @if ($order->status == 2)
                                            <i class="fa-solid fa-circle-check fa-beat" style="color: #07e921;"></i>
                                        @endif
                                    </button>
                                </div>
                                <div class="d-inline-block">
                                    <button class="btn btn-sm btn-warning" name="status" type="submit" value="vanchuyen"
                                        @if ($order->status != 3 && $order->status != 2) @disabled(true) @endif>
                                        <i class="fa-solid fa-plane-up"></i> Vận chuyển
                                        @if ($order->status == 3)
                                            <i class="fa-solid fa-circle-check fa-beat" style="color: #07e921;"></i>
                                        @endif
                                    </button>
                                </div>
                                <div class="d-inline-block">

                                    <button class="btn btn-sm btn-success" name="status" type="submit" value="dagiao"
                                        @if ($order->status != 4 && $order->status != 3) @disabled(true) @endif>
                                        <i class="fa-solid fa-plane-circle-check"></i>Đã giao
                                        @if ($order->status == 4)
                                            <i class="fa-solid fa-circle-check fa-beat" style="color: #07e921;"></i>
                                        @endif
                                    </button>
                                </div>
                                <div class="d-inline-block">
                                    <button class="btn btn-sm btn-danger" name="status" type="submit" value="huy"
                                        @if ($order->status == 4) @disabled(true) @endif>
                                        <i class="fas fa-trash"></i> Hủy
                                        @if ($order->status == 5)
                                            <i class="fa-solid fa-circle-check fa-beat" style="color: #07e921;"></i>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @includeIf('backend.message_alert')
                        <div class="row">
                            <div class="col-3">
                                <h5 class="text-info">Thông tin khách hàng</h5>
                                <div>
                                    <h6><strong>Tên người nhận</strong></h6>
                                    <input type="text" class="form-control" disabled value="{{ $order->name }}">
                                </div>
                                <div class="mt-2">
                                    <h6><strong>Điện thoại</strong></h6>
                                    <input type="text" class="form-control" disabled value="{{ $order->phone }}">
                                </div>
                                <div class="mt-2">
                                    <h6><strong>Địa chỉ</strong></h6>
                                    <input type="text" class="form-control" disabled value="{{ $order->address }}">
                                </div>
                                <div class="mt-2">
                                    <h6><strong>Email</strong></h6>
                                    <input type="text" class="form-control" disabled value="{{ $order->email }}">
                                </div>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered" id="myTable">
                                    <h5 class="text-info">Chi tiết đơn hàng</h5>
                                    <thead>
                                        <tr>
                                            

                                            <th class="col-md-1 col-sm-1 col-1 align-middle text-center">
                                                <div class="form-group select-all">
                                                    <input type="checkbox" class="" name="checkAll"
                                                        id="checkAll">
                                                </div>
                                            </th>
                                            <th class="col-md-2 col-sm-1 col-1 align-middle text-center">image</th>
                                            <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Tên sản phẩm</th>
                                            <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Giá</th>
                                            <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Số lượng</th>
                                            <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Thành tiền</th>
                                            <th class="col-md-1 col-sm-1 col-1 align-middle text-center">id</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_orderdetail as $row)
                                            <tr>

                                                <td class="text-center" style="width:20px">
                                                    <div class="form-group">
                                                        <input name="checkId[]" type="checkbox"
                                                            value="{{ $row->id }}" id="web-developer">
                                                    </div>
                                                </td>
                                                <td>

                                                    <img src="{{ asset('images/product/' . $row->product->images[0]->image) }}"
                                                        class="img-fluid" alt="{{ $row->image }}">

                                                </td>
                                                <td>
                                                    {{ $row->product->name }}
                                                </td>
                                                <td>
                                                    {{ number_format($row->price) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $row->qty }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($row->amount) }}
                                                </td>

                                                <td class="text-center" style="width:20px">
                                                    {{ $row->id }}
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <td colspan="7">
                                        <h5 class="text-danger">Tổng tiền: {{ number_format($total) }} </h5>
                                    </td>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-sm btn-info" href="{{ route('order.index') }}">
                                    <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                </a>
                                <a class="btn btn-sm btn-primary"
                                    href="{{ route('order.edit', ['order' => $order->id]) }} ">
                                    <i class=" fas fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-danger"
                                    href="{{ route('order.delete', ['order' => $order->id]) }}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->

            </section>
        </form>
        <!-- /.content -->
    </div>

@endsection
