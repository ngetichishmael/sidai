<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav bookmark-icons">
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="/chats" title="Chat"><i class="ficon"
                            data-feather="message-square"></i></a>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link bookmark-star"><i class="ficon text-warning" data-feather="star"></i></a>
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">

            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                        data-feather="moon"></i></a></li>
            {{-- <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
            <div class="search-input">
                  <div class="search-input-icon"><i data-feather="search"></i></div>
                  <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1" data-search="search">
                  <div class="search-input-close"><i data-feather="x"></i></div>
                  <ul class="search-list search-list-main"></ul>
            </div>
         </li> --}}
            {{-- <li class="nav-item dropdown dropdown-cart me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="shopping-cart"></i><span class="badge rounded-pill bg-primary badge-up cart-item-count">6</span></a> --}}
            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                <li class="dropdown-menu-header">
                    <div class="dropdown-header d-flex">
                        <h4 class="mb-0 notification-title me-auto">Orders</h4>
                        <div class="badge rounded-pill badge-light-primary">4 Items</div>
                    </div>
                </li>
                <li class="scrollable-container media-list">
                    <div class="list-item align-items-center"><img class="rounded d-block me-1"
                            src="{!! asset('app-assets/images/pages/eCommerce/1.png') !!}" alt="donuts" width="62">
                        <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove" data-feather="x"></i>
                            <div class="media-heading">
                                <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html">
                                        Apple watch 5</a></h6><small class="cart-item-by">By Apple</small>
                            </div>
                            <div class="cart-item-qty">
                                <div class="input-group">
                                    <input class="touchspin-cart" type="number" value="1">
                                </div>
                            </div>
                            <h5 class="cart-item-price">$374.90</h5>
                        </div>
                    </div>
                    <div class="list-item align-items-center"><img class="rounded d-block me-1"
                            src="{!! asset('app-assets/images/pages/eCommerce/7.png') !!}" alt="donuts" width="62">
                        <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove" data-feather="x"></i>
                            <div class="media-heading">
                                <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html">
                                        Google Home Mini</a></h6><small class="cart-item-by">By Google</small>
                            </div>
                            <div class="cart-item-qty">
                                <div class="input-group">
                                    <input class="touchspin-cart" type="number" value="3">
                                </div>
                            </div>
                            <h5 class="cart-item-price">$129.40</h5>
                        </div>
                    </div>
                    <div class="list-item align-items-center"><img class="rounded d-block me-1"
                            src="{!! asset('app-assets/images/pages/eCommerce/2.png') !!}" alt="donuts" width="62">
                        <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove" data-feather="x"></i>
                            <div class="media-heading">
                                <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html">
                                        iPhone 11 Pro</a></h6><small class="cart-item-by">By Apple</small>
                            </div>
                            <div class="cart-item-qty">
                                <div class="input-group">
                                    <input class="touchspin-cart" type="number" value="2">
                                </div>
                            </div>
                            <h5 class="cart-item-price">$699.00</h5>
                        </div>
                    </div>
                    <div class="list-item align-items-center"><img class="rounded d-block me-1"
                            src="{!! asset('app-assets/images/pages/eCommerce/3.png') !!}" alt="donuts" width="62">
                        <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove" data-feather="x"></i>
                            <div class="media-heading">
                                <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html">
                                        iMac Pro</a></h6><small class="cart-item-by">By Apple</small>
                            </div>
                            <div class="cart-item-qty">
                                <div class="input-group">
                                    <input class="touchspin-cart" type="number" value="1">
                                </div>
                            </div>
                            <h5 class="cart-item-price">$4,999.00</h5>
                        </div>
                    </div>
                    <div class="list-item align-items-center"><img class="rounded d-block me-1"
                            src="{!! asset('app-assets/images/pages/eCommerce/5.png') !!}" alt="donuts" width="62">
                        <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove"
                                data-feather="x"></i>
                            <div class="media-heading">
                                <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html">
                                        MacBook Pro</a></h6><small class="cart-item-by">By Apple</small>
                            </div>
                            <div class="cart-item-qty">
                                <div class="input-group">
                                    <input class="touchspin-cart" type="number" value="1">
                                </div>
                            </div>
                            <h5 class="cart-item-price">$2,999.00</h5>
                        </div>
                    </div>
                </li>
                <li class="dropdown-menu-footer">
                    <div class="mb-1 d-flex justify-content-between">
                        <h6 class="mb-0 fw-bolder">Total:</h6>
                        <h6 class="mb-0 text-primary fw-bolder">$10,999.00</h6>
                    </div><a class="btn btn-primary w-100" href="app-ecommerce-checkout.html">Checkout</a>
                </li>
            </ul>
            {{-- </li> --}}

            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name fw-bolder">{!! Auth::user()->name !!}</span>
                        <span class="user-status">{!! Auth::user()->email !!}</span>
                    </div>
                    <span class="avatar">
                        <img src="https://ui-avatars.com/api/?name={!! Auth::user()->name !!}&rounded=true&size=80"
                            alt="profile image" class="round" height="40" width="40" />
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{!! url('logout') !!}"><i class="me-50"
                            data-feather="power"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
