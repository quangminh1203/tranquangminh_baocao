@if ($count != 0)
    <div class="col-md-5 col-12 product_flash owl-carousel-parent">
        <div class="row title border-1 border-dark border-bottom mb-5">

            <div class="col-md-10 col-8  ">
                <h2 class="d-inline-block custom-title "><a class="text-decoration-none "
                        href="index.php?option=product">Flash
                        Sale</a></h2>
            </div>
            <div class="col-md-2 col-4 text-end owl-carousel-parent__btn">
                <button class='back-btn '>
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class='next-btn '>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

        </div>
        <div class="row">
            <div class="large-12 columns">
                <div class="owl-carousel owl-carousel_flash_sale  owl-theme ">
                    @foreach ($list_product as $product)
                        <div class="item  p-0 bg-none  product-data">
                            <div class="card ">
                                <div class="row g-0">
                                    <div class="col-md-5 col-6 item-img ">
                                        <a href="{{ route('slug.home', ['slug' => $product->slug]) }}">
                                            <img src="{{ asset('images/product/' . $product->images[0]->image) }}"
                                                class="card-img-top img-product_flash img-fluid py-auto" alt="...">
                                        </a>
                                        <div class="product-sale">
                                            <div class="sale-off">
                                                -{{ (int) ((($product->price_buy - $product->sale->price_sale) / $product->price_buy) * 100) }}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <div class="card-body ">
                                            <h3 class="mt-0 text-line-2 fs-1 custom-title ">
                                                <a
                                                    href="{{ route('slug.home', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                                            </h3>
                                            <div class="row item-price">
                                                <div class="col-md-6 col-6 ">
                                                    <h3 class="m-0  amount-md">
                                                        {{ number_format($product->sale->price_sale) }}₫</h3>
                                                </div>
                                                <div class="col-md-6 col-6 ">
                                                    <h3 class="m-0  sale-md">
                                                        {{ number_format($product->price_buy) }}₫
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="card-text text-line-5">
                                                {!! $product->metadesc !!}
                                            </div>
                                            <div class="card-item">
                                                <div class="row ">


                                                    <div class="col-md-6 col-12">
                                                        <a class="cart-item__action active-1" title="Xem"
                                                            href="{{ route('slug.home', ['slug' => $product->slug]) }}"
                                                            data-abc="true">
                                                            <i class="fa-regular fa-eye"></i>
                                                        </a>
                                                        <input type="hidden" class="amount qly_input" name="amount"
                                                            value="1">
                                                        <input type="hidden" value="{{ $product->id }} "
                                                            name="product_id_hidden" class="prod_id">
                                                        @if ($product->store->qty > 0)
                                                            <a class="cart-item__action addToCartBtn" type="submit"
                                                                title="Thêm vào giỏ" data-abc="true">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </a>
                                                        @else
                                                            <a class="cart-item__action no-product"
                                                                title="Hết hàng" data-abc="true">
                                                                Hết hàng
                                                            </a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach


                </div>
            </div>

        </div>
        <div class="row py-3 flash_banner">
            <img src="https://demo.wpthemego.com/themes/sw_coruja/wp-content/uploads/2019/09/banner-countdown.png"
                alt="">
        </div>
    </div>
@endif
