@extends('layouts.site')

@section('title', $title ?? 'trang chủ')
@section('header')

@endsection
@section('content')

    <section class="maincontent">
        <div class="alert alert-danger print-error-msg" style="display:none">
            <ul></ul>
        </div>

        <div class="section_book_feature">
            <div class="container">
                <div class="row my-5 bg-white">
                    <div class="d-flex justify-content-center">
                        <h3 class="header-name">{{ $title }}</h3>
                    </div>
                    <div class="d-flex justify-content-center">


                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-bl_gr">Trang
                                        chủ</a></li>
                                <li class="breadcrumb-item active-main cate-name" aria-current="page">{{ $title }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="row bg-white d-flex justify-content-center">
                    <div class="col-md-4 col-12">
                        <div class="card contact-data">

                            <form>
                                @csrf
                                <div class="card-body ">
                                    <h6>LIÊN HỆ VỚI CHÚNG TÔI</h6>
                                    <hr>
                                    <div class="row checkout-form">
                                        <div class="col-md-12 mt-2">
                                            <label for="name">Tên </label>
                                            <input type="text" name="name"
                                                value="{{ Auth::guard('users')->user()->name ?? old('name') }}"
                                                class="form-control name" placeholder="Name">

                                            <p class="error_msg text-danger" id="name"></p>

                                        </div>

                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="">Email </label>
                                        <input type="email" name="email"
                                            value="{{ Auth::guard('users')->user()->email ?? old('email') }}"
                                            class="form-control email" placeholder="email">
                                        <p class="error_msg text-danger" id="email"></p>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="phone">Điện thoại </label>
                                        <input type="text" name="phone" class="phone form-control"
                                            value="{{ Auth::guard('users')->user()->phone ?? old('phone') }}"
                                            placeholder="phone">
                                        <p class="error_msg text-danger" id="phone"></p>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="title">Tiêu đề</label>
                                        <input type="text" name="title" class="title form-control"
                                            value="{{ old('title') }}" placeholder="title">
                                        <p class="error_msg text-danger" id="title"></p>
                                    </div>
                                    <div class="col-md-12 mt-2">

                                        <label for="content">Nội dung</label>
                                        <textarea name="content" cols="10" rows="2" class="content form-control " placeholder=" ">{{ old('content') }}</textarea>
                                        <p class="error_msg text-danger" id="content"></p>

                                    </div>

                                </div>
                                <button type="submit p-2" class="btn btn-outline-success w-100 ContactBtn my-4">Gửi liên
                                    hệ</button>
                        </div>

                        </form>
                    </div>
                    <div class="col-md-4 col-12">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d696.8372053988071!2d106.77410669912798!3d10.830299451428312!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752701a34a5d5f%3A0x30056b2fdf668565!2zVHLGsOG7nW5nIENhbyDEkOG6s25nIEPDtG5nIFRoxrDGoW5nIFRQLkhDTQ!5e1!3m2!1svi!2s!4v1681352941314!5m2!1svi!2s"
                            width="425" height="425" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
        </div>

    </section>
@endsection

@section('footer')


    <script type="text/javascript">
        $(".ContactBtn").click(function(e) {
            e.preventDefault();

            // Kiểm tra tất cả các trường đã thỏa mãn điều kiện validate chưa
            let formIsValid = true;
            $(this).closest('.contact-data').find('input, textarea').each(function() {
                if (!this.checkValidity()) {
                    formIsValid = false;
                    $(this).addClass('is-invalid');
                    $(this).siblings('.error_msg').html(this.validationMessage);
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.error_msg').html('');
                }
            });

            // Nếu tất cả các trường đều thỏa mãn điều kiện validate, submit form
            if (formIsValid) {
                var name = $(this).closest('.contact-data').find('.name').val();
                var email = $(this).closest('.contact-data').find('.email').val();
                var phone = $(this).closest('.contact-data').find('.phone').val();
                var title = $(this).closest('.contact-data').find('.title').val();
                var content = $(this).closest('.contact-data').find('.content').val();

                data = {
                    'name': name,
                    'email': email,
                    'phone': phone,
                    'title': title,
                    'content': content,
                }

                $.ajax({
                    type: 'post',
                    url: "contact-admin",
                    data: data,

                    success: function(response) {
                        if ($.isEmptyObject(response.errors)) {
                            swal(response.status);

                        } else {
                            let resp = response.errors;
                            for (index in resp) {
                                $("#" + index).html(resp[index]);
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
