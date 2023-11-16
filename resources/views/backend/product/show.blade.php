@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/flexslider.css') }}" type="text/css" media="screen" />
    <style>
        .parent-div {

            display: flex;
            align-items: center;
        }

        .parent-div img {
            margin: auto 0px;
            
            /* căn giữa ảnh theo chiều ngang */
        }

        .carousel-item {
            opacity: 0.3;
            border: 2px solid transparent;
            border-color: #28a745;
            transition: border-color 0.2s ease-in-out;
        }

        
        .flex-active-slide .carousel-item  {
            border-color: #28a745;;
            opacity: 1;
            transition:opacity 1s  ;
        }
        
    </style>
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection
@section('content')
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
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('product.index') }}">
                                        <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                    </a>
                                    <a href="{{ route('product.edit', ['product' => $product->id]) }}"
                                        class="btn btn-sm btn-info" title="edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('product.delete', ['product' => $product->id]) }}"
                                        class="btn btn-sm btn-danger" title="delete">
                                        <i class="fa-solid fa-delete-left"></i>
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="card-body">

                        <div class="row product">
                            <div class="col-md-5">
                                <div id="main" role="main">
                                    <section class="slider">
                                        <div id="slider" class="flexslider border border-1 border-success ">
                                            <ul class="slides parent-div">
                                                @foreach ($list_image as $item)
                                                    <li>
                                                        <img src="{{ asset('images/product/' . $item->image) }}"
                                                            alt="">
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div id="carousel" class="flexslider">
                                            <ul class="slides">

                                                @foreach ($list_image as $item)
                                                    <li>
                                                        <img src="{{ asset('images/product/' . $item->image) }}"
                                                            alt="" class="carousel-item">
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </section>

                                </div>

                            </div>
                            <!-- /.col -->
                            <div class="col-md-7">
                                <div class="cards">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#activity"
                                                    data-toggle="tab">Thông
                                                    tin cơ bản</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#sale" data-toggle="tab">Khuyến
                                                    mãi</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="#store" data-toggle="tab">Kho
                                                    hàng</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="#detail-tab"
                                                    data-toggle="tab">Thông
                                                    tin chi tiết</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="#metadesc-tab"
                                                    data-toggle="tab">Mô Tả
                                                    Sản Phẩm</a>
                                            </li>
                                        </ul>

                                    </div><!-- /.card-header -->
                                    <div class="card-bodys">
                                        <div class="tab-content">
                                            <h2 class="product__name">{{ $product->name }}</h2>
                                            <div class="active tab-pane" id="activity">
                                                <div class="card card-primary">
                                                    <div class="card-body">

                                                        <ul class="list-group list-group-unbordered mb-3">
                                                            <li class="list-group-item">
                                                                <b>mã sản phẩm</b> <a
                                                                    class="float-right">{{ $product->id }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Danh mục</b> <a
                                                                    class="float-right">{{ $product->category_name }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>thương hiệu</b> <a
                                                                    class="float-right">{{ $product->brand_name }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Giá bán</b> <a
                                                                    class="float-right">{{ $product->price_buy }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Ngày nhập</b> <a class="float-right">
                                                                    {{ $product->created_at }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Người nhập</b> <a class="float-right">
                                                                    {{ $product->created_name }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Ngày cập nhật cuối</b> <a class="float-right">
                                                                    {{ $product->updated_at }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Người cập nhật</b> <a class="float-right">
                                                                    {{ $product->created_name }}</a>
                                                            </li>


                                                        </ul>

                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>

                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="sale">
                                                <!-- The sale -->
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        <ul class="list-group list-group-unbordered mb-3">

                                                            <li class="list-group-item">
                                                                <b>Giá Khuyến mãi</b> <a
                                                                    class="float-right">{{ $product->price_sale }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Ngày bắt đầu khuyến mãi</b> <a class="float-right">
                                                                    {{ $product->date_begin }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Ngày kết thúc khuyến mãi</b> <a class="float-right">
                                                                    {{ $product->date_end }}</a>
                                                            </li>

                                                        </ul>


                                                    </div>

                                                </div>
                                            </div>
                                            <!-- /.The sale -->
                                            <div class="tab-pane" id="store">
                                                <!-- The sale -->
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        <ul class="list-group list-group-unbordered mb-3">

                                                            <li class="list-group-item">
                                                                <b>Giá gốc</b> <a
                                                                    class="float-right">{{ $product->price }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Số lượng</b> <a class="float-right">
                                                                    {{ $product->qty }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Người nhập</b> <a class="float-right">
                                                                    {{ $product->created_name_ps }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Ngày nhập</b> <a class="float-right">
                                                                    {{-- {{ $product_store->created_at }} --}}
                                                                    {{ $product->created_at_ps }}</a>
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Người cập nhật</b> <a class="float-right">
                                                                    {{ $product->updated_name_ps }}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Ngày cập nhật</b> <a class="float-right">
                                                                    {{ $product->updated_category_ps }}</a>
                                                                </a>
                                                            </li>
                                                        </ul>


                                                    </div>

                                                </div>
                                            </div>
                                            <!-- /.The sale -->
                                            <!-- /.detail-tab -->
                                            <div class="tab-pane" id="detail-tab">
                                                <!-- The sale -->
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        {!! $product->detail !!}
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- /.detail-tab -->
                                            <!-- /.detail-tab -->
                                            <div class="tab-pane" id="metadesc-tab">
                                                <!-- The sale -->
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        {!! $product->metadesc !!}
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- /.detail-tab -->

                                        </div>
                                        <!-- /.tab-content -->

                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>

                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card -->

        </section>
    </div>
@endsection
@section('footer')
    <!-- jQuery -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script>
        window.jQuery ||
            document.write('<script src="js/libs/jquery-1.7.min.js">\x3C/script>');
    </script>

    <!-- FlexSlider ẩn hiện -->

    <script defer src="{{ asset('js/jquery.flexslider.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            SyntaxHighlighter.all();
        });
        $(window).load(function() {
            $("#carousel").flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                itemWidth: 100,

                itemMargin: 5,
                asNavFor: "#slider",

            });

            $("#slider").flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                sync: "#carousel",
                start: function(slider) {
                    $("body").removeClass("loading");

                    $("#carousel img").css({
                        "padding": "2px",
                        "height": "100px",
                        "width": "100px",
                        "object-fit": "cover"
                    });
                },
            });
        });
    </script>

@endsection
