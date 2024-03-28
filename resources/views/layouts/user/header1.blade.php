<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="{{ route('frontend.home') }}"><img src="{{ asset('assets/img/logo_400.png') }}" style="height:50px;" alt="Logo Haldinfoods"></a>
    </div>
    <div class="humberger__menu__cart">
        <ul>
            <!-- <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li> -->
            <li><a href="{{ route('frontend.get_cart') }}"><i class="fas fa-shopping-cart"></i> <span id="count_cart_m">{{ $count_cart }}</span></a></li>
        </ul>
        <div class="header__cart__price">item: <span id="grand_cart_m">Rp{{ number_format($grand_cart, 0) }}</span></div>
    </div>
    <div class="humberger__menu__widget">
        <!-- <div class="header__top__right__language">
            <img src="{{ asset('assets/img/language.png') }}" alt="">
            <div>English</div>
            <span class="arrow_carrot-down"></span>
            <ul>
                <li><a href="#">Spanis</a></li>
                <li><a href="#">English</a></li>
            </ul>
        </div> -->
        <!-- <div class="header__top__right__auth">
            <a href="#"><i class="fa fa-user"></i> Login</a>
        </div> -->
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="{{ request()->is('/cart') ? 'active' : '' }}"><a href="{{ route('frontend.get_cart') }}">Cart</a></li>
            <!-- <li class="active"><a href="./index.html">Checkout</a></li>
            <li><a href="./shop-grid.html">History</a></li> -->
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    {{-- <div class="header__top__right__social">
        <a href="https://haldinfoods.com/"><i class="fa fa-globe"></i></a>
        <a href="https://www.facebook.com/haldinfoods"><i class="fa fa-facebook"></i></a>
        <a href="https://www.instagram.com/haldinfoods/"><i class="fa fa-instagram"></i></a>
    </div> --}}
    {{-- <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> ecommerce@haldinfoods.com</li>
            <!-- <li>Free Shipping for all Order of $99</li> -->
        </ul>
    </div> --}}
</div>

<header class="header" id="myHeader">
    {{-- <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__left">
                        <ul>
                            <li><i class="fa fa-envelope"></i> ecommerce@haldinfoods.com</li>
                            <!-- <li>Free Shipping for all Order of $99</li> -->
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">
                            <a href="https://haldinfoods.com/"><i class="fa fa-globe"></i></a>
                            <a href="https://www.facebook.com/haldinfoods"><i class="fa fa-facebook"></i></a>
                            <a href="https://www.instagram.com/haldinfoods/"><i class="fa fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="header__logo">
                    <a href="{{ route('frontend.home') }}"><img src="{{ asset('assets/img/logo_400.png') }}" style="height:50px;" alt="Logo Haldinfoods"></a>
                </div>
            </div>
            {{-- <div class="col-lg-6">
                <nav class="header__menu">
                    <ul>
                        <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('frontend.home') }}">Home</a></li>
                        <li class="{{ request()->is('cart') ? 'active' : '' }}"><a href="{{ route('frontend.get_cart') }}">Cart</a></li>
                        <li class="{{ request()->is('order') ? 'active' : '' }}"><a href="{{ route('frontend.get_order') }}">Checkout</a></li>
                        <li><a href="./shop-grid.html">History</a></li>
                    </ul>
                </nav>
            </div> --}}
            <div class="wrap_cart col-7">
                <a href="{{ route('frontend.get_cart') }}">
                    <span class="header__cart">
                        <i class="fas fa-shopping-cart"></i> <span id="count_cart">{{ $count_cart }}</span>
                        <div class="header__cart__price"> <span id="grand_cart">Rp{{ number_format($grand_cart, 0) }}</span></div>
                    </span>
                 </a>
            </div>
            <div class="col-2">
                <a class="humberger__open _custom">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div>
    </div>

</header>
