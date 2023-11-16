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
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-sm btn-danger" type="submit" name="DELETE_ALL">
                                <i class="fa-solid fa-trash-can"></i> Xóa đã chọn
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="text-right">
                                <a class="btn btn-sm btn-success" href="{{ route('customer.create') }}">
                                    <i class="fas fa-plus"></i> Thêm
                                </a>
                                <a class="btn btn-sm btn-danger" href="{{ route('customer.trash') }}">
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
                                    <input type="checkbox" name="" id="">
                                </th>
                                <th class="col-md-1 col-sm-1 col-1 align-middle text-center">image</th>
                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Tên</th>
                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Email</th>
                                <th class="col-md-1 col-sm-1 col-1 align-middle text-center">Phone</th>

                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Chức năng</th>
                                <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Ngày tham gia</th>
                                <th class="col-md-1 col-sm-1 col-1 align-middle text-center">id</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_customer as $customer)
                                <tr>
                                    <td class="text-center"> <input type="checkbox" name="" id=""></td>
                                    <td>

                                        @if ($customer->image == null)
                                            @if ($customer->gender == 0)
                                                <img src="{{ asset('images/user/male.png') }}"
                                                    style="width: 100px; height: 100px; object-fit: cover;
                                                    class="card-img-top
                                                    index-img" alt="male">
                                            @else
                                                <img src="{{ asset('images/user/Female.png') }}"
                                                    style="width: 100px; height: 100px; object-fit: cover;
                                                    class="card-img-top
                                                    index-img" alt="Female">
                                            @endif
                                        @else
                                            <img src="{{ asset('images/user/' . $customer->image) }}" alt=""
                                                style="width: 100px; height: 100px; object-fit: cover;
                                                class="w-100">
                                        @endif
                                    </td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>

                                    <td class="text-center">
                                        @if ($customer->status == 1)
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('customer.status', ['customer' => $customer->id]) }}">
                                                <i class="fas fa-toggle-on"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-sm btn-danger"
                                                href="{{ route('customer.status', ['customer' => $customer->id]) }}">
                                                <i class="fas fa-toggle-off"></i>
                                            </a>
                                        @endif


                                        <a href="{{ route('customer.edit', ['customer' => $customer->id]) }}"
                                            class="btn btn-sm btn-info" title="edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="{{ route('customer.show', ['customer' => $customer->id]) }}"
                                            class="btn btn-sm btn-primary" title="view">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customer.delete', ['customer' => $customer->id]) }}"
                                            class="btn btn-sm btn-danger" title="delete">
                                            <i class="fa-solid fa-delete-left"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ $customer->created_at }}
                                    </td>
                                    <td class="text-center">{{ $customer->id }}</td>
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
@endsection
@section('footer')

@endsection
