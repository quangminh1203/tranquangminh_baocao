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
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('user.index') }}">
                                        <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                    </a>

                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">

                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center mb-3">
                                            @if ($customer->image)
                                                <img style="object-fit: cover; width: 100px; height: 100px;"
                                                    class="profile-user-img img-fluid img-circle"
                                                    src="{{ asset('images/user/' . $customer->image) }}"
                                                    alt="User profile picture">
                                            @else
                                                <img class="profile-user-img img-fluid img-circle"
                                                    style="object-fit: cover; width: 100px; height: 100px; object-position: 50% 50%;"
                                                    src="{{ asset('images/No-Image-Placeholder.svg.png') }}" alt="">
                                            @endif
                                        </div>

                                        <h3 class="profile-username text-center mb-2">{{ $customer->name }}</h3>

                                        <p class="text-muted text-center mb-2">id: {{ $customer->id }}</p>

                                        <div class="row mb-3 mx-auto">
                                            <div class="col-md-6">
                                                <table style="width:100%">
                                                    <tr>

                                                        <th rowspan="2"
                                                            style="height: 50px; width: 50px; background-color: #a8e1b2"
                                                            class="text-center ">


                                                            <img src="{{ asset('images/shopping-cart-148959.svg') }}"
                                                                alt="" width="1em" height="1em"
                                                                viewBox="0 0 24 24"
                                                                style="font-size: 22px; height: 22px; width: 22px;">

                                                        </th>
                                                        <td>555-1234</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Số sản phẩm</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table style="width:100%">
                                                    <tr>
                                                        <th rowspan="2"
                                                            style="height: 50px; width: 50px; background-color: #a8e1b2"
                                                            class="text-center ">


                                                            <img src="{{ asset('images/check-40319.svg') }}" alt=""
                                                                width="1em" height="1em" viewBox="0 0 24 24"
                                                                style="font-size: 22px; height: 22px; width: 22px;">

                                                        </th>
                                                        <td>555-1234</td>
                                                    </tr>
                                                    <tr>

                                                        <td>Số sản phẩm</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <h3>Detail</h3>
                                        <hr>
                                        <div class="box-profile-">
                                            <h6 class="text-md">
                                                Username:
                                                <span class="text-muted">{{ $customer->username }}</span>
                                            </h6>
                                        </div>
                                        <div class="box-profile-">
                                            <h6 class="text-md">
                                                Email:
                                                <span class="text-muted">{{ $customer->email }}</span>
                                            </h6>
                                        </div>
                                        <div class="box-profile-">
                                            <h6 class="text-md">
                                                Status:

                                                @if ($customer->status == 1)
                                                    <span style="background-color: #a8e1b2">
                                                        <span class="p-2 text-success">Đang hoạt động</span>
                                                    </span>
                                                @else
                                                    <span style="background-color: #dc8686">
                                                        <span class="text-danger">Ngừng hoạt động</span>
                                                    </span>
                                                @endif

                                            </h6>
                                        </div>
                                        <div class="box-profile-">
                                            <h6 class="text-md">
                                                Gender:

                                                @if ($customer->gender == 0)
                                                    <span class="text-muted">Nam</span>
                                                @else
                                                    <span class="text-muted">Nữ</span>
                                                @endif

                                            </h6>
                                        </div>
                                        <div class="box-profile-">
                                            <h6 class="text-md">
                                                Role:
                                                <span class="text-muted">{{ $customer->roles }}</span>
                                            </h6>
                                        </div>
                                        <div class="box-profile-">
                                            <h6 class="text-md">
                                                Phone:
                                                <span class="text-muted">{{ $customer->phone }}</span>
                                            </h6>
                                        </div>
                                        <div class="box-profile-">
                                            <h6 class="text-md">
                                                Address:
                                                <span class="text-muted">{{ $customer->address }}</span>
                                            </h6>
                                        </div>
                                        <hr>
                                        {{-- <div class="row mt-3">
                                            <div class="col-md-6 text-center">
                                                <a href="{{ route('user.edit', ['user' => $customer->id]) }}"
                                                    class="btn btn-md btn-info" title="edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    Edit
                                                </a>

                                            </div> --}}
                                        <div class="col-md-6">
                                            <a href="{{ route('user.delete', ['user' => $customer->id]) }}"
                                                class="btn btn-md btn-danger" title="delete">
                                                <i class="fa-solid fa-delete-left"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- About Me Box -->

                            <!-- /.card -->

                            <!-- /.col -->
                            <div class="col-md-7">
                                <div class="cards">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#activity"
                                                    data-toggle="tab">Thông
                                                    tin khác</a></li>

                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-bodys">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="activity">
                                                <div class="card card-primary">
                                                    <div class="card-body">


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
                        </div>
                        <!-- /.col -->
                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.card -->

    </div>
    </section>
@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $('.product-image-thumb').on('click', function() {
                var $image_element = $(this).find('img')
                $('.product-image').prop('src', $image_element.attr('src'))
                $('.product-image-thumb.active').removeClass('active')
                $(this).addClass('active')
            })
        })
    </script>

@endsection
