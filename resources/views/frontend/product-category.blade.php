@extends('layouts.site')

@section('title', $cat->name ?? 'trang chủ')
@section('header')

    <style>
        .filter-condition {
            padding: 20px;

            font-size: 16px;
            font-weight: bold;
        }

        .filter-condition select {
            width: 200px;
            padding: 0 0 0 10px;
            border: none;
            outline: none;
            font-weight: bold;
            color: purple;
            background: transparent;
            cursor: pointer;
            border: 1px solid;
        }



        /*  */
    </style>
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection
@section('content')

    <section class="maincontent">
        <div class="section_book_feature">
            <div class="container">
                <div class="row my-5 bg-white">
                    <div class="d-flex justify-content-center">
                        <h3 class="header-name">{{ $cat->name }}</h3>
                    </div>
                    <div class="d-flex justify-content-center">


                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                        chủ</a></li>
                                <li class="breadcrumb-item active-main cate-name" aria-current="page">{{ $cat->name }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="row bg-white">

                    <div class=" col-12 d-flex justify-content-end border-bottom">
                        <form action="">
                            @csrf
                            <div class="filter-condition shadow">
                                <span>Sắp xếp theo:</span>
                                <select name="sort" id="sort">
                                    <option value="{{ Request::url() }}?sort_by=none">Default</option>

                                    <option value="{{ Request::url() }}?sort_by=LowToHigh">Giá: thấp tới cao</option>
                                    <option value="{{ Request::url() }}?sort_by=HighToLow">Giá: cao tới thấp</option>
                                    <option value="{{ Request::url() }}?sort_by=az">tên: A - Z</option>
                                    <option value="{{ Request::url() }}?sort_by=za">Tên: Z - A</option>
                                </select>
                            </div>

                        </form>
                    </div>
                    <div class="col-md-12 col-12 mx-auto py-5 ">
                        <div class="row">
                            <!-- mobile -->
                            <div class="col-md-3 shadow">
                                <nav class="navbar navbar-expand-md">
                                    <div id="left-menu" class="offcanvas offcanvas-md offcanvas-start "
                                        style="max-width: 100%;">
                                        <div class="offcanvas-body flex-column">
                                            <x-list-category />
                                            <x-list-brand />
                                            <x-list-topic />
                                            <div class="my-4 mx-1" style="width: 95%">
                                                <label for="amount" class="mb-1">Lọc theo giá: </label>
                                                <form style="margin-left: 3%">
                                                    <div id="slider-range"></div>

                                                    <input type="text" id="amount" readonly
                                                        style="border:0; color:#f6931f; font-weight:bold; margin-left: -3%">
                                                    <input type="hidden" id="start_price" name="start_price">
                                                    <input type="hidden" id="end_price" name="end_price">
                                                    <br />
                                                    <input type="submit" name='fitter_price' value="Lọc giá"
                                                        class="btn btn-success" style="margin-left: -3%">

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <div class="col-md-9 col-12 float-md-none shadow">
                                @if (count($list_product) > 0)
                                    <div class="row row-cols-1 row-cols-md-3 g-4">
                                        @foreach ($list_product as $product)
                                            @php
                                                if ($product->sale == null) {
                                                    $index = 0;
                                                } else {
                                                    $index = 1;
                                                }
                                            @endphp

                                            <div class="col-md-3 col-6 item ">
                                                <div class="card h-100 text-center shadow-product product-wrapper product-data">
                                                    <div class="card-header item-img">

                                                        <a href="{{ route('slug.home', ['slug' => $product->slug]) }}">
                                                            <img src="{{ asset('images/product/' . $product->images[0]->image) }}"
                                                                class="card-img-top img-product img-fluid py-auto"
                                                                alt="...">
                                                        </a>
                                                        @if ($index == 1)
                                                            <div class="product-sale">
                                                                <div class="sale-off">
                                                                    -{{ (int) ((($product->price_buy - $product->sale->price_sale) / $product->price_buy) * 100) }}%
                                                                </div>

                                                            </div>
                                                        @endif
                                                        <div class="product-action">
                                                            <div class="product-action-style">

                                                                <input type="hidden" class="amount qly_input"
                                                                    name="amount" value="1">
                                                                <input type="hidden" value="{{ $product->id }} "
                                                                    name="product_id_hidden" class="prod_id">
                                                                @if ($product->store->qty > 0)
                                                                    <a class="action-cart addToCartBtn" type="submit"
                                                                        title="Thêm vào giỏ" data-abc="true">
                                                                        <i class="fa fa-shopping-cart"></i>
                                                                    </a>
                                                                @else
                                                                    <a class="action-cart no-product" title="Hết hàng"
                                                                        data-abc="true">
                                                                        <i class="fa-solid fa-ban"></i>
                                                                    </a>
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body ">
                                                        <h3
                                                            class="card-title fs-6 fs-7 text-bl_gr text-truncate product-name">
                                                            <a
                                                                href="{{ route('slug.home', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="row item-price ">

                                                            @if ($index == 1)
                                                                <div class="col-md-6 col-6 ">
                                                                    <h3 class="m-0  amount">
                                                                        {{ number_format($product->sale->price_sale) }}₫
                                                                    </h3>
                                                                </div>
                                                                <div class="col-md-6 col-6 ">
                                                                    <h3 class="m-0  sale">
                                                                        {{ number_format($product->price_buy) }}₫
                                                                    </h3>
                                                                </div>
                                                            @else
                                                                <div class="col-md-6 col-6 ">
                                                                    <h3 class="m-0 amount">
                                                                        {{ number_format($product->price_buy) }}₫
                                                                    </h3>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                        <!-- 2 -->



                                        <!--  -->
                                    </div>
                                @else
                                    <div class="row ">
                                        <div class="d-flex justify-content-center">
                                            <h3 class="text-no-product">Không có sản phẩm</h3>
                                        </div>
                                    </div>
                                @endif

                                <div class="py-2 ">
                                    {{ $list_product->links('pagination::bootstrap-5') }}
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('footer')
    <script>
        let listElements = document.querySelectorAll('.link');

        listElements.forEach(listElement => {
            listElement.addEventListener('click', () => {
                if (listElement.classList.contains('active')) {
                    listElement.classList.remove('active');
                } else {
                    listElements.forEach(listE => {
                        listE.classList.remove('active');
                    })
                    listElement.classList.toggle('active');
                }
            })
        });


        // range - price 

        $(function() {
            $("#slider-range").slider({
                orientation: "horizontal",
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price_range }},
                step: 10000,
                values: [{{ $min_price }}, {{ $max_price }}],
                slide: function(event, ui) {
                    $("#amount").val(ui.values[0] + "đ" + " - " + ui.values[1] + "đ");
                    $("#start_price").val(ui.values[0]);
                    $("#end_price").val(ui.values[1]);
                }
            });
            $("#amount").val($("#slider-range").slider("values", 0) + "đ" +
                " - " + $("#slider-range").slider("values", 1) + "đ");
        });
    </script>
@endsection
