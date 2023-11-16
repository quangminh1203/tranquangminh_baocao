@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')
<link rel="stylesheet" href="{{asset('css/images.css')  }}">
@endsection
@section('content')
    <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
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

                            <div class="col-md-12 text-right">
                                <div class="text-right">
                                    <button name="THEM" type="submit" class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-floppy-disk"></i> Lưu
                                    </button>
                                    <a class="btn btn-sm btn-info" href="{{ route('product.index') }}">
                                        <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                    </a>


                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="product_info-tab" data-toggle="tab"
                                    data-target="#product_info" type="button" role="tab" aria-controls="product_info"
                                    aria-selected="true">Thông tin sản phẩm</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="product_detail-tab" data-toggle="tab"
                                    data-target="#product_detail" type="button" role="tab" aria-controls="product_detail"
                                    aria-selected="true">mô tả sản phẩm</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="product_image-tab" data-toggle="tab"
                                    data-target="#product_image" type="button" role="tab" aria-controls="product_image"
                                    aria-selected="true">Hình ảnh</button>
                            </li>
                            
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="product_sale-tab" data-toggle="tab"
                                    data-target="#product_sale" type="button" role="tab" aria-controls="product_sale"
                                    aria-selected="true">Khuyến mãi</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="product_store-tab" data-toggle="tab"
                                    data-target="#product_store" type="button" role="tab" aria-controls="product_store"
                                    aria-selected="true">Nhập sản phẩm</button>
                            </li>


                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active border-right border-bottom border-left p-3 "
                                id="product_info" role="tabpanel" aria-labelledby="product_info-tab">
                                @includeIf('backend.product.create.tab_product_info', ['some' => 'data'])
                            </div>
                            <div class="tab-pane fade show  border-right border-bottom border-left p-3 " id="product_detail"
                                role="tabpanel" aria-labelledby="product_detail-tab">
                                @includeIf('backend.product.create.tab_product_detail', ['some' => 'data'])
                            </div>
                            <div class="tab-pane fade show  border-right border-bottom border-left p-3 " id="product_image"
                                role="tabpanel" aria-labelledby="product_image-tab">
                                @includeIf('backend.product.create.tab_product_image', ['some' => 'data'])
                            </div>
                            <div class="tab-pane fade show  border-right border-bottom border-left p-3 "
                                id="product_atribute" role="tabpanel" aria-labelledby="product_atribute-tab">
                                @includeIf('backend.product.create.tab_product_atribute', ['some' => 'data'])
                            </div>
                            <div class="tab-pane fade show  border-right border-bottom border-left p-3 " id="product_sale"
                                role="tabpanel" aria-labelledby="product_sale-tab">
                                @includeIf('backend.product.create.tab_product_sale', ['some' => 'data'])
                            </div>
                            <div class="tab-pane fade show  border-right border-bottom border-left p-3 " id="product_store"
                                role="tabpanel" aria-labelledby="product_store-tab">
                                @includeIf('backend.product.create.tab_product_store', ['some' => 'data'])
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
 <script src="{{ asset('js/images.js') }}"></script>
    <script>
        CKEDITOR.replace('detail')
    </script>
@endsection
