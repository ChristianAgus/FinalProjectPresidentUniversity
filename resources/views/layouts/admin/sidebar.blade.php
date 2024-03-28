<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2" style="text-transform: inherit !important;">Haldinfoods</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('exhibition/dashboard') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li>
        <li class="menu-item {{ request()->is('exhibition/master/*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Form Elements">Master</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('exhibition/master/category/index') ? 'active' : '' }}">
                    <a href="{{ route('category.index') }}" class="menu-link">
                        <div data-i18n="Account">Category</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('exhibition/master/product/index') ? 'active' : '' }}">
                    <a href="{{ route('product.index') }}" class="menu-link">
                        <div data-i18n="Notifications">Product</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ request()->is('exhibition/history') ? 'active' : '' }}">
            <a href="{{ route('order.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div data-i18n="Analytics">Order</div>
            </a>
        </li>
    </ul>
</aside>