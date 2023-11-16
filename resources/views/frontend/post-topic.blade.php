@extends('layouts.site')

@section('title', $title ?? 'trang chủ')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection
@section('content')
    <section class="maincontent">
        <div class="section_book_feature">
            <div class="container">
                <div class="row my-5 bg-white">
                    <div class="d-flex justify-content-center">
                        <h3 class="header-name">{{ $topic->name }}</h3>
                    </div>
                    <div class="d-flex justify-content-center">


                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                        chủ</a></li>
                                <li class="breadcrumb-item active-main cate-name" aria-current="page">{{ $topic->name }}
                              
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

                                    <option value="{{ Request::url() }}?sort_by=NewToOld">Mới tới cũ</option>
                                    <option value="{{ Request::url() }}?sort_by=OldToNew">Cũ tới mới</option>
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

                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <div class="col-md-9 col-12 float-md-none shadow py-3">
                                @if (count($list) > 0)
                                    <div class="row row-cols-1 row-cols-md-3 g-4">
                                        @foreach ($list as $item)
                                            <div class="col">
                                                <div class="card h-100">
                                                    <div class="card-header">
                                                        <h5 class="card-title"><a href="{{ route('slug.home', ['slug' => $item->slug]) }}">{{ $item->title }}</a>
                                                        </h5>
                                                        <p class="card-text">{{ $item->created_at }}</p>
                                                    </div>

                                                    <div class="card-body">
                                                        <a href="{{ route('slug.home', ['slug' => $item->slug]) }}">
                                                            <img src="{{ asset('images/post/' . $item->image) }}"
                                                                class="card-img-top" alt="...">
                                                        </a>
                                                    </div>
                                                    <div class="card-footer">
                                                        <small class="text-muted">Bài viết được đăng bởi:
                                                            </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="row ">
                                        <div class="d-flex justify-content-center">
                                            <h3 class="text-no-product">Không có sản phẩm</h3>
                                        </div>
                                    </div>
                                @endif

                                <div class="py-2 ">
                                    {{ $list->links('pagination::bootstrap-5') }}
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
    </script>
@endsection
