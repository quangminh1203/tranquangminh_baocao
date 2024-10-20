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
                            <img src="{{ asset('images/sale1.jpg') }}"
                                alt="" class="d-block w-100">
                        </div>
                        <div class="col-md-4 banner--item">
                            <img src="{{ asset('images/sale2.jpg') }}"
                                alt="" class="d-block w-100">
                        </div>
                        <div class="col-md-4 banner--item">
                            <img src="{{ asset('images/sale3.jpg') }}"
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
                            <video id="bannerVideo" class="d-block w-100" autoplay muted>
                                <source src="{{ asset('images/bannersale.mp4') }}" type="video/mp4">
                                Trình duyệt của bạn không hỗ trợ thẻ video.
                            </video>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var video = document.getElementById('bannerVideo');
            video.addEventListener('ended', function () {
                setTimeout(function () {
                    video.play();
                }, 3000); // 3000 milliseconds = 3 seconds
            });
        });
    </script>

@endsection
