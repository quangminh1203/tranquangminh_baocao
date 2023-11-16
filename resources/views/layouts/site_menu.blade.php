    <section class=" navbarsticky mainmenu my-1 border-1 border-top border-dark border-bottom"id="navbarsticky">


        <div class="container">
            <div class="col-md-12 col-12  main-md-menu fs-4 mx-auto">
                <nav class="navbar  navbar-expand-lg ">
                    <button data-bs-target="#navbar_main" data-bs-toggle="offcanvas"
                        class="d-lg-none btn btn-outline-success navbar-toggler ms-auto" type="button">
                        <span class=" navbar-toggler-icon"></span>
                    </button>
                    <div id="navbar_main" class="offcanvas offcanvas-md offcanvas-start ">
                        <div class="offcanvas-header">
                            <span class="offcanvas-title">

                                <img src="https://demo.wpthemego.com/themes/sw_coruja/wp-content/uploads/2019/11/logo-default.png"
                                    alt="logo" class="img-fluid rounded-top my-auto" width="200px">

                            </span>
                            <button class="btn-close float-end" data-bs-dismiss="offcanvas"></button>
                        </div>

                        <div class="offcanvas-body  ">
                            <div class="col-md-7 col-12">
                                <ul class="navbar-nav">
                                    <x-main-menu />
                                </ul>
                            </div>
                            <div class="col-md-5 col-12">
                                <ul class="navbar-nav float-md-end">
                                    @if (Auth::guard('users')->check())
                                        <li class="nav-item me-5"><a class="nav-link mt-0"
                                                href="{{ route('site.profile') }}"><i
                                                    class="fa-solid fa-arrow-right-to-bracket me-2"></i> Tài khoản</a>
                                        </li>
                                    @else
                                       
                                        <li class="nav-item me-5">
                                            <a class="nav-link  mt-0"
                                                href="" data-bs-toggle="modal" data-bs-target="#myModalLogin"><i
                                                    class="fa-solid fa-arrow-right-to-bracket me-2"></i>Đăng
                                                nhập / Đăng ký</a>
                                            <!-- The Modal -->

                                        </li>
                                        <div class="modal " id="myModalLogin">
                                            <div class="modal-dialog modal-fullscreen-sm-down">
                                                <div class="modal-content">
                                                    @includeIf('frontend.model-login')
                                                </div>
                                            </div>
                                        </div>
                                      
                                    @endif


                                </ul>
                            </div>
                            <div class="col-12 d-md-none d-block">
                                <div class="input-group py-2 ">
                                    <input type="text" class="form-control fs-3" placeholder="Search"
                                        aria-label="Search" aria-describedby="basic-addon2">
                                    <span class="input-group-text px-4" id="basic-addon2">
                                        <i class="fa-solid fa-magnifying-glass fs-4"></i>
                                    </span>
                                </div>
                            </div>

                        </div> 
                        <!-- container-fluid.// -->
                    </div>
                </nav>

            </div>

        </div>

    </section>
   
