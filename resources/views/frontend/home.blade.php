@extends('layouts.site')

@section('title', $title ?? 'trang chủ')
@section('header')

@endsection

@section('content')
    <x-slide-show />
    <section class="maincontent">
        <div class="section_feature py-5">
            <div class="container">
                <div class="section_banner py-5">
                    <div class="row banner">
                        <div class="col-md-4 banner--item">
                            <img src="https://u6wdnj9wggobj.vcdn.cloud/media/mgs_blog/d/e/deal-doc-quyen-website-432x260.jpg"
                                alt="" class="d-block w-100">
                        </div>
                        <div class="col-md-4 banner--item">
                            <img src="https://demo.wpthemego.com/themes/sw_coruja/wp-content/uploads/2019/07/bn2.jpg"
                                alt="" class="d-block w-100">
                        </div>
                        <div class="col-md-4 banner--item">
                            <img src="https://babykid.vn/wp-content/uploads/2019/11/1.png"
                                alt="" class="d-block w-100">
                        </div>
                    </div>
                </div>

                <div class="new_product py-5 owl-carousel-parent">
                    <x-new-product-home />
                </div>

               
                <div class="section_banner py-5">
                    <div class="row banner">
                        <div class="col-md-12 banner--item">
                            <img src="{{ asset('images/bannersale.jpg') }}"
                                alt="" class="d-block w-100">
                        </div>

                    </div>
                </div>
                <div class="category_product py-5">
                    @foreach ($list_category as $category)
                        <x-product-home :rowcate="$category" />
                    @endforeach
                </div>
            </div>
        </div>

    </section>

@endsection
