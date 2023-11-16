                    <div class="row title border-1 border-dark border-bottom mb-3">
                        <div class="col-md-10 col-8 ">
                            <h2 class="custom-title d-inline-block "><a class="text-decoration-none btn-tab "
                                    href="{{ route('site.product') }}">Hàng mới</a></h2>

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
                        <div class="col-md-12 col-12 mx-auto">
                            <div class="large-12 columns">
                                <div class="owl-carousel new_book_carousel  owl-theme ">

                                    @foreach ($list_product as $product)
                                        @php
                                            if ($product->sale == null) {
                                                $index = 0;
                                            } else {
                                                $index = 1;
                                            }
                                        @endphp

                                        <div class="item  p-0 bg-none  product-data">
                                            <div class="card h-100 text-center shadow-product product-wrapper">
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



                                </div>

                            </div>

                        </div>
                    </div>
