@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

    <link rel="stylesheet" href="{{ asset('css/images.css') }}">

    <style>
        .img-product {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid;
            border-radius: 5px
        }

        .btn-delete-img {
            width: 20px;
            height: 20px;
            padding: 3px !important;
            position: relative;

        }

        .btn-delete-img>i {
            position: absolute;
            top: 2px;
            left: 5px;
        }
    </style>

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

                                <a class="btn btn-sm btn-info" href="{{ route('product.index') }}">
                                    <i class="fas fa-arrow-circle-left"></i> Quay về danh sách
                                </a>


                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data"
                        action="{{ route('product.imageUpload', ['product' => $product->id]) }}" method="POST">
                        @csrf
                        <div class="col-md-12 ">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="text-danger border-bottom border-1">
                                        New image
                                    </h3>

                                </div>
                                <div class="col-md-6 text-right">
                                    <button name="THEM" type="submit" class="btn btn-success ">
                                        <i class="fa-solid fa-floppy-disk"></i> Lưu
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="form_images mt-2">
                            <div class="card">
                                <div class="top">
                                    <p>Kéo và thả để thêm ảnh</p>


                                </div>
                                <div class="drag-area">
                                    <span class="visible">
                                        Kéo và thả ảnh vảo đây hoặc
                                        <span class="select" role="button">Thêm</span>
                                    </span>
                                    <span class="on-drop">Thả ra</span>
                                    <input name="image[]" type="file" class="file" multiple />
                                </div>

                                <div class="container">
                                    
                                    @if ($errors->has('image'))
                                        <div class="text-danger">
                                            {{ $errors->first('image') }}
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>


                    </form>
                    <form class="mt-2">
                        <div class="form-group" style="max-height: 80vh; height: auto; width: 80vw; overflow: hidden;">

                            <h3 class="text-danger border-bottom border-1">
                                Old image
                            </h3>

                            @foreach ($image as $pimage)
                                <label class="img-data mt-1">
                                    <input type="hidden" value="{{ $pimage->id }} " value="{{ $pimage->id }}"
                                        class="img_id">
                                    <input type="hidden" value="{{ $pimage->product_id }} "
                                        value="{{ $pimage->product_id }}" class="prod_id">
                                    <img src="{{ asset('images/product') }}/{{ $pimage->image }}" class="img-product"
                                        alt="">
                                    <button class="btn btn-outline-danger btn-delete-img"><i
                                            class="fa fa-times text-danger m-auto"></i></button>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>

@endsection
@section('footer')
    <script src="{{ asset('js/images.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.btn-delete-img').click(function(e) {
                e.preventDefault();
                var img_id = $(this).closest('.img-data').find('.img_id').val();
                var prod_id = $(this).closest('.img-data').find('.prod_id').val();
                // alert(img_id)
                $.ajax({
                    type: "POST",
                    url: "{{ route('product.imageDelete') }}",
                    data: {
                        'img_id': img_id,
                        'prod_id': prod_id,
                    },

                    success: function(response) {
                        console.log(img_id);
                        window.location.reload();
                        swal("", response.status, "success");

                        // alert(response.status);
                    }
                });
            });
        })
    </script>
@endsection
