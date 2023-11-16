@extends('layouts.site')

@section('title', $title ?? 'trang chủ')
@section('header')
@endsection
@section('content')
    <div class="container modal-body">
        @includeIf('frontend.messageAlert', ['some' => 'data'])
        <div class="row my-3 ">
            <div class="d-flex justify-content-center">


                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                chủ</a></li>
                        <li class="breadcrumb-item" aria-current="page">
                           Đăng
                            nhập / Đăng ký</a>
                        </li>

                    </ol>
                </nav>
            </div>
        </div>
        <div class="text-center">

            @includeIf('frontend.model-login')
        </div>
    </div>
@endsection

@section('footer')

@endsection
