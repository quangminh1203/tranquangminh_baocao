@extends('layouts.site')

@section('title', $title ?? 'trang chủ')
@section('header')
@endsection
@section('content')
    @php
        $total = 0;
    @endphp
    <div class="container modal-body">
        @includeIf('backend.messageAlert', ['some' => 'data'])
        <div class="row my-3 ">
            <div class="d-flex justify-content-center">


                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                chủ</a></li>
                        <li class="breadcrumb-item" aria-current="page">
                            Giỏ hàng</a>
                        </li>

                    </ol>
                </nav>
            </div>
        </div>

        <div class="bg-white">

            <section class="mycontent shadow">
                <div class="container">
                    <div class="row mx-auto ">


                        <table class="table table-bordered">
                            <h1 class="text-info my-3"> Giỏ hàng</h1>
                            <thead>
                                <tr>


                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">image</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Tên sản phẩm</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Giá</th>
                                    <th class="col-md-2 col-sm-2 col-2 align-middle text-center">Số lượng</th>
                                    <th class="col-md-3 col-sm-2 col-2 align-middle text-center">Thành tiền</th>
                                    <th class="col-md-1 col-sm-1 col-1 align-middle text-center">Hủy</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if (count($data) > 0)

                                    @foreach ($data as $row)
                                        <tr class="product_data">

                                            {{-- <td class="text-center" style="width:20px">
                                            <div class="form-group">
                                                <input name="checkId[]" type="checkbox" value="{{ $row->id }}"
                                                    id="web-developer">
                                            </div>
                                        </td> --}}
                                            <td>

                                                <img src="{{ asset('images/product/' . $row->products->images[0]->image) }}"
                                                    class="img-fluid" alt="{{ $row->image }}">


                                            </td>
                                            <td>
                                                {{ $row->products->name }}
                                            </td>
                                            <td>
                                                @if ($row->products->sale && $row->products->sale->date_begin <= now() && $row->products->sale->date_end >= now())
                                                    {{ number_format($row->products->sale->price_sale) }}đ
                                                @else
                                                    {{ number_format($row->products->price_buy) }}đ
                                                @endif


                                            </td>

                                            <td class="text-center">
                                                @if ($row->products->store->qty >= $row->product_qty)
                                                    <div class="buy-amount " style="justify-content: center">
                                                        <input type="hidden" value="{{ $row->products->id }} "
                                                            value="{{ $row->products->id }}" class="prod_id">
                                                        <input type="hidden" value="{{ $row->products->store->qty }} "
                                                            class="qty_max">
                                                        <button class="minus-btn changeQty">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19.5 12h-15" />
                                                            </svg>
                                                        </button>
                                                        <input type="text" class="amount" name="amount"
                                                            value="{{ $row->product_qty }}">
                                                        <button class="plus-btn changeQty">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 4.5v15m7.5-7.5h-15" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    @php
                                                        $total += $row->product_qty * ($row->products->sale && $row->products->sale->date_begin <= now() && $row->products->sale->date_end >= now() ? $row->products->sale->price_sale : $row->products->price_buy);
                                                    @endphp
                                                @else
                                                    <span class="text-danger">
                                                        Hết hàng
                                                    </span>
                                                @endif


                                            </td>
                                            <td class="text-center">
                                                @if ($row->products->store->qty >= $row->product_qty)
                                                    @if ($row->products->sale && $row->products->sale->date_begin <= now() && $row->products->sale->date_end >= now())
                                                        {{ number_format($row->product_qty * $row->products->sale->price_sale) }}đ
                                                    @else
                                                        {{ number_format($row->product_qty * $row->products->price_buy) }}đ
                                                    @endif
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-danger delete__cart--item "><i
                                                        class="fa-regular fa-circle-xmark"></i></button>

                                            </td>






                                        </tr>
                                    @endforeach
                                @else
                                    <img src="https://anphapetrol.store/img/empty-cart__G35z9.png" alt="">
                                @endif
                            </tbody>


                        </table>
                        <div class="cart my-2">
                            <h5 class="fs-3 " style="display: inline-block">Tổng tiền: <strong
                                    class="text-danger">{{ number_format($total) }}đ</strong></h5>
                            <a href="{{ url('check-out') }}" class="btn btn-outline-success float-end">Thanh toán</a>
                        </div>

                    </div>
                </div>
            </section>

            </form>
        </div>

    @endsection

    @section('footer')

    @endsection
