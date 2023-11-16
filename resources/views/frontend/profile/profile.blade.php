@extends('layouts.site')

@section('title', $title ?? 'trang chủ')
@section('header')
    <style>
        /* Profile container */
        .profile {
            margin: 20px 0;
        }

        /* Profile sidebar */
        .profile-sidebar {
            padding: 20px 0 10px 0;
            background: #fff;
        }

        .profile-userpic img {
            float: none;
            margin: 0 auto;
            width: 50%;
            height: 50%;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
        }

        .profile-usertitle {
            text-align: center;
            margin-top: 20px;
        }

        .profile-usertitle-name {
            color: #5a7391;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .profile-usertitle-job {
            text-transform: uppercase;
            color: #5b9bd1;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .profile-userbuttons {
            text-align: center;
            margin-top: 10px;
        }

        .profile-userbuttons .btn {
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 15px;
            margin-right: 5px;
        }

        .profile-userbuttons .btn:last-child {
            margin-right: 0px;
        }

        .profile-usermenu {
            margin-top: 30px;
        }

        .profile-usermenu ul li {
            border-bottom: 1px solid #f0f4f7;
        }

        .profile-usermenu ul li:last-child {
            border-bottom: none;
        }

        .profile-usermenu ul li a {
            color: #93a3b5;
            font-size: 14px;
            font-weight: 400;
        }

        .profile-usermenu ul li a i {
            margin-right: 8px;
            font-size: 14px;
        }

        .profile-usermenu ul li a:hover {
            background-color: #fafcfd;
            color: #5b9bd1;
        }

        .profile-usermenu ul li.active {
            border-bottom: none;
        }

        .profile-usermenu ul li.active a {
            color: #5b9bd1;
            background-color: #f6f9fb;
            border-left: 2px solid #5b9bd1;
            margin-left: -2px;
        }

        /* Profile Content */
        .profile-content {
            padding: 20px;
            background: #fff;
            min-height: 460px;
        }























        a,
        button,
        code,
        div,
        img,
        input,
        label,
        li,
        p,
        pre,
        select,
        span,
        svg,
        table,
        td,
        textarea,
        th,
        ul {
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
            border-radius: 0 !important;
        }

        .dashboard-stat,
        .portlet {
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -ms-border-radius: 4px;
            -o-border-radius: 4px;
        }

        .portlet {
            margin-top: 0;
            margin-bottom: 25px;
            padding: 0;
            border-radius: 4px;
        }

        .portlet.bordered {
            border-left: 2px solid #e6e9ec !important;
        }

        .portlet.light {
            padding: 12px 20px 15px;
            background-color: #fff;
        }

        .portlet.light.bordered {
            border: 1px solid #e7ecf1 !important;
        }

        .list-separated {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .profile-stat {
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f4f7;
        }

        .profile-stat-title {
            color: #7f90a4;
            font-size: 25px;
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase !important;
        }

        .profile-stat-text {
            color: #5b9bd1;
            font-size: 10px;
            font-weight: 600;
            text-align: center;
        }

        .profile-desc-title {
            color: #7f90a4;
            font-size: 17px;
            font-weight: 600;
        }

        .profile-desc-text {
            color: #7e8c9e;
            font-size: 14px;
        }

        .margin-top-20 {
            margin-top: 20px !important;
        }

        [class*=" fa-"]:not(.fa-stack),
        [class*=" glyphicon-"],
        [class*=" icon-"],
        [class^=fa-]:not(.fa-stack),
        [class^=glyphicon-],
        [class^=icon-] {
            display: inline-block;
            line-height: 14px;
            -webkit-font-smoothing: antialiased;
        }

        .profile-desc-link i {
            width: 22px;
            font-size: 19px;
            color: #abb6c4;
            margin-right: 5px;
        }
    </style>
@endsection
@section('content')

    <div class="container modal-body">
        @includeIf('backend.messageAlert', ['some' => 'data'])
        <div class="row my-3 ">
            <div class="d-flex justify-content-center">


                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                chủ</a></li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="{{ route('site.profile') }}" class="text-bl_gr">profile</a>
                        </li>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="container shadow">
        <div class="row profile">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <img src="{{ asset('images/user/' . $profile->image) }}" class="img-responsive" alt="">
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{ $profile->name }}
                        </div>
                        <div class="profile-usertitle-job">
                            @if ($profile->roles == 0)
                                Member
                            @else
                                Admin
                            @endif

                        </div>
                    </div>

                    <div class="portlet light bordered">
                        <!-- STAT -->

                        <!-- END STAT -->
                        <div>
                            <h4 class="profile-desc-title">Chức năng</h4>
                            </span>
                            <div class="margin-top-20 profile-desc-link">
                                <i class="fa fa-globe"></i>
                                <a href="{{ route('site.logout') }}">Tài khoản</a>
                            </div>

                            <div class="margin-top-20 profile-desc-link">
                                <i class="fa fa-globe"></i>
                                <a href="{{ route('site.logout') }}">Địa chỉ</a>
                            </div>
                            <div class="margin-top-20 profile-desc-link">
                                <i class="fa fa-globe"></i>
                                <a href="{{ route('site.logout') }}">Đăng xuất</a>
                            </div>

                        </div>
                    </div>



                </div>
            </div>
            <div class="col-md-9">
                <div class="profile-content">
                    <form action="" method="post" enctype="multipart/form-data" id="check_submit">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="my-3" for="name">Tên hiển thị*
                                </label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', $profile->name) }}">
                                @if ($errors->has('name'))
                                    <div class="text-danger">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                                <label class="my-3" for="email">Địa chỉ email*
                                </label>
                                <input class="form-control" type="text" name="email" id="email"
                                    value="{{ old('email', $profile->email) }}">
                                @if ($errors->has('email'))
                                    <div class="text-danger">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif

                                <label class="my-3" for="phone">Điện thoại*
                                </label>
                                <input class="form-control" type="number" name="phone" id="phone"
                                    value="{{ old('phone', $profile->phone) }}">
                                @if ($errors->has('phone'))
                                    <div class="text-danger">
                                        {{ $errors->first('phone') }}
                                    </div>
                                @endif

                                <label class="my-3">Giới tính: </label>
                                <input type="radio" name="gender" id="nam" value="0"
                                    {{ old('gender', $profile->gender) == 0 ? 'checked' : ' ' }}><label
                                    for="nam">Nam</label>
                                <input type="radio" name="gender" id="nu" value="1"
                                    {{ old('gender', $profile->gender) == 1 ? 'checked' : ' ' }}><label
                                    for="nu">Nữ</label>
                                <br />
                                <label for="image">Hình đại diện</label>
                                <input type="checkbox" name="def_image" value="1"
                                    {{ old('def_image') == 1 ? 'checked' : '' }}>(Mặc định)
                                <input name="image" id="image" type="file" class="form-control "
                                    value="{{ old('image', $profile->image) }}">
                                @if ($errors->has('image'))
                                    <div class="text-danger">
                                        {{ $errors->first('image') }}
                                    </div>
                                @endif
                                <div class="d-flex justify-content-center">
                                    <button class="my-3  btn btn-outline-success" type="submit">LƯU THÔNG TIN</button>
                                </div>
                            </div>

                    </form>
                    <div class="col-md-6">
                        <form action="{{ route('site.postget_password', ['id' => $profile->id]) }}" method="post">
                            @csrf
                            <div class="row">
                                <h4 class="mt-4 mb-1 border-bottom fs-5">Thay đổi mật khẩu</h4>
                                <label class="my-3" for="password">Mật khẩu hiện tại ( <span style="font-weight: 100">bỏ
                                        trống nếu
                                        không đổi</span> )
                                </label>
                                <input class="form-control" type="password" name="password" id="password"
                                    value="">
                                @if ($errors->has('password'))
                                    <div class="text-danger">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                                <label class="my-3" for="new_password">Mật khẩu mới ( <span style="font-weight: 100">bỏ
                                        trống
                                        nếu
                                        không đổi</span> )
                                </label>
                                <input class="form-control" type="password" name="new_password"
                                    id="new_password-password" value="">
                                @if ($errors->has('new_password'))
                                    <div class="text-danger">
                                        {{ $errors->first('new_password') }}
                                    </div>
                                @endif
                                <label class="my-3" for="confirm_password">Xác nhận mật khẩu mới
                                </label>
                                <input class="form-control" type="password" name="confirm_password"
                                    id="confirm_password" value="">
                                @if ($errors->has('confirm_password'))
                                    <div class="text-danger">
                                        {{ $errors->first('confirm_password') }}
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-center">
                                <button class="my-3  btn btn-outline-success" type="submit">ĐỔI MẬT KHẨU</button>
                            </div>

                        </form>

                    </div>
                </div>




            </div>
        </div>
    </div>
    </div>
@endsection

@section('footer')

@endsection
