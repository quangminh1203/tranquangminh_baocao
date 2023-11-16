@extends('layouts.site')

@section('title', $title ?? 'trang chủ')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection
@section('content')

    <div class="container ">
        <div class="row my-3 ">
            <div class="d-flex justify-content-center">


                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                chủ</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ route('slug.home', ['slug' => $post->topic->slug]) }}">{{ $post->topic->name }}</a>
                        </li>

                        <li class="breadcrumb-item active-main cate-name" aria-current="page">{{ $post->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row product-data shadow">
            <div class="col-md-12 col-12 mx-auto bg-white py-5  ">
                <div class="row mt-3">
                    <div class="col-md-3 col-10 mx-auto  p-3 ">
                        <nav class="navbar navbar-expand-md">
                            <div id="left-menu" class="offcanvas offcanvas-md offcanvas-start " style="max-width: 100%;">
                                <div class="offcanvas-body flex-column">
                                    <x-list-category />
                                    <x-list-brand />
                                    <x-list-topic />
                                   
                                    <x-list-post-topic :rowpost="$list" />


                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="col-md-9 col-12 p-3">
                        <h5 class="card-title"><a
                                href="{{ route('slug.home', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                        </h5>

                        <small class="text-muted">Bài viết được đăng bởi:
                            </small>
                        <div class="mt-3">
                            {!! $post->detail !!}
                        </div>
                    </div>

                </div>
                <div class="row mt-3">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">
                                <h2 class="fs-5">BÌNH LUẬN</h2>
                            </button>

                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                            tabindex="0">
                            <div class="card card-body">

                                @includeIf('frontend.comment', ['some' => 'data'])
                            </div>
                        </div>

                    </div>

                    {{-- <div class="Featured-products row bg-product-item mt-3" id="menuWrapper">
                        <div class="col-md-12  mt-2 pe-0">
                            <div class="row">
                                <nav class="pe-0">
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">

                                        <button class="nav-link  " id="nav-comment-tab_1" data-bs-toggle="tab"
                                            data-bs-target="#nav-comment" type="button" role="tab"
                                            aria-controls="nav-comment" aria-selected="false">

                                        </button>
                                    </div>
                                </nav>
                            </div>
                        </div>

                        <div class="col-md-12 col-12 pe-0">
                            <div class="row tab-content " id="nav-tabContent">

                                <!-- end Thông Tin Chi Tiết-->
                                <div class="tab-pane fade pe-0 " id="nav-comment" role="tabpanel"
                                    aria-labelledby="nav-comment-tab" tabindex="0">

                                </div>
                                <!-- end Thông Tin Chi Tiết-->
                            </div>
                        </div>





                    </div> --}}
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
    </script>
@endsection
