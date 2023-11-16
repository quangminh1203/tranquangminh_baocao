@extends('layouts.site')

@section('title', $product->name ?? 'trang chủ')
@section('header')
    <script src="{{ asset('dist/splide-4.1.3/dist/js/splide.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('dist/splide-4.1.3/dist/css/splide.min.css') }}">
    <script></script>
    <style>
        .splide__slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .splide__slide {
            opacity: 0.6;
        }

        .splide__slide.is-active {
            opacity: 1;
        }

        /* qty */
        /* .btn-qty {
                                            height: 40px;
                                            min-width: 80%;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            background: #FFF;

                                        }

                                        .btn-qty span {

                                            text-align: center;
                                            font-size: 20px;
                                            font-weight: 600;
                                            cursor: pointer;
                                            user-select: none;
                                            border: 2px solid rgba(0, 0, 0, 0.2);
                                            border-radius: 20px;
                                            line-height: 30px;
                                        }

                                        .btn-qty span:not(.num) {
                                            width: 20%;
                                            border-radius: 50%;
                                        }

                                        .btn-qty span.num {
                                            width: 100%;
                                            font-size: 20px;
                                            border-right: 2px solid rgba(0, 0, 0, 0.2);
                                            border-left: 2px solid rgba(0, 0, 0, 0.2);
                                            pointer-events: none;
                                        } */

        /* cart */

        .add-cart {
            border-radius: 50%;
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;

        }

        .add-cart button {
            background: #00000030;
            display: inline-block;
            width: 100%;

            line-height: 35px;
            text-align: center;
            padding: 0;
            font-size: 20px;
            color: white;
            border-radius: 20px;
        }

        .add-cart button:hover {
            color: white;
            cursor: pointer;
            background-color: #04aa6d;
        }

        @media (min-width: 768px) {
            .add-cart {
                height: 35px !important;
                line-height: 35px !important;
                width: 40% !important;
                border-radius: 20px !important;
            }
        }

        /* product thumb */
        .product__thumb--image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /*  */
    </style>
@endsection

@section('content')
    @php
        if ($product_sale->sale == null) {
            $index = 0;
        } else {
            $index = 1;
        }
    @endphp
    <div class="container " >
        <div class="row my-3 ">
            <div class="d-flex justify-content-center">


                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                chủ</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('slug.home', ['slug' =>  $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                        
                        <li class="breadcrumb-item active-main cate-name" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row product-data shadow">
            <div class="col-md-12 col-12 mx-auto bg-white py-5  ">
                <div class="row mt-3">
                    <div class="col-md-3 col-10 mx-auto  p-3 ">
                        <section id="image-carousel" class="splide" aria-label="Beautiful Images">
                            <div class="splide__track product-wrapper">
                                <ul class="splide__list">
                                    @for ($i = 0; $i < count($product->images); $i++)
                                        <li class="splide__slide">
                                            <img src="{{ asset('images/product/' . $product->images[$i]->image) }}"
                                                alt="">
                                        </li>
                                    @endfor

                                </ul>
                                @if ($index == 1)
                                    <div class="product-sale">
                                        <div class="sale-off">
                                            -{{ (int) ((($product->price_buy - $product->sale->price_sale) / $product->price_buy) * 100) }}%
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </section>
                        <section id="thumbnail-carousel" class="splide mt-1"
                            aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @for ($i = 0; $i < count($product->images); $i++)
                                        <li class="splide__slide">
                                            <img src="{{ asset('images/product/' . $product->images[$i]->image) }}"
                                                alt="">
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-5 col-12 p-3 ">

                        <h1 class="title fs-2"><strong>{{ $product->name }}</strong></h1>
                        <!-- 1 -->

                        <div class="row mt-3">
                            <div class="col-md-6 col-12 d-flex item-price">
                                {{-- <h3 class="fs-4  amount">243.000₫</h3>
                                <span class="fs-5 ms-2 sale">
                                    270,000₫
                                </span> --}}
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

                        <div class="row my-3 d-flex justify-content-between">
                            <div class="col-md-6 col-6">
                                <div class="buy-amount">
                                    <input type="hidden" value="{{ $product->store->qty }} " class="qty_max">
                                    <input type="hidden" value="{{ $product->id }} " name="product_id_hidden"
                                        class="prod_id">
                                    <button class="minus-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                        </svg>
                                    </button>
                                    <input type="text" class="amount qly_input" name="amount" value="1">
                                    <button class="plus-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 col-2 ">
                                <div class="add-cart">
                                    @if ($product->store->qty > 0)
                                        <button class="action-cart addToCartBtn" type="submit" title="Thêm vào giỏ"
                                            data-abc="true">
                                            <i class="fa fa-shopping-cart"></i>
                                        </button>
                                    @endif


                                </div>

                            </div>

                        </div>


                        <!-- 3 -->
                        <div class="row mt-3 border-top border-bottom ">
                            <div class="col-md-12 col-12 mb-2">
                                <span>
                                    Mã sản phẩm:
                                </span>
                                <span class="cate-name">{{ $product->id }}</span>
                            </div>
                            <div class="col-md-12 col-12 mb-2">
                                <span>
                                    Thương hiệu:
                                </span>
                                <span class="cate-name">{{ $product->brand->name }}</span>
                            </div>
                            <div class="col-md-12 col-12 mb-2">
                                <span>
                                    Loại:
                                </span>
                                <span class="cate-name">{{ $product->category->name }}</span>
                            </div>
                            <div class="col-md-12 col-12 mb-2 ">
                                <span>
                                    Tình trạng:
                                </span>

                                @if ($product->store->qty > 0)
                                    <span class="cate-name">
                                        Còn hàng
                                    </span>
                                @else
                                   <span class="text-danger">
                                     Hết hàng
                                   </span>
                                @endif

                            </div>
                        </div>
                        <!-- 4 -->

                        <!-- 5 -->
                        <div class="row">
                            <p>
                                Gọi ngay
                                <a href="#" class="cate-name">1800 6750</a>
                                để được tư vấn miễn phí
                            </p>
                        </div>
                        <div class="row ">
                            <div class="col product-rating ">
                                <i class="fa-solid fa-star product-rating__star--gold"></i>
                                <i class="fa-solid fa-star product-rating__star--gold"></i>
                                <i class="fa-solid fa-star product-rating__star--gold"></i>
                                <i class="fa-solid fa-star product-rating__star--gold"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                        <!-- 6 -->



                    </div>
                    <div class="col-md-4 col-12 p-md-3">
                        <h1 class="border-bottom border-2 text-center fs-2"><strong>Sản phẩm liên quan</strong></h1>
                        <ul class="row p-0 mt-3">
                            @if (count($list_product) > 0)
                                @foreach ($list_product as $item)
                                    @php
                                        if ($item->sale == null) {
                                            $index = 0;
                                        } else {
                                            $index = 1;
                                        }
                                    @endphp
                                    <li class="mb-2 col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-3 col-2">
                                                <div class="product__thumb--image">
                                                    <img src="{{ asset('images/product/' . $item->images[0]->image) }}"
                                                        alt="" class="w-100 border ">
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-10">
                                                <div class="product__thumb--title">
                                                    <h3 class="fs-5 m-0 product-name"><a
                                                            href="{{ route('slug.home', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                                                    </h3>
                                                </div>


                                                <div class="product__thumb--price d-flex item-price">
                                                    @if ($index == 1)
                                                        <div class="col-md-6 col-6 ">
                                                            <h3 class="m-0  amount">
                                                                {{ number_format($item->sale->price_sale) }}₫
                                                            </h3>
                                                        </div>
                                                        <div class="col-md-6 col-6 ">
                                                            <h3 class="m-0  sale">
                                                                {{ number_format($item->price_buy) }}₫
                                                            </h3>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6 col-6 ">
                                                            <h3 class="m-0 amount">
                                                                {{ number_format($item->price_buy) }}₫
                                                            </h3>
                                                        </div>
                                                    @endif
                                                </div>


                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="mb-2 col-md-12 col-12">
                                    <img src="https://a1indiangroceries.com/images/no_product.jpg" alt=""
                                        class="w-100 border ">
                                </li>
                            @endif

                        </ul>
                    </div>

                </div>
                <div class="row mt-3">
                    <div class="Featured-products row bg-product-item mt-3" id="menuWrapper">
                        <div class="col-md-12  mt-2 pe-0">
                            <div class="row">
                                <nav class="pe-0">
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-home" type="button" role="tab"
                                            aria-controls="nav-home" aria-selected="true">
                                            <h2 class="fs-5">MÔ TẢ SẢN PHẨM</h2>
                                        </button>
                                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-profile" type="button" role="tab"
                                            aria-controls="nav-profile" aria-selected="false">
                                            <h2 class="fs-5">Thông Tin</h2>
                                        </button>
                                        <button class="nav-link" id="nav-comment-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-comment" type="button" role="tab"
                                            aria-controls="nav-comment" aria-selected="false">
                                            <h2 class="fs-5">BÌNH LUẬN</h2>
                                        </button>
                                    </div>
                                </nav>
                            </div>
                        </div>

                        <div class="col-md-12 col-12 pe-0">
                            <div class="row tab-content " id="nav-tabContent">
                                <div class="tab-pane fade show active pe-0 " id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab" tabindex="0">
                                    <div class="card card-body">
                                        <h1>{{ $product->name }}</h1>
                                        <div>
                                            {!! $product->metadesc !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- end-MÔ TẢ SẢN PHẨM -->
                                <div class="tab-pane fade pe-0" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab" tabindex="0">
                                    <div class="card card-body">
                                        {!! $product->detail !!}
                                    </div>
                                </div>
                                <!-- end Thông Tin Chi Tiết-->
                                <div class="tab-pane fade pe-0" id="nav-comment" role="tabpanel"
                                    aria-labelledby="nav-comment-tab" tabindex="0">
                                    <div class="card card-body">
                                        @includeIf('frontend.comment', ['some' => 'data'])
                                    </div>
                                </div>
                                <!-- end Thông Tin Chi Tiết-->
                            </div>
                        </div>





                    </div>
                </div>
            </div>
        </div>

    </div>




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
    </script>
    {{--  --}}
    <!-- all js here -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var main = new Splide('#image-carousel', {

                pagination: false,
            });
            var thumbnails = new Splide('#thumbnail-carousel', {
                fixedWidth: 80,
                fixedHeight: 50,

                gap: 10,
                rewind: true,
                isNavigation: true,
                pagination: false,
                focus: 'center',
                breakpoints: {
                    600: {
                        fixedWidth: 60,
                        fixedHeight: 44,
                    },
                },
            });
            main.sync(thumbnails);
            main.mount();
            thumbnails.mount();
        });
        // button qty
        // const plus = document.querySelector(".plus"),
        //     minus = document.querySelector(".minus"),
        //     num = document.querySelector(".num");

        // let a = 1;

        // plus.addEventListener("click", () => {
        //     a++;
        //     a = (a < 10) ? "0" + a : a;
        //     num.innerText = a;
        // });

        // minus.addEventListener("click", () => {
        //     if (a > 1) {
        //         a--;
        //         a = (a < 10) ? "0" + a : a;
        //         num.innerText = a;
        //     }
        // });
    </script>


@endsection
